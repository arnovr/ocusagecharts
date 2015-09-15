Feature: Displaying current storage usage in percentage and GB
  As a user
  I want to see current storage usage in percentages
  So that i can keep track of storage used

  Background:
    Given an owncloud user named "ferdinand" was added to owncloud
    And an owncloud user named "dan" was added to owncloud
    And an owncloud user named "elton" was added to owncloud

  Scenario: Showing current storage information of ferdinand
    Given "15" GB owncloud storage was measured by owncloud user "ferdinand"
    And "80" GB owncloud storage was measured by owncloud user "elton"
    And "5" GB owncloud storage was measured by owncloud user "dan"
    And owncloud has "100" GB of free storage space left
    When calculating storage usage in percentages
    Then the percentage for owncloud user "ferdinand" should be "7.5"%
    And the percentage for owncloud user "elton" should be "40"%
    And the percentage for owncloud user "dan" should be "2.5"%
    And the remaining percentage should be "50"%

  Scenario: Showing current storage information of ferdinand
    Given "15" GB owncloud storage was measured by owncloud user "ferdinand"
    And "5" GB owncloud storage was measured by owncloud user "dan"
    And owncloud has "100" GB of free storage space left
    When calculating storage usage in percentages
    Then the percentage for owncloud user "ferdinand" should be "7.5"%
    And the remaining percentage should be "50"%