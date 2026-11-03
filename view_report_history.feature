Feature: View Report History
  As a user, I want to view the report history so that I can see the bug reports that have been submitted.

  Scenario: View Report History Success
    Given I am on the "/report/history" page
    When the page loads
    Then I should see a list of my submitted bug reports
    And each report should display "Title", "Status", and "Submission Date"

  Scenario: View Report History (empty state)
    Given I am on the "/report/history" page
    When the page loads
    Then I should see the message "Anda belum memiliki laporan bug."

  Scenario: View Report Detail
    Given I am on the "/report/history" page
    And I click on report "Tombol submit tidak berfungsi"
    When the report detail is opened
    Then I should see the "Title", "Description", "Status", "Priority", and "Attached Screenshot" of the report

  Scenario: Filter Report History by Status
    Given I am on the "/report/history" page
    And I select filter "Status" with "Duplicate"
    When I press "Apply Filter"
    Then I should see only reports with status "Duplicate"
