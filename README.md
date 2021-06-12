# Symfony 5 Todo App

With this project I wanted to create a full stack project that uses a variety of different tools. First and foremost I wanted to explore a DDD approach for this project as I think that this would improve my engineering skills a lot.

To not overcommit I wanna create a simple todo app. More on that in the domain analysis.

__This Project is a WIP. So big changes are very likely.__
## Stack

* Docker
* Nginx
* Symfony 5
* Mysql 8
* Vue.js 3

## Installation

You need docker installed as well as [composer](https://getcomposer.org/) to manage the php symfony backend and [node.js + npm](https://nodejs.org/en/) to manage the vue app.

Clone the project 
```zsh
git clone https://github.com/stvbyr/ddd-symfony-vue-todo-docker.git
cd ddd-symfony-vue-todo-docker
```

Rename the `.env.sample` file to `.env` and configure it to your liking. This file is only used by docker. The symfony backend uses its own `.env` file.

```zsh
cd frontend 
npm i
cd ../symfony
composer install
```

Wire up docker to get the development environment started
```zsh
docker-compose up -d
```

Now you can access
* symfony backend: http://localhost:8080
* vue dev server: http://localhost:3000

For a production environment run (WIP)
```zsh
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
```

This runs `npm run build` on the vue image instead of booting up the dev server. It also sets the `APP_ENV=prod` so that the backend is started in production mode. The built vue frontend can then be accessed over: http://localhost:3001

## Domain Analysis
### Prerequisites

Before I can analyse anything I need some fundamentals that I can build on. These are just my understandings of DDD, Bounded Context, etc. I may be wrong here as this is my first sort of project where I use DDD. If you have any suggestions I would be happy if you let me know. I think it's important to clarify what each of the concepts mean to me as there is a lot of discussion around different terms and what they mean and how to interpret them. 

It's funny/ironical: DDD says that your domain and business case is what matters most. Which implies that the same thing has different meaning in different contexts (User vs Customer for example). Or in DDD-words, we use ubiquitous language in different contexts. But then we try to describe DDD with terminology like "bounded context" that was meant to have the same meaning for everyone. But actually they mean different things to different people. This confused me a lot. Still does. At least it seems like that.

Here is how define the terminology:
* __Domain__: the combined knowledge about a business or use case. Most of the time there is a domain expert that knows the ins and outs of domain, their rules etc.
* __Subdomain/Bounded Context__: a clearly separated boundary between business concerns. Each of these contexts define their own rules. This means that the same things means different things in different contexts. For the sales apartment the person is just someone with payment information (address, credit card info) but for the marketing apartment it is actually important to know who the person is (age, interests, etc.). 
* __Ubiquitous language__: Based on the context we have to decide how concepts are named. In the sales context the person might be called account while in the marketing context they might be called audience or target.
* __Value Object__: A dumb object without any logic that just holds data
* __Entities__: Objects that have unique ID. Entities do not know about any database. Their properties can be made up of primitives, Value Objects or other Entities.
* __Aggregates__: Compounds of objects that represent a concept of the domain
* __Aggregate root__: The entry point into the aggregate. It is the thing(s) that the context is concerned about. Will most of the time be an entity.
* __Invariants__: The specification or business rules that apply to the domain

I have to admit that I didn't read "the book" yet and I all I know stems from articles and YouTube. But I will probably read it soon.

#### Backend as single source of truth

The whole project consists of the backend (symfony) and a frontend (vue). This analysis will just focus on the backend for now. The backend is where the core domain logic lies. This backend will be accessible through an API. This is were the boundary between the backend and everything else is. I can't control what the API consumer will do with my API. It could be that they just read from it or just write to it or both. In this case I am the consumer myself but that shouldn't be a factor for designing this backend.

#### Layered architecture

I use a layered architecture to structure my code. More precisely a onion structure.

| Layer          | Meaning                                                                                          | Can depend on          |
|----------------|--------------------------------------------------------------------------------------------------|------------------------|
| Domain         | Core business logic, entities, aggregates, invariants, domain services                           | Isolated               |
| Infrastructure | Concrete implementations of the domain layer interfaces and connection to external services (db) | Domain                 |
| Application    | Application logic (application services), usage of domain aggregates and Infrastructure          | Domain, Infrastructure |
| UI             | Controllers, console commands. Lives under a separate namespace App\                             | Application            |
| View           | Presents the data                                                                                | UI                     |

(Technically their would be a core layer which would contain low level concepts such as Lists, Stacks, etc. but I skip this.)

1. The domain is at the center. There lives our business logic which consists of aggregates, entities, domain interfaces, business rules, invariants(valid before creating), validation(valid after creation). It cannot use the application or infrastructure layer and doesn't even know that they're exist. Hence the domain is not allowed to contain any dependencies to any layer that is above it.
2. Finally the infrastructure provides concrete implementations for the domain interfaces and enables the communication to third party services. For instance, a domain defines a repository interface but it does not know anything about databases, redis caches or any other outside stuff. The infrastructure is responsible for implementing that repository so that the aggregates can be saved and retrieved from a database or stored inside a redis cache. 
3. The application is the entry point to our domain. The application ensures that we get the resources that we need from the domain *BUT* the application is not allowed to contain any domain logic. It can use aggregates and domain services as well as the infrastructure.
4. The UI layer sits on top of the application layer. Its purpose is to provide a concrete interface to our application and the outside world such as http responses or REST API responses. The UI layer can only use application services to communicate with the domain.
5. The view is responsible for presenting the data that it gets from the UI. It doesn't know anything about the domain logic, it just presents it.

#### CQRS

I use the Command-Query-Responsibility-Segregation Principle. With this I am able to split my read and write operation to separate places as well as enforce a standardized way of retrieving and storing data. I use the symfony message bus to send commands and queries to their respective handlers.

### Understanding the Domain

I describe the process that my software should handle. In real life scenarios like this would come from the client. The client would describe to you how their customers use the domain. These are called "domain experts". YOU have to understand it and ask the right questions to these people to get answers. 

In the real world this would probably consist of a lot of email/phone communication with the client. Your goal is to understand the domain and the use cases of the customer. 

You then model the domain and agree on a ubiquitous language that is used in communication and code.

I have the advantage that I am the domain expert and user in one person. So I know exactly what my software should do. I will describe this in a very short and precise way.

#### Processes

The goal is to write software where Users can track their productivity. What does this exactly mean? These processes describe what my software should do.

0. Users need to be authenticated to use all of the following processes.

1. User wants to be able to track their todos. They wanna create, read, update and delete their todos as wells as mark them as done. 

2. Every todo has a status. Todos that are marked as done have a "Done" status. Viable Statuses are: Open, Done. Default is Open. 

3. The Users can (optionally) schedule their todos for a specific date. If the date is not due it cannot change its state to Done.

4. User wants to create recurring todos, we call them habits. They wanna create, read, update and delete their habits as wells as mark the individual moves as done(A move is the equivalent of a todo in the first example, these are the steps that the user has to do to the goal. Hence a move.).

5. User has to provide a date range and can decide in which frequency the habits are created. Only the habit as a whole can be edited which affects all moves.

6. Moves can't be deleted individually. A habit has to be deleted as a whole. Each individual move can change status separately.

### Terminology/Ubiquitous language

Having a unified terminology is a core principle of DDD. Every process/entity should have its distinguished name based on its context. We can derive the terminology from the process described above.

Based on those descriptions I can see two different bounded contexts that are different from another. 

![Image of Productivity domain with its bounded contexts](/resources/Domain.png?raw=true "Image of Productivity domain with its bounded contexts")

#### Context 1: Todos

The first context spans over the first 3 (1. - 3.) points. Here we're dealing with CRUD operations on todo items. The typical todo app that everyone knows. Every todo is separate from each other. Optionally it can get a scheduled date. It's a simple todo app really.

| Term          | Meaning                                                    |
|---------------|------------------------------------------------------------|
| User          | the user that the todo item belongs to                     |
| Todo          | a single to do item                                        |
| Status        | a definite Status that the todo item can have (Done, Open) |
| Title         | the title of the todo                                      |
| ScheduledDate | the date where the todo is due                             |

The `Todo` is the aggregate. It gets a unique id.

#### Context 2: Habits

What about the second context? We are still dealing with todos, well sort of, but this time we have some significant changes in behavior and name them accordingly. 

First, each todo item is now called a "move" and related to other moves (not directly but implicit) that belong to the same "habit", because a habit forms from repeating the same thing over and over again. That habit holds information about a date range (how long do I wanna do/practice this habit?) and a frequency(how often should it repeat?daily?weekly?). In other words a recurring collection of todos. 

Second, I can't edit the moves individually anymore and I can't delete them as single units, because the focus lies on the habit itself. A habit only forms if you have a consistent plan so it makes no sense to make changes to individual moves or even delete them. (at least in theory :D ) While we have some similarities with a todo item (marking individual units as done) we also have significant differences. The biggest one is that a move is NOT a primary concern anymore. 

Why do I compare context 1 with context 2? Is the sole purpose of bounded context to separate concerns? Absolutely.

Coming from a traditional symfony application you will likely use the Doctrine ORM. As you can see a todo item and a move are not that different from each other. It is tempting to just build a super todo class that maps with the database and can both act as a todo item in the sense of the first context but can also be mashed together as a series of todos that form a habit in the second context. To do that a lot of conditional logic would be necessary. Why do I mention this?

Because I was there. I did such things and I know from experience that these kind of things can go disastrously wrong. Sure you have an object that does not duplicate logic but you couple both context into one class. Just by reading the previous paragraph you should feel wrong. Because it is. Don't do that.

After reading this you might think: I would've never put both contexts in one class. But I would guess that you did things like this in the past. Think about the last time you extended a model because you don't wanted to have duplication even though you import methods that you don't need or even worse override already existing methods to bend the new class to your needs. Thats the trap. Thinking that you can sacrifice everything for duplication free code. It might work in the short term but create coupling that is not necessary.

But in this scenario it is ok to have duplication. The use cases are completely different. 

| Term      | Meaning                                                            |
|-----------|--------------------------------------------------------------------|
| User      | the user that the habit belongs to                                 |
| Move      | one of many exact dates were the habit is due                      |
| Status    | a definite Status that the Move can have (Done, Open)              |
| Title     | the title of the habit                                             |
| Habit     | a set of Moves                                                     |
| DateRange | a date boundary where the moves are created in                     |
| Frequency | determines how often the Moves should be created in the date range |

The `Habit` is the aggregate. It gets a unique id.

### Conceptualize the Domain

After identifying the process and settle for a ubiquitous language we can define what is core, supporting and generic for our project. In other words: Which things out of the following list are Domain related, Infrastructure related, Application related or UI related.

* User -> Domain
* Single Todos -> Domain
* Recurring Todos -> Domain
* Authentication -> Infrastructure
* API -> UI

### Getting more Technical

As we've seen the first use case in terms of singular todo items and habits are fundamentally different. Now we have to model this and come up with a suitable domain model (and database base transactions as well).

From a users perspective we want to make sure that the handling of singular todo items are similar to the recurring ones. Obviously this is a matter of the frontend and is not affecting our domain model. 

On the backend though we save and retrieve singular todo items 

#### The Models


### Sources

* Example of a php DDD project: https://github.com/CodelyTV/php-ddd-example
* doctrine and DDD by matthiasnoback: https://matthiasnoback.nl/2018/06/doctrine-orm-and-ddd-aggregates/
* php with DDD: https://entwickler.de/online/php/ddd-patterns-domain-driven-design-185328.html
* why the frontend does not fall under DDD: https://khalilstemmler.com/articles/typescript-domain-driven-design/ddd-frontend/
* cargo example: https://github.com/codeliner/php-ddd-cargo-sample
* [A lot of YouTube Videos that I can't list all here](https://www.youtube.com/watch?v=pfMGgd_NDPc)
* https://dev.to/patryk/symfony-messenger-component-for-cqrs-applications-884