SOLUTION
========

Estimation
----------
Estimated: 5 hours

Spent: 4 hours


Solution
--------

Views Table


My solution is including a bit of refactoring and rethinking on the structure. When I have first seen the `views` table,
I thought we would have one entry per each day, which would make sense, as it would collect together all the view for an
X day. Then when I started to dig deeper in the code, I realised that you will actually have multiple rows for each day
with the same `profile_id`. 

Then I thought the `views` table might be actually a table having all the views one-by-one, which would have made sense
if we did not have `views` column under the `views` table. 

So I came up with the following structure:
Each time someone is performing a view action, we insert a new row in `view` table. This will contain viewer related date,
and concrete date. For example browser information, cookies, etc.
With a cron job function, at every midnight we collect together all the views for that specific day, and add them as a single
row into our new `statistics_daily_views` table. This will allow use to use less memory when looking through the rows,
as they are already 'cached' for each day and profile. If we want the data fetching even more, we could consider having
`monthly` and `yearly` tables as well.
