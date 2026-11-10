Feature: Create Bug Report
  As a reporter, I want to create and submit a bug report
  so that the system can examine the reported bug.

  Background:
    Given I am logged in as a reporter

  Scenario: Submit Bug Report Success (with screenshot)
    Given I am on the "/bug-reports/create" page
    And I fill in "Title" with "Tombol submit tidak berfungsi"
    And I fill in "Description" with "Tombol submit tidak merespon saat diklik pada halaman checkout"
    And I fill in "Module" with "Checkout & Pembayaran"
    And I fill in "Severity" with "Tinggi"
    And I attach a screenshot "error_screenshot.png"
    When I press "Submit Report"
    Then I should be redirected to "/bug-reports/create"
    And I should see the message "Laporan bug berhasil dikirim dan sedang dianalisis oleh sistem TwinBug."

  Scenario: Submit Bug Report Success (without screenshot)
    Given I am on the "/bug-reports/create" page
    And I fill in "Title" with "Halaman gagal dimuat"
    And I fill in "Description" with "Halaman dashboard menampilkan blank screen setelah login"
    And I fill in "Module" with "Dashboard"
    And I fill in "Severity" with "Sedang"
    When I press "Submit Report"
    Then I should be redirected to "/bug-reports/create"
    And I should see the message "Laporan bug berhasil dikirim dan sedang dianalisis oleh sistem TwinBug."

  Scenario: Failed Submit (missing required fields)
    Given I am on the "/bug-reports/create" page
    And I fill in "Title" with ""
    And I fill in "Description" with ""
    When I press "Submit Report"
    Then I should see the message "Judul bug wajib diisi."
    And I should see the message "Deskripsi bug wajib diisi."
    And I should remain on the "/bug-reports/create" page

  Scenario: Failed Submit (unsupported screenshot format)
    Given I am on the "/bug-reports/create" page
    And I fill in "Title" with "Error saat upload file"
    And I fill in "Description" with "Sistem menampilkan pesan error saat mengunggah dokumen"
    And I fill in "Module" with "Lainnya"
    And I fill in "Severity" with "Rendah"
    And I attach a screenshot "bukti_error.pdf"
    When I press "Submit Report"
    Then I should see the message "Screenshot harus berformat PNG, JPG, atau GIF."
    And I should remain on the "/bug-reports/create" page
