## Instructions for running the application

### Dev environment

Execute the following commands:

1. Make up - To build and run docker containers
2. Make db-schema - To create database tables

### Test environment

Execute the following command:

1. Make test/coverage - To build and run docker test containers and execute phpunit tests coverage

## Design Considerations

I have developed the architecture of the application in a bounded-context and in three layers according to DDD, where
the inner layers cannot have knowledge of the outer layers.
The main bounded-context is Images and inside it, we can find the three layers of the application:

### Infrastructure:

Is the external layer. This layer provides the concrete implementations needed to interact with the outside world, such
as databases, controllers, etc.

### Application:

Is the middle layer. This is where the domain objects are coordinated and managed to achieve the application use cases.

### Domain:

Is the internal layer. This is where the business rules are defined and represented. In this layer we can found
entities, valueObjects, interfaces and domain services.

## Use cases:

### Create Images:

In this request, a json is received with a list of images.

What I do is create each image with its initial data and also initialize the weight, views and clicks of it to zero.

### Create Event:

In this request, a json is received with an event type for an image. Here I do two things:

1. Create the event with its data.
2. Update the event image. I calculate the weight of the event and add it to the weight of the image. I also increase
   the value of the views or clicks of the image by one, depending on the type of the event.

### Find all Images:

In this request we are asked for a list of all the images ordered by their weight and creation date in descending order.

What I do here is make a query to the database and obtain the list of images ordered by their weight fields and creation
date in descending order. As each image has in its own fields the sum of the weight of each of its events, as well as
the amount of views and clicks type events, this query is carried out very quickly.
Additionally, to further optimize and speed up the search, I have created a SQL index for the image table for the weight
and creation date columns.
