# Domain Analysis

## Sources

* Example of a php DDD project: https://github.com/CodelyTV/php-ddd-example
* doctrine and DDD by matthiasnoback: https://matthiasnoback.nl/2018/06/doctrine-orm-and-ddd-aggregates/
* php with DDD: https://entwickler.de/online/php/ddd-patterns-domain-driven-design-185328.html
* why the frontend does not fall under DDD: https://khalilstemmler.com/articles/typescript-domain-driven-design/ddd-frontend/
* cargo example: https://github.com/codeliner/php-ddd-cargo-sample
* [A lot of YouTube Videos that I can't list all here](https://www.youtube.com/watch?v=pfMGgd_NDPc)

## Understanding the Domain

I describe the process that my software should handle. In real life scenarios these would come from the client. The client would describe to you how their customers use the domain of the client. YOU have to understand it and ask the right questions to get answers. In the real world this would probably consist of a lot of email/phone communication with the client. Your goal is to understand the domain and the use cases of the customer. I have the advantage that I am the client and customer in one person. So I know exactly what my software should do. I describe this in a very short and precise way.

### Processes

0. Users need to be authenticated to use all of the following processes.

1. Users want to be able to track their todos. They wanna create, read, update and delete (hard) their todos as wells as mark them as done. 

2. Todos that are marked as done have a "Done" state. Every todo has a state. Viable States are: Open, Done. Default is Open. 

3. The Users can schedule their todos for a specific date. If the date is not due it cannot change its state to Done.

4. The Users can create recurring todos. They have to provide a date range and can decide in which frequency the todos are created. Only the recurring collection as a whole can be edited which affects all todo items. Upon editing the date range or frequency the state resets. Todo items belong to a recurring collection. Todo items should not be delegable. A recurring collection has to be deleted as a whole. Each individual item can change state separately.

Notice: I do not mention anything about the frontend here because the frontend ist *NOT* part of our domain. https://khalilstemmler.com/articles/typescript-domain-driven-design/ddd-frontend/

## Terminology

Having a unified terminology is a core principle of DDD. Every process/entity should have its distinguished name based on its context. We can derive the terminology from the process described above.

Based on those descriptions I can see two different contexts that are different from another. 

The first context spans over the first 3 points. Here we're dealing with CRUD operations on singular todo items. The typical todo app that everyone knows. Every todo item is separate from each other. Optionally it can get a scheduled date. This todo item can be saved/updated to the database as is. It's a simple todo app really.

What about the second context? We are still dealing with todos but this time we have some significant changes in behavior. 

First, each todo item is now related to other todo items (not directly but implicit) that are in the same recurring collection. That collection holds information about a date range and a frequency. In simple words a recurring collection of todos. 

Second, I can't change the todo items individually anymore and I can't delete them as single units. While we have some similarities with the first context (marking individual todos as done) we also have significant differences. 

Coming from a traditional symfony application where every model corresponds to a database table we see that we have a problem. Let's say a todo item would be a Doctrine Model `TodoItem` in the database. How can we put both processes in this model? 

Simple answer: We can't. 

In the first context the todo item has a schedule date. In the second context it doesn't. Instead it belongs to a recurring collection that holds a date range that the todo item in the first context doesn't have. 

What's the gist of all of this? 

We now have a Term/Model `TodoItem` in both contexts that mean totally different things.

Let's transform these findings into a ubiquitous language. I tried to use as much ubiquitous terminology as possible in this section.

### Context 1: Individual Todos

| Term          | Meaning                                                    |
|---------------|------------------------------------------------------------|
| Users         | auth user                                                  |
| TodoItem      | a single to do item                                        |
| Status        | a definite Status that the todo item can have (Done, Open) |
| Title         | the title of the todo                                      |
| ScheduledDate | the date where the todo is due                             |

The `TodoItem` is the aggregate. 

### Context 2: Recurring Todos

| Term                    | Meaning                                                    |
|-------------------------|------------------------------------------------------------|
| Users                   | auth user                                                  |
| TodoItem                | a single to do item that belongs to a collection           |
| Status                  | a definite Status that the todo item can have (Done, Open) |
| Title                   | the title of the todo                                      |
| RecurringTodoCollection | a set todo items                                           |

Now these terms are similar but with a noticeable difference. The TodoItem must now belong to collection. This means that the `RecurringTodoCollection` is now the aggregate and the `TodoItem` is the child. This is because a single todo item is not the center of interest anymore but rather the collection of them.
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

