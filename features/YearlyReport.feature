Feature: Yearly report
    In order to see profile performance  
    As a Business Analyst
    I want to see the number of monthly views for each profile

    Scenario: Run yearly report for 2015
        Given that there is historical data available for 2015
        When I execute the Yearly Views report for 2015
        Then I expect to see a monthly breakdown of the total views per profiles:
        """
        +----------------+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+
        | Profile   2015 | Jan | Feb | Mar | Apr | May | Jun | Jul | Aug | Sep | Oct | Nov | Dec |
        +----------------+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+
        | Anna Wintour   | 10  | 20  | 30  | 40  | 50  | 60  | 70  | 80  | 90  | 100 | 110 | 120 |
        | Karl Lagerfeld | 10  | 20  | 30  | 40  | 50  | 60  | 70  | 80  | 90  | 100 | 110 | 120 |
        +----------------+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+
        """
    Scenario: Run yearly report for 2015
        Given that there is historical data available for 2015
        When I execute the Yearly Views report for 2015
        Then I expect to have the profiles names listed in alphabetical order:
        """
        +----------------+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+
        | Profile   2015 | Jan | Feb | Mar | Apr | May | Jun | Jul | Aug | Sep | Oct | Nov | Dec |
        +----------------+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+
        | Anna Wintour   | 10  | 20  | 30  | 40  | 50  | 60  | 70  | 80  | 90  | 100 | 110 | 120 |
        | Karl Lagerfeld | 10  | 20  | 30  | 40  | 50  | 60  | 70  | 80  | 90  | 100 | 110 | 120 |
        +----------------+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+
        """

    Scenario: Run yearly report for 2015
        Given that there is historical data available for 2015
        When I execute the Yearly Views report for 2015
        Then I expect to have the month names listed in chronological order:
        """
        +----------------+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+
        | Profile   2015 | Jan | Feb | Mar | Apr | May | Jun | Jul | Aug | Sep | Oct | Nov | Dec |
        +----------------+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+
        | Anna Wintour   | 10  | 20  | 30  | 40  | 50  | 60  | 70  | 80  | 90  | 100 | 110 | 120 |
        | Karl Lagerfeld | 10  | 20  | 30  | 40  | 50  | 60  | 70  | 80  | 90  | 100 | 110 | 120 |
        +----------------+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+
        """

    Scenario: Run yearly report for 2015
        Given that there is historical data available for 2015
        When I execute the Yearly Views report for 2015
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
        When I execute the Yearly Views report for next year
        Then I expect to see n/a

    Scenario: Run yearly report
        Given that there is no historical data available for current year
        When I execute the Yearly Views report
        Then I expect to see n/a


    Scenario: Run yearly report for 2016 in February 2016
        Given that there is historical data available for 2016
        When I execute the Yearly Views report for 2016
        Then I expect to see a monthly total views per profiles for January and February 2016:
        """
        +----------------+-----------+-----------+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+
        | Profile   2016 | Jan       | Feb       | Mar | Apr | May | Jun | Jul | Aug | Sep | Oct | Nov | Dec |
        +----------------+-----------+-----------+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+
        | Anna Wintour   | 1,000,000 | 2,000,000 | n/a | n/a | n/a | n/a | n/a | n/a | n/a | n/a | n/a | n/a |
        | Karl Lagerfeld | 1,000,000 | 2,000,000 | n/a | n/a | n/a | n/a | n/a | n/a | n/a | n/a | n/a | n/a |
        +----------------+-----------+-----------+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+
        """
        And n/a for months where data is not available:
        """
        +----------------+-----------+-----------+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+
        | Profile   2016 | Jan       | Feb       | Mar | Apr | May | Jun | Jul | Aug | Sep | Oct | Nov | Dec |
        +----------------+-----------+-----------+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+
        | Anna Wintour   | 1,000,000 | 2,000,000 | n/a | n/a | n/a | n/a | n/a | n/a | n/a | n/a | n/a | n/a |
        | Karl Lagerfeld | 1,000,000 | 2,000,000 | n/a | n/a | n/a | n/a | n/a | n/a | n/a | n/a | n/a | n/a |
        +----------------+-----------+-----------+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+
        """
        And views will have a comma separating every group of thousands:
        """
        +----------------+-----------+-----------+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+
        | Profile   2016 | Jan       | Feb       | Mar | Apr | May | Jun | Jul | Aug | Sep | Oct | Nov | Dec |
        +----------------+-----------+-----------+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+
        | Anna Wintour   | 1,000,000 | 2,000,000 | n/a | n/a | n/a | n/a | n/a | n/a | n/a | n/a | n/a | n/a |
        | Karl Lagerfeld | 1,000,000 | 2,000,000 | n/a | n/a | n/a | n/a | n/a | n/a | n/a | n/a | n/a | n/a |
        +----------------+-----------+-----------+-----+-----+-----+-----+-----+-----+-----+-----+-----+-----+
        """

    Scenario: Run yearly report for 2015
        Given that there is historical data available for 2015
        When I execute the Yearly Views report for 2015
        Then I expect to see year 2015