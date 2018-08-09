SOLUTION
========

Estimation
----------
Estimated: 5 hours

Spent: 9 hours


Solution
--------

**General**

I spent most of my time getting to know the framework and the possibilities given by it, as I previously never worked with
Symfony. 

I was considering the use of Entities with ORM, but I had some issues with installing it (as I don't have much experience
with Symfony), so then I decided the use of DataMappers (Repositories) instead.

Instead of hard-deleting I chose to soft-delete with logging the data more efficiently (updated & created columns) throughout
the whole "system". 

**Views Table**

My solution is including a bit of refactoring and rethinking on the structure. When I have first seen the `views` table,
I thought we would have one entry per each day, which would make sense, as it would collect together all the view for an
X day. Then when I started to dig deeper in the code, I realised that we actually have multiple rows for each day
with the same `profile_id`. 

Then I thought the `views` table might be actually a table having all the views one-by-one, which would have made sense
if we did not have `views` column under the `views` table (without user identifier, for example one user had X views that day).

So I came up with the following structure:
Each time someone is performing a view action, we insert a new row in `view` table. This will contain viewer related date,
and concrete date. For example browser information, cookies, etc.
With a cron job function, at every midnight we collect together all the views for that specific day, and add them as a single
row into our new `statistics_daily_views` table. This will allow use to use less memory when looking through the rows,
as they are already 'cached' for each day and profile. If we want the data fetching even more, we could consider having
`monthly` and `yearly` tables as well.

**SQL queries**

I wanted to present my knowledge both in writing SQL queries and PHP, so I chose to write a bit more complex queries and
less code.

**Statistics table(s)**
In real life, cron jobs must be implemented, by recommendation on a daily basis.

**Recommendations regarding features**

- Implementing `yearly` and `monthly` tables as well (both in the database and in the console).
- More abroad filters, for example periods or only one profile.
- Forecasting based on historical data.
- Adding the possibility to insert data to views based on console.
- Grow and decrease graphs based on data.
- Allowing the user to set the sort order.