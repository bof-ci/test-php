SOLUTION
========

Estimation
----------
Estimated: 7 hours

Spent: 10 hours


Solution
--------

1. There should be an output so if there is no data in the DB a user should see some notice
2. For some months there are might be no data available but these months still have to be in a table with 'n/a' value
3. For some profiles there might be no views at all but these profiles should present in a table
4. There might be a data for more then one year so output should display these data separately
5. Profiles and months should be in a right order

As a proposal for making a better product I would suggest to implement a way for using output not only as a table view
but also make it ready for different type of usage i.e. xml, json. So the output component need to be encapsulated in 
an adapter to make it possible to replace with something which uses the same interface. Also command line will take another
parameter in order to override the default format (6 hours)

Because of the reporting nature of this product then it might be a good idea to have a kind of call back function which 
will send the result by email or as a request to a kind of API endpoint as a final step (8 hours) 

There might be a huge amount of data which might take a significant amount of resources and time to proceed since we 
request all the data from tables. It would be useful to break down this request to several one for each year, also 
this kind yearly data looks as a good candidate for table sharding. If we go further we could use queue tasks or simple
timed cron jobs in order to precalculate this kind of requests and keep it cached. (6-20 hours depend on what needs to be implemented)