Feature: Report
  So that I can have visibility on profile performance
  As a Business Analyst
  I want to report the monthly views for each profile

  Scenario: Getting a monthly breakdown of the total views per profiles
    Given that there is historical data available
    When I execute the Yearly Views report
    Then I expect to see a monthly breakdown of the total views per profiles

  Scenario: Getting the profiles names listed in alphabetical order
    Given that there is historical data available
    When I view the Yearly Views report
    Then I expect to have the profiles names listed in alphabetical order

  Scenario: Getting 'n/a' when data is not available
    Given that there is historical data available
    When I view the Yearly Views report
    And I expect to see 'n/a' when data is not available