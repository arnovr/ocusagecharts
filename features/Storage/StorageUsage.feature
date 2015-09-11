Feature: Displaying current storage usage
  As a user
  I want to see current storage usage of all the users
  So that i can keep track of storage used

  Scenario: Showing current storage information of all users
    Given user "ferdinand" uses "15" GB of storage
    And user "dan" uses "5" GB of storage
    And user "elton" uses "80" GB of storage
    And there is 100 GB of free storage
    When i add "ferdinand" to the chart
    Then the chart slice "ferdinand" should be "7.5%" with "15" GB used storage
    And the chart slice "used by others" should be "42.5%" with "85" GB used storage
    And the chart slice "free" should be "50%" with "100" GB used storage