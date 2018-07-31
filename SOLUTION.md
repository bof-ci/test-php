SOLUTION
========

Estimation
----------
Estimated: 2 hours

Spent: 3 hours


Solution
--------
- Execute the command to run the Reports
- You'll be ask to enter a date
- a validation has been set to determine if it's a valid year
    - if not you'll have to enter a valid one to continue.
- Once a valid date entered, the SQL query will be triggered using the query builder
- The SQL query take care of the first 2 points (calculating the sum of views and ordering alphabetically) and the number formatting
- We fill the missing views by "n/a" on the PHP side.
- to quickly verify the results, I've exported the views table in a csv and double checked if the amount of views for a person was matching with the output

Describe how you can build a better "Product" for this coding task in SOLUTION.md and include your estimates
-
I would add more interaction by choosing different filters, like select a person,
different views instead of just yearly (quarterly ...), and total.

I would estimate 2 extra hours, choosing a different view might require the most development as the filters are just 
other conditions to add to the WHERE.

