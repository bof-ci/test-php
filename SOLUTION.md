SOLUTION
========

Estimation
----------
Estimated: 6 hours

Spent: 5 hours


Solution
--------
This solution solves the acceptance criteria set by the client and can be implemented in a timely manner.

It can be further upgraded with additional options and arguments such as sorting order and profile selection.
With this we would get a more useful product for the client which would be able to filter the data without the
need of an external tool.

Additionally this could be developed as a web service that returns data via REST API either in a web page or JSON response.
Responding with a spreadsheet or CSV file would also be a viable solution for a service, though.

I have made several database modifications:
* added auto increment and set ```profile_id``` as primary key in the ```profiles``` table
* added primary key for the ```views``` table (auto increment as well)
* added foreign key constraint on the ```profile_id``` in the ```views``` table

With primary and foreign keys we can enforce constraints and more easily manage the relationships between the tables.
