Feature: Stream
  In order to streaming
  As a user
  I need to be able to start streaming
  Scenario: Start streaming
    Given There are user:
      | email          | password | name |
      | test@test.com  | 123456   | test |
    And I start streaming
    Then Streaming session should be started and recorded in database
