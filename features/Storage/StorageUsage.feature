Feature: Displaying current storage usage
  As a user
  I want to see current storage usage of all the users
  So that i can keep track of storage used

  Scenario: Showing current storage information of all users
    Given user "ferdinand" uses "15" GB of storage
    And user "dan" uses "5" GB of storage
    And user "elton" uses "80" GB of storage
    When a user requests storage information for all users
    Then The chart shows "15%" which represents "15" GB of storage
    And The chart shows "5%" which represents "5" GB of storage
    And The chart shows "80%" which represents "80" GB of storage
    And the overall chart usage should be "100%" which represents "100" GB