Feature: Displaying storage usage
  As a user
  I want to see storage information of all the users
  So that i can keep track of storage used

  Scenario: Showing storage information of all users
    Given The user "ferdinand" uses "15" GB of storage usage
    Given The user "dan" uses "5" GB of storage usage
    Given The user "elton" uses "80" GB of storage usage
    When the user requests storage information for all users
    Then The storage information should supply "100%" is "100" GB of storage usage
    Then The storage information should supply "15%" is "15" GB of storage usage
    Then The storage information should supply "5%" is "5" GB of storage usage
    Then The storage information should supply "80%" is "80" GB of storage usage