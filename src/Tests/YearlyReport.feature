GIVEN the fact that there is historic data for each days of the year for all the profiles available
  WHEN I execute the `report:yearly Profiles` command with the right year
  THEN I expect to see data for all the months under all the profiles

GIVEN the fact that there is no historic data available
  WHEN I execute the `report:yearly Profiles` command with the right year
  THEN I expect to see all not deleted profiles indicating N/O for each month

GIVEN the fact that I insert five new (not deleted) views for one of the profiles
  WHEN I run the command `test:data:reset`, and I execute the `report:yearly Profiles` command
  THEN I see the increase in the numbers compared to before

GIVEN the fact that I call the `report:yearly Profiles` command
  WHEN I type for the question "Which year?" an invalid year (for example string)
  THEN I get back the "You must type a number." error message

GIVEN the fact that I truncate the `daily_statistics_views` table
  WHEN I run the command `report:yearly Profiles with the right year
  THEN I expect to see all the profiles with N/O values