Test Case 1:
  running the command without the year argument throws RuntimeException with set message.

Test Case 2:
  providing year as a non-numerical value throws InvalidArgumentException with set message.

Test Case 3:
  correct data is output for the specified year for each of the profiles (testing with mock).

Test Case 4:
  if there is no record in the database, display number of views as "n/a" (testing with mock).

Test Case 5:
  0 is a valid number of views for specified month - get's displayed properly instead of "n/a" (testing with mock).

Test Case 6:
  output data is sorted by profile name in ascending order.

Test Case 7:
  sorting function works on profiles with the same profile name (testing with mock) and keeps data correctly coupled.
