Feature: Register
  As a user, I want to create an account in the TwinBug system so that I can use the system.

  Scenario: Register Success
    Given I am on the "/register" page
    And I fill in "Name" with "kelompok2"
    And I fill in "Email" with "kelompok2@gmail.com"
    And I fill in "Password" with "kelompok123"
    And I fill in "Confirm Password" with "kelompok123"
    When I press "Register"
    Then I should be redirected to "/login"
    And I should see the message "Registrasi berhasil! Silakan login."

  Scenario: Failed Register (missing fields)
    Given I am on the "/register" page
    And I fill in "Name" with ""
    And I fill in "Email" with ""
    And I fill in "Password" with ""
    When I press "Register"
    Then I should see the message "Registrasi gagal. Semua field harus diisi!"
    And I should remain on the "/register" page

  Scenario: Failed Register (email already registered)
    Given I am on the "/register" page
    And I fill in "Email" with "kelompok2@gmail.com"
    And I fill in "Password" with "kelompok123"
    And I fill in "Confirm Password" with "kelompok123"
    When I press "Register"
    Then I should see the message "Registrasi gagal. Email sudah terdaftar."
    And I should remain on the "/register" page

  Scenario: Failed Register (password confirmation mismatch)
    Given I am on the "/register" page
    And I fill in "Email" with "2kelompok@gmail.com"
    And I fill in "Password" with "kelompok123"
    And I fill in "Confirm Password" with "kelompok456"
    When I press "Register"
    Then I should see the message "Registrasi gagal. Konfirmasi password tidak cocok."
    And I should remain on the "/register" page
