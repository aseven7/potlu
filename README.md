# Potlu Framework
Potlu is simple PHP framework for multipurpose project, inspired from java beans. 
Powered by : gentelella admin themes (User interface in this example), Idiorm (database ORM), FlightPHP (route).


### Application Structure

Although most of the php frameworks apply MVC (Model-View-Controller), the POTLU Framework only implements Logic-View just for simplicity. Potlu Framework concept is Logic -> Views.
Potlu still in minimum documentation, the following several hint may help for first start this framework

  - app : Application folder, there has folder :
    - beans   : Spell this as model, simple structure model base on database or logic purpose.
    - config  : Web, Database configuration.
    - helper  : Helper functions.
    - logic   : Logic for each template module
    - views   : PHP file template located.

### Other
- If you familiar with idiorm, this framework using idiorm for ORM features (covering model feature in this case). For IdiORM Documentation :  [idiorm.readthedocs.io](idiorm.readthedocs.io)
- For documentation flight php (routing framework) : [flightphp.com/learn/](flightphp.com/learn/)

### Setup
Clone and Copy these files to _www_ directory, and install database example (inventory system).

### License
Feel free to use.