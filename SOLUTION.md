SOLUTION
========

Estimation
----------
Estimated: 7 hours

Spent: 6 hours

It was my first time working with Symfony framework so I predicted to spend a little bit more hours so that I get to know the system, but there was just a couple of components so I didn't lose too much time.
I was also a little bit careful so I spent a little bit more time.

Solution
--------
I extended the application that client can choose year of the report and sorting order by name.

Project also can be upgraded so client can search only for specific client or it could get data for multiple years.
Data also can be exported to other formats, like json or excel.

On database we can define foreign keys to restrict false inputs and we can also set indexes on search columns for better performance.
We need to set profile_name column to "unique" or add an autoincrement column.