SOLUTION
========

Estimation
----------
Estimated: 4 hours
- Test cases 1h
- Implement command 3h

Spent: 4.25 hours
- Test cases 0.75h
- Implement command 3.5h

Solution
--------
- Test cases for task 4 can be found in `src/Tests/YearlYReport.feature`.
- `app/config.yml` has been changed to connect to a Mysql Docker container called 'database'.
- Behat tests in `features/` folder.


For a better "Product"
----------------------
- Create a responsive web page to display the data. This will be accessible from wide variety of devices.
    + Year option can be made in to a drop down which will be more user friendly.
    + Show data as a chart or data visualisation.
- Create a REST API endpoint so this data can be fed to a web or mobile applications.
- Add option to filter data by year, month and date. This will enable users to see
more granular data.
