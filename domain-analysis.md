# Domain Analysis

## Sources

* Example of a php ddd project: https://github.com/CodelyTV/php-ddd-example
* doctrine and ddd by matthiasnoback: https://matthiasnoback.nl/2018/06/doctrine-orm-and-ddd-aggregates/
* php with ddd: https://entwickler.de/online/php/ddd-patterns-domain-driven-design-185328.html
* why the frontend does not fall under ddd: https://khalilstemmler.com/articles/typescript-domain-driven-design/ddd-frontend/
* A lot of YouTube Videos that I can't list all here

## Understanding the Domain

I describe the process that my software should handle. In real life scenarios these would come from the client. The client would describe to you how their customers use the domain of the client. YOU have to understand it and ask the right questions to get answers. In the real world this would probably consist of a lot of email/phone communication with the client. Your goal is to understand the domain and the use cases of the customer. I have the advantage that I am the client and customer in one person. So I know exactly what my software should do. I describe this in a very short and precise way.

### Processes

0. Users need to be authenticated to use all of the following processes.

1. Users want to be able to track their todos. They wanna create, read, update and delete (hard) their todos as wells as mark them as done. 

2. Todos that are marked as done have a "Done" state. Every todo has a state. Viable States are: Open, Done. Default is Open. 

3. The Users can schedule their todos for a specific date. If the date is not due it cannot change its state to Done.

4. The Users can create recurring todos. They have to provide a date range and can decide in which frequency the todos are created. Only the collection as a whole can be edited which affects all todo items. Todo items belong to a recurring collection. Recurring todos should not be able to be deleted as single items. A collection has to be deleted as a whole. Each individual item can change state separately.

Notice: I do not mention anything about the frontend here because the frontend ist *NOT* part of our domain. https://khalilstemmler.com/articles/typescript-domain-driven-design/ddd-frontend/

## Terminology

Having a unified terminology is a core principle of DDD. Every process/entity should have its distinguished name based on its context. We can derive the terminology from the process described above.

Based on those descriptions I can see two different contexts that are different from another. 

The first context spans over the first 3 points. Here we're dealing with CRUD operations on singular todo items. The typical todo app that everyone knows. Every todo item is separate from each other. I can change all attributes of it. Optionally it can get a scheduled date. This todo item can be saved to the database as is. It's a simple todo app really.

What about the second context? We are still dealing with todos but this time we have some significant changes in behavior. 

First, each todo items is now related to other todos (not directly but implicit) that are in the same recurring collection. That collection holds information about a date range and a frequency. In simple words a recurring collection of todos. 

Second, I can't change the todo items individually anymore and I can't delete them as single units. While we have some similarities with the first context (marking individual todos as done) we also have significant differences as described. 

But the biggest difference is that these todo items are not saved to the DB as individual items anymore. Instead the recurring collection will be saved which preserves the state of the marked todo items.

Coming from a traditional symfony application where every model corresponds to a database table we see that we have a problem. Let's say a todo item would be a Model `Todo` in the database. How can we put both processes in this model? 

Simple answer: We can't. 

Let's take a deeper look into the `Todo` Model. In the first context the todo item has a schedule date. In the second context it doesn't. Instead it belongs to a recurring collection that holds a date range that the todo item in the first context doesn't have. 

What's the gist of all of this? 

We now have a Term/Model `Todo` in both contexts that mean totally different things in both scenarios.

Let's transform these findings into a ubiquitous language

### Context 1: Individual Todos

* Users -> can login, end users of the software
* Todo -> a single to do item
* TodoState -> a definite state that the system can act upon (Done, Open)
* ScheduleDate -> The date where the todo is due


## Domains

After identifying the process and settle for a ubiquitous language we can define what is core, supporting and generic for our project.

* Identity Management 