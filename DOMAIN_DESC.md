# Domain Description

Every project or service has a domain. A domain is the top most layer that you can have. A domain is the core that YOU (and the customer) are concerned about. We are talking about software here but you could actually apply this to business processes in general. If you own a business you provide value and earn money in that specific domain. 

Let's look at an example. If you're a webdesigner your domain would be designing stuff for your customers. 

The core would be all the things that you need to start a conversation with the customer for creating the design and finally getting payed. If you would transfer that to software this could be crm system where you can manage customers and show them the designs that you made. They can vote on your samples and get to download the final pieces from the software after the payment was successful. Let's put that knowledge into a list.

* customers (users)
* offer
* contract
* designs
* votes/voting
* download
* payment(succeeded/failed)

This is what your domain model could look like. It includes all the things that were described above. Now you could transfer each item of this list into a domain model that holds the business logic of your application.

In a lot of paradigms a model is equal to a database representation. Each models maps a specific table in the database. But in DDD this is not the case all the time. For example a design model could consist of a provisionally design where you give the customer a look and feel for their requirements. After they have voted on one design you might take their criticism and make a final design based of those provisional designs. This could be a design from the provisional phase but could also be a new one that merges all the previous together.

This sounds complicated and it actually is. But this shows that a domain can get pretty complex pretty fast.

What is not in your domain? The payment process itself. Sure you wanna know that you got paid BUT you are not interested or even concerned about how that happened. This is the responsibility of your payment provider.

So a domain can consist of multiple projects that includes multiple services that includes multiple different modules and so on. 

That sounds complicated again? It kinda is but so is the real world. 

Modeling a domain is not a easy task. Humans believe that they can predict the future but in most cases they're horribly wrong. (See all the traders at wallstreet. Well, the one that don't get right.) That applies to software as well. You can't predict whether a feature will be added or removed from your domain. You can't predict if it is fundamentally changing.

