Given I type the command: bin/console report:profiles:yearly
When I enter an invalid date
Then I should get an error message of Instance LogicException with following message: 'Incorrect year'

Given I type the command: bin/console report:profiles:yearly
When I enter a valid date
Then I should see an output

Given I have historical data
When I execute the Yearly Views report
Then I should see an output

Given The user 'Anna Wintour' Join the company in September 2014
When I execute the Yearly Views report
And Set the Year 2014
Then The value for this user should be 'n/a' before September
And The User should have values after September

Given The user 'Karl Lagerfeld' Join the company in January 2018
When I execute the Yearly Views report
And Set the Year 2017
Then The user should not appear in the output

Given The user 'Anna Wintour' has 123,456 views in January 2018
When I execute the Yearly Views report
And Set the Year 2018
Then The value number appearing in the Column January for this user should be 123,456
And formatted correctly xxx,xxx