Feature: View Detection Results
  As a user, I want to view the detection results so that I can see the historical bug reports that are similar to my report.

  Scenario: View Detection Results Success
    Given I am on the "/report/detection-result/101" page
    When the page loads
    Then I should see a list of "Similar Historical Bug Reports"
    And each result should display "Title", "Similarity Score", and "Status"

  Scenario: View Detection Results (no similar reports found)
    Given I am on the "/report/detection-result/102" page
    When the page loads
    Then I should see the message "Tidak ditemukan laporan bug serupa."

  Scenario: View Detailed Comparison of Similar Report
    Given I am on the "/report/detection-result/101" page
    And I click on a similar report "Tombol checkout tidak merespon"
    When the comparison view is opened
    Then I should see the text similarity score and image similarity score of the comparison
    And I should see the screenshots of both reports side by side

  Scenario: Detection Results Still Processing
    Given I am on the "/report/detection-result/103" page
    When the page loads
    Then I should see the message "Analisis kemiripan sedang diproses. Silakan cek kembali beberapa saat lagi."
