Feature: Storage Usage last month
  In order to show the storage usage for the last month
  As a user
  I need to be able to retrieve storage usage information about the last month

  Scenario:
    Given I am a user
    And I am logged in
    And I have a chart
    When i try to retrieve the storage usage for the last month
    Then I see my personal json data for storage usage for the last month

  Scenario:
    Given I am a user
    And I am logged in
    And I am administrator
    And I have a chart
    When i try to retrieve the storage usage for the last month
    Then I see json data for all users with the storage usage for the last month

  Scenario:
    Given I am a user
    And i am not logged in
    And I have a chart
    When i try to retrieve the storage usage for the last month
    Then I get an empty response