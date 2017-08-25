SOLUTION
========

Estimation
----------
Estimated: 4 hours

Spent: 7 hours


Solution
--------
- I modified setup.sql that both tables 
have auto increment primary key id. I also added foreign key profile_id and index in table views
- I installed doctrine/orm with composer, because every project that is not time-critical 
is good to have some ORM. I spent 3 hours more than expected, mostly because of ORM configuration
and cucumber / Gherkin. I had problems with some native functions 
so I installed also beberlei/DoctrineExtensions. ORM configuration is now on dev mode. 
If we want to switch to production mode, we have to create proxies (console orm:generate-proxies)
- For better product I would like to recommend exporting report in excel or pdf file 
and maybe generate chart
- For better technology I would like to recommend Laravel especially because is like Rails Convention 
over Configuration and it is also composed from Symfony components.