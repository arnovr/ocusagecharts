Feature: Average storage usage per month
  In order to show the average storage usage per month
  As a user
  I need to be able to retrieve data which includes data about the average storage

  Scenario:
    Given I am a user
    And I have a chart
    And I am logged in
    When i try to retrieve Average storage usage per month
    Then I see json data with the average storage usage per month for user

  Scenario:
    Given I am a user
    And I have a chart
    And I am logged in
    And I am administrator
    When i try to retrieve Average storage usage per month
    Then I see json data with the average storage usage per month for administrator

  Scenario:
    Given I am a user
    And I have a chart
    And i am not logged in
    When i try to retrieve Average storage usage per month
    Then I get an empty response