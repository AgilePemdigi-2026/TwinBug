Feature: View Report History
  As a user, I want to view the report history
  so that I can see the bug reports that have been submitted.

  Background:
    # [ASUMSI] tidak ada di SRS, ditambahkan karena fitur ini butuh login
    Given I am logged in as a reporter

  Scenario: View Report History Success
    Given I have submitted a bug report "Tombol submit tidak berfungsi"
    And I am on the "/bug-reports?scope=my" page
    When the page loads
    Then I should see a list of my submitted bug reports
    And each report should display "Judul & Ringkasan Bug", "Status", and "Tanggal Lapor"

  Scenario: View Report History (empty state)
    # [ASUMSI] login memakai akun Reporter yang belum punya laporan
    Given I am on the "/bug-reports" page
    When the page loads
    Then I should see the message "Sistem belum memiliki catatan laporan bug aktif."

  Scenario: View Report Detail
    Given I have submitted a bug report "Tombol submit tidak berfungsi"
    And I am on the "/bug-reports" page
    And I click on report "Tombol submit tidak berfungsi"
    When the report detail is opened
    Then I should see the report's title, description, status and priority
    And I should see the "Lampiran Screenshot" section

  Scenario: Filter Report History by Status
    Given I have submitted a bug report "Laporan terbuka dashboard" with status "Terbuka"
    And I have submitted a bug report "Laporan selesai checkout" with status "Diselesaikan"
    And I am on the "/bug-reports" page
    And I select filter "status" with "Diselesaikan"
    When I press "Terapkan Filter"
    Then I should see only reports with status "Diselesaikan"
