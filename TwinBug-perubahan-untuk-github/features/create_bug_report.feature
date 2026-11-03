Feature: Create Bug Report
  As a reporter, I want to create and submit a bug report so that the system can examine the reported bug.

  Scenario: Submit Bug Report Success (with screenshot)
    Given I am on the "/report/new" page
    And I fill in "Title" with "Tombol submit tidak berfungsi"
    And I fill in "Description" with "Tombol submit tidak merespon saat diklik pada halaman checkout"
    And I attach a screenshot "error_screenshot.png"
    When I press "Submit Report"
    Then I should be redirected to "/report/history"
    And I should see the message "Laporan bug berhasil dikirim!"

  Scenario: Submit Bug Report Success (without screenshot)
    Given I am on the "/report/new" page
    And I fill in "Title" with "Halaman gagal dimuat"
    And I fill in "Description" with "Halaman dashboard menampilkan blank screen setelah login"
    When I press "Submit Report"
    Then I should be redirected to "/report/history"
    And I should see the message "Laporan bug berhasil dikirim!"

  Scenario: Failed Submit (missing required fields)
    Given I am on the "/report/new" page
    And I fill in "Title" with ""
    And I fill in "Description" with ""
    When I press "Submit Report"
    Then I should see the message "Laporan gagal dikirim. Judul dan deskripsi harus diisi!"
    And I should remain on the "/report/new" page

  Scenario: Failed Submit (unsupported screenshot format)
    Given I am on the "/report/new" page
    And I fill in "Title" with "Error saat upload file"
    And I fill in "Description" with "Sistem menampilkan pesan error saat mengunggah dokumen"
    And I attach a screenshot "bukti_error.pdf"
    When I press "Submit Report"
    Then I should see the message "Laporan gagal dikirim. Format file tidak didukung. Gunakan JPG atau PNG."
    And I should remain on the "/report/new" page
