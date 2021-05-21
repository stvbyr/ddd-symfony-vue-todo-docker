# Domain Analysis

## Prerequisites

Before I can analyse anything I need some fundamentals that I can build on. These are just my understandings of DDD, Bounded Context, etc. I may be wrong here as this is my first sort of project where I use DDD. But I think it's important to clarify what each of the concepts mean to me as there is a lot of discussion around different terms and what they mean and how to interpret them. 

It's funny/ironical: DDD says that your domain and business case is what matters most. Which implies that the same thing has different meaning in different contexts (User vs Customer for example). Or in DDD-words, we use ubiquitous language in different contexts. But then we try to describe DDD with terminology like "aggregate" that was meant to have the same meaning for everyone. But actually they mean different things to different people. This confused me a lot. Still does. But that just as a little side note.

I have to admit that I didn't read "the book" yet and I all I know stems from articles and YouTube. But I will probably read it soon.

### Backend as single source of truth

The whole project consists of the backend (symfony) and a frontend (vue). This analysis will just focus on the backend for now. The backend is where the core domain logic lies. This backend will be accessible through an API. This is were the boundary between the backend and everything else is. I can't control what the API consumer will do with my API. It could be that they just read from it or just write to or both. In this case I am the consumer myself but that shouldn't be a factor for designing this backend.

### Layered architecture

I use a layered architecture to structure my code. 

| Layer            | Meaning                                                                                          | Can depend on       |
|------------------|--------------------------------------------------------------------------------------------------|---------------------|
| Domain           | Core business logic, entities, aggregates, invariants, domain services                           | Isolated            |
| Application(API) | Application logic (application services), usage of domain aggregates and Infrastructure          | Domain              |
| Infrastructure   | Concrete implementations of the domain layer interfaces and connection to external services (db) | Domain, Application |

## Understanding the Domain

I describe the process that my software should handle. In real life scenarios these would come from the client. The client would describe to you how their customers use the domain of the client. These are called "domain experts". YOU have to understand it and ask the right questions to these people to get answers. 

In the real world this would probably consist of a lot of email/phone communication with the client. Your goal is to understand the domain and the use cases of the customer. 

I have the advantage that I am the client and customer in one person. So I know exactly what my software should do. I will describe this in a very short and precise way.

### Processes

The goal is to write software where Users can track their productivity. What does this exactly mean? These processes describe what my software should do.

0. Users need to be authenticated to use all of the following processes.

1. User wants to be able to track their todos. They wanna create, read, update and delete their todos as wells as mark them as done. 

2. Every todo has a status. Todos that are marked as done have a "Done" status. Viable Statuses are: Open, Done. Default is Open. 

3. The Users can (optionally) schedule their todos for a specific date. If the date is not due it cannot change its state to Done.

4. User wants to create recurring todos, we call them habits. They wanna create, read, update and delete their habits as wells as mark the individual stamps(this is the exact day the habit is due, like a timestamp) as done.

5. User has to provide a date range and can decide in which frequency the habits are created. Only the habit as a whole can be edited which affects all stamps.

6. Stamps can't be deleted individually. A habit has to be deleted as a whole. Each individual stamp can change state separately.

## Terminology/Ubiquitous language

Having a unified terminology is a core principle of DDD. Every process/entity should have its distinguished name based on its context. We can derive the terminology from the process described above.

Based on those descriptions I can see two different bounded contexts that are different from another. 

![Image of Productivity domain with its bounded contexts](/resources/Domain.png?raw=true "Image of Productivity domain with its bounded contexts")

### Context 1: Todos

The first context spans over the first 3 points. Here we're dealing with CRUD operations on singular todo items. The typical todo app that everyone knows. Every todo is separate from each other. Optionally it can get a scheduled date. This todo item can be saved/updated to the database as is. It's a simple todo app really.

| Term          | Meaning                                                    |
|---------------|------------------------------------------------------------|
| User          | auth user                                                  |
| Todo          | a single to do item                                        |
| Status        | a definite Status that the todo item can have (Done, Open) |
| Title         | the title of the todo                                      |
| ScheduledDate | the date where the todo is due                             |

The `Todo` is the aggregate. It gets a unique id.

### Context 2: Habits

What about the second context? We are still dealing with todos, well sort of, but this time we have some significant changes in behavior. 

First, each todo item is now called stamp and related to other stamps (not directly but implicit) that belong to the same habit. That habit holds information about a date range and a frequency. In other words a recurring collection of todos. 

Second, I can't edit the stamps individually anymore and I can't delete them as single units. While we have some similarities with a todo item (marking individual units as done) we also have significant differences. 

Why do I mix context 1 with context 2 now?

Coming from a traditional symfony application you will likely use the Doctrine ORM. As you can see a todo item and a stamp are not that different from each other. It is tempting to just build a super todo class that maps with the database and can both act as a todo in the sense of the first context but can also mashed together as a series of todos that form a habit in the second context. To do that a lot of conditional logic would be necessary. Why do I mention this?

Because I was there. I did such things and I know from experience that these kind of things can go disastrously wrong. Sure you have an object that does not duplicate logic but you couple both context into one class. Just by reading the previous paragraph you should feel wrong. Because it is. Don't do that.

| Term      | Meaning                                                             |
|-----------|---------------------------------------------------------------------|
| User      | auth user                                                           |
| Stamp     | one of many exact dates were the habit is due                       |
| Status    | a definite Status that the Stamp can have (Done, Open)              |
| Title     | the title of the habit                                              |
| Habit     | a set of Stamps                                                     |
| DateRange | a date boundary where the stamps are created in                     |
| Frequency | determines how often the Stamps should be created in the date range |

The `Habit` is the aggregate. It gets a unique id.

### Gist?

What's the gist of all of this? 

We now have a Term/Model `TodoItem` in both contexts that mean totally different things.

Let's transform these findings into a ubiquitous language. I tried to use as much ubiquitous terminology as possible in this section.

## Conceptualize the Domain

After identifying the process and settle for a ubiquitous language we can define what is core, supporting and generic for our project. In other words: Which things out of the following list are Domain related, Infrastructure related, Application related or UI related.

* User -> Domain
* Single Todos -> Domain
* Recurring Todos -> Domain
* Authentication -> Infrastructure
* API -> UI

## Getting more Technical

As we've seen the first use case in terms of singular todo items and recurring todo items are fundamentally different. Now we have to model this and come up with a suitable domain model (and database base transactions as well).

From a users perspective we want to make sure that the handling of singular todo items are similar to the recurring ones. Obviously this is a matter of the frontend and is not affecting our domain model. 

On the backend though we save and retrieve singular todo items 

### The Models


## Sources

* Example of a php DDD project: https://github.com/CodelyTV/php-ddd-example
* doctrine and DDD by matthiasnoback: https://matthiasnoback.nl/2018/06/doctrine-orm-and-ddd-aggregates/
* php with DDD: https://entwickler.de/online/php/ddd-patterns-domain-driven-design-185328.html
* why the frontend does not fall under DDD: https://khalilstemmler.com/articles/typescript-domain-driven-design/ddd-frontend/
* cargo example: https://github.com/codeliner/php-ddd-cargo-sample
* [A lot of YouTube Videos that I can't list all here](https://www.youtube.com/watch?v=pfMGgd_NDPc)