Feature: Displaying current storage usage
  As a user
  I want to see current storage usage of all the users
  So that i can keep track of storage used

  Background:
    Given an owncloud user named "ferdinand" was added to owncloud
    And an owncloud user named "dan" was added to owncloud
    And an owncloud user named "elton" was added to owncloud

  Scenario: Showing current storage information of ferdinand
    Given "15" GB owncloud storage was stored by owncloud user "ferdinand"
    And "80" GB owncloud storage was stored by owncloud user "elton"
    And "5" GB owncloud storage was stored by owncloud user "dan"
    And owncloud has "100" GB of free storage left
    Then the chart slice "ferdinand" should be "7.5%" with "15" GB used storage
    And the chart slice "used by others" should be "42.5%" with "85" GB used storage
    And the chart slice "free" should be "50%" with "100" GB used storage