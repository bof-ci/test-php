Feature: Yearly report
    In order to view profile performance  
    As a Business Analyst
    I want to see the number of monthly views for each profile
   
    Scenario: Run yearly report for previous year
        Given that there is historical data available for previous year
        When I execute the Yearly Views report for previous year
        Then I expect to have the profiles names listed in alphabetical order

    Scenario: Run yearly report for previous year
        Given that there is historical data available for previous year
        When I execute the Yearly Views report
        Then I expect to see a monthly breakdown of the total views per profiles

    Scenario: Run yearly report for previous year
        Given that there is historical data available for previous year
        When I execute the Yearly Views report
        Then I expect to have the month names listed in chronological order

    Scenario: Run yearly report for previous year
        Given that there is historical data available for previous year
        When I execute the Yearly Views report
        Then I expect to see view numbers separated with a comma(,) for every thousands

    Scenario: Run yearly report for previous year
        Given that there is historical data available for previous year
        When I execute the Yearly Views report
        Then I expect to see the month name "Jan"
        And I expect to see the month name "Feb"
        And I expect to see the month name "Mar"
        And I expect to see the month name "Apr"
        And I expect to see the month name "May"
        And I expect to see the month name "Jun"
        And I expect to see the month name "Jul"
        And I expect to see the month name "Aug"
        And I expect to see the month name "Sep"
        And I expect to see the month name "Oct"
        And I expect to see the month name "Nov"
        And I expect to see the month name "Dec"

    Scenario: Run yearly report for next year 
        Given that there is no historical data available for next year
        When I execute the Yearly Views report
        Then I expect to see n/a

    Scenario: Run yearly report for 2017 on February 2017
        Given that there is historical data available for 2017
        When I execute the Yearly Views report
        Then I expect to see a monthly total views per profiles for January and February 
        And "n/a" for months where data is not available

    Scenario: Run yearly report for 2014
        Given that there is historical data available for 2014
        When I execute the Yearly Views report
        Then I expect to see year 2014

    