GIVEN that there is historical data available
WHEN I execute the Yearly Views report
THEN I expect to see a monthly breakdown of the total views per profiles

GIVEN that there is NO year defined
WHEN I execute the Yearly Views report
THEN I expect to see "Not enough arguments"

GIVEN that the year is not numeric
WHEN I execute the Yearly Views report
THEN I expect to see "Invalid Year argument"

GIVEN that there is historical data available
WHEN I view the Yearly Views report
THEN I expect to have the profiles names listed in alphabetical order

GIVEN that there is historical data available
WHEN I view the Yearly Views report
THEN I expect to see "n/a" when data is not available

GIVEN that there is NO historical data available
WHEN I view the Yearly Views report
THEN I expect to see "No data available"


