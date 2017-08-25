Feature: Yearly report of views for each profile per month
  Scenario: Execute command bin/console report:profiles:yearly 2016
    Given that there is historical data available
    When I execute the Yearly Views report
    Then I expect report for year 2016
      And I expect to see a monthly breakdown of the total views per profiles
      And I expect to have the profiles names listed in alphabetical order

  Scenario: Execute command bin/console report:profiles:yearly
    Given that there is historical data available
    When I execute the Yearly Views report
    Then I expect report for last year in database
      And I expect to see a monthly breakdown of the total views per profiles
      And I expect to have the profiles names listed in alphabetical order
      And I expect to see "n/a" when data is not available

  Scenario: Execute command bin/console report:profiles:yearly 2018
    Given that there is historical data available
    When I execute the Yearly Views report
    Then I expect to have the profiles names listed in alphabetical order
      And I expect to see "n/a" in all columns
