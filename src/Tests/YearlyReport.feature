GIVEN that there is historical data available
WHEN I execute the Yearly Views report
THEN I expect to see a monthly breakdown of the total views per profiles

GIVEN that there is historical data available
WHEN I view the Yearly Views report
THEN I expect to have the profiles names listed in alphabetical order

GIVEN that there is historical data available
WHEN I view the Yearly Views report
THEN I expect to see "n/a" when data is not available

GIVEN asking the user for year information
WHEN I execute the Yearly Views report
THEN I expect the value is numeric


GIVEN asking the user for year information
WHEN I execute the Yearly Views report
THEN I expect the value is 4 characters long

GIVEN asking the user for year information
WHEN I execute the Yearly Views report without entery
THEN I set current year

GIVEN asking the user for sort order information
WHEN I execute the Yearly Views report
THEN I expect the value is ASC or DESC

GIVEN asking the user for sort order information
WHEN I execute the Yearly Views report without entery
THEN I set default value ASC

GIVEN that there is NO historical data available
WHEN I execute the Yearly Views report
THEN I expect to see text "No Data found for year XXXX"