# Wholesaler Integration (kollex)

Solution by Jaroslav

## Run the stuff

alternatves to run the demo:

1. docker-compose build ; docker-compose up
     - depends on docker
2. make all
     - depends on local php

## What is important

- many integrations, various formats
- importing product lists in various formats, schemas, protocols

## Format

    `- [x] means done`
    `- [.] means started`
    `- [/] means aborted`

...renders like:

    - [x] done
    - [.] kinda done
    - [/] aborted

## Task

I made this concise summary of the sought-after points / skills before I began
working on the solution. Some of the stuff I wanted to do is aspirational,
some is routine. Today, I am going through the list and marking what I managed,
or not.

Please refer to the diagram below.

- write a modern, maintainable, testable and extendable app
  - pragmatic, solid architecture, not over-engineered
  - fully testable for continuous integration and delivery 
  - [x] recent PHP
  - [x] single responsibility classes
  - [x] use existing components
  - [x] write unit tests
  - [/] write behavioral tests
- extendable to new sources or data formats
  - parse and validate product lists in various formats
  - [.] validate according to swagger
  - [x] source abstraction (data-provider)
  - [x] format adapter ( JSON / CSV )
  - [x] implement schema:
    - [x] JSON
    - [x] CSV 
  - [x] implement source:
    - [x] file-reader
    - [/] http-reader
  - [.] implement output:
     - [x] json
     - [ ] xml
  - [.] generate model from spec (yaml)
  - [x] implement `kollex\Dataprovider\Assortment\Product`
    - [.] expand as necessary
- best practices
  - [x] describe classes briefly, avoid zero-info comments
  - [x] defensive programming
  - [x] code to interface
  - [x] type hinting, return types
  - [.] 12-factor app (https://12factor.net)
- target
  - [x] parse all data and store as defined in swagger-Product
  - [x] skip elaborate API / CLI front-end
  - [.] provide documentation of code, concepts, possible extensions
  - [.] provide rationale for choices of specific structure, patterns
  - [.] provide reasons for picking libraries / frameworks compared to other options 
  - [x] decisions regarding test methodology
  - [x] docker-compose up

## Desicions


### Table nomralization

We have a few units that appear in several columns of the product entity (packaging, smallest unit, unit of measure).
I mean units such as LT GR CN BO.One might think that it is space-efficient and clean to store these in a separate table.

I chose to store them as strings for ease of use and clarity, without foreign references to a "units" table.

Given that the tables where they are used (products), will grow with time, I would much rather
have direct access to the values instead of having to join with a "units" table because joins are very costly.

The units are not likely to change very often and we can exploit system-memory based cache on the nodes 
that serve the web requests of our users.


### Default length for some fields

Given lack of more precise specifications, all text fields such as name and id are initialized with the
default lengths for `varchar` (255 characters).

### Public fields in product

There is no public API for export here, I own all the code in this challenge.
Therefore, there is no need to encapsulate the entity properties as private, therefore,
I have all the properties of `Public` public. It serves only as a Message-object and the
implication is that it is immutable. Since PHP does not have constructs for communicating
strict immutability, I am not going to bother polluting the class with mutator functions.

### Dependency encapsulation

In case we find out that one or several of the modules we started with prove to be too slow or lacking in features,
it is wise to encapsulate business-critical entities and services with our own
`shell-services` and classes as I have done with `ProductMapper`, `ProductValidator`, `FileReader`, `ProductEntity`, to
name a few.

### Basic string functions in `adapter->decode()`

I hold that the memory/CPU cost of `explode` and regular expressions in not worth the
convenience for tackling fairly predictable input data. Further, parsing single fields such as `packaging`
with `explode()` and `preg_match()` would not be more elegant than using `substr()`, `strpos()` and `strtok()`, etc.

### Export as a format

Admittedly, this part of the app is not well thought out.
Currently we can export items as `JSON`, but some of the logic should be refactored and moved out from `main` and into
the exporter.

## Architecture

The task breaks down into two main actions that the user may perform.  
In the class diagrams, the green arrows imitate a sequence diagram
and represent the logical flow.

### Import data files scenario

We need to read files from a folder and store them in our database,
while converting to our internal schema.

![import](doc/import-scenario.class.png "Import scenario")


### List products scenario

The entry point is from the `displayProducts()` function.

![list](doc/list-scenario.class.png "Import scenario")


### Notes about the implementation

The `Sourceinterface` is currently overly simplistic. It is potentially very memory costly.
To make it more robust, one would support dividing the import process into several chunks
and support resuming after a failed or aborted import operation.

So in other words, the import-job should be split into several independent sections.

### Framework

I have refrained from elaborate DI / service locator frameworks because the task description
called for that.


### Documentation

I have left class-level comments on some of the interfaces, detailing the business needs.
On the syntax-level I have purposefully removed fluffy comments about parameter types and return values
because PHP 7 has syntax to declare those things.

## Test plan

I have not implemented any major or important integrations with this coding challenge, because of this, I have chosen
to cover the relevant business logic unit tests. From what I gather, the parsing and validation of products seem
to be a priority, and that is what I have focused on.

## External libraries

Doctrine and Symfony-validator cover 90% of the use-cases of all the projects I have worked with in the last few years,
yet still It seems that every year or so someone has a more "fluent" solution. I don't care for that. I want to use 
stable and proven packages - being aware of their limitations and bugs - and to be effective with the "standard"
solutions. In my experience, most performance issues arising from use of ORM are easily addressed  by writing custom 
queries, and Doctrine (with DQL) excels at that.

