# kollex Coding Challenge – Wholesaler Integration

Solution by Jaroslav

## What is important

- many integrations, various formats
- importing product lists in various formats, schemas, protocols

## Task

- write a modern, maintainable, testable and extendable app
    - pragmatic, solid architecture, not over-engineered
    - fully testable for continuous integration and delivery 
    - [ ] recent PHP
    - [ ] single responsibility classes
    - [ ] use existing components
    - [ ] write unit tests
    - [ ] write behavioral tests
- extendable to new sources or data formats
    - parse and validate product lists in various formats
    - [ ] validate according to swagger
    - [ ] source abstraction
    - [ ] format adapter
    - [ ] implement schema: [ ] json [ ] csv 
    - [ ] implement source: [ ] file-reader
    - [ ] implement output: [ ] json [ ] xml
    - [ ] generate model from spec
    - [ ] implement kollex\Dataprovider\Assortment\Product
        - [ ] expand as necessary
- best practices
   - [ ] describe classes briefly, avoid zero-info comments
   - [ ] defensive programming
   - [ ] code to interface
   - [ ] type hinting, return types
   - [ ] 12-factor app
- target
  - [ ] parse all data and json-encode as defined in swagger-Product
  - [ ] skip elaborate API / CLI frontend
  - [ ] provide documentation of code, concepts, possible extensions
  - [ ] provide rationale for coieces of specific structure, patterns
  - [ ] privide reasons for picking libraries / frameworks compared to other options 
  - [ ] desicions regarding test methodology
  - [ ] docker-compose up


## Desicions

- Write about normalization / sapace tradeofs
    - memory consumption
    - join complexity
