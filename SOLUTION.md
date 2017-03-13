SOLUTION
========

Estimation
----------
Estimated: 2 hours

Spent: 1.5 hours


Solution
--------

Please find below 5 suggested CoA's:

```gherkin

GIVEN that there is historical data available
WHEN I execute the Yearly Views report
THEN I expect to display an * in front of the name of most viewed profile that year

GIVEN that there is historical data available
WHEN I execute the Yearly Views report
THEN I expect an aggregated column with the sum of views for that profile that year

GIVEN that there is historical data available
WHEN I execute the Yearly Views report
THEN I expect an aggregated row with the sum of all the views of that month

GIVEN that there is historical data available
WHEN I execute the Yearly Views report
THEN I expect every month views to have after the value of views the variation respect the last month between parenthesis

GIVEN that there is historical data available
WHEN I execute the Yearly Views report
THEN I expect to display an ! if the variation drops more than 20%
```

I set up a VM on Vagrant and started to set up Behat but the setup process it was taking too long so I decide to switch 
to non TDD solution. 