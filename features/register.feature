Feature: Register
  As a user, I want to create an account in the TwinBug system
  so that I can use the system.

  Scenario: Register Success
    Given an application named "TwinBug Demo" exists
    And I am on the "/register" page
    And I select the "reporter" role
    And I fill in "Name" with "kelompok2"
    And I fill in "Email" with "kelompok2@gmail.com"
    And I fill in "Application" with "TwinBug Demo"
    And I fill in "Password" with "kelompok123"
    And I fill in "Confirm Password" with "kelompok123"
    And I accept the terms and conditions
    When I press "Register"
    Then I should be redirected to "/otp/verify"
    And I should see the message "Kode OTP telah dikirim ke email Anda."

  Scenario: Register OTP verification completes account creation
    Given an application named "TwinBug Demo" exists
    And I am on the "/register" page
    And I select the "reporter" role
    And I fill in "Name" with "kelompok2"
    And I fill in "Email" with "kelompok2@gmail.com"
    And I fill in "Application" with "TwinBug Demo"
    And I fill in "Password" with "kelompok123"
    And I fill in "Confirm Password" with "kelompok123"
    And I accept the terms and conditions
    When I press "Register"
    And I submit the OTP code from the email
    Then I should be redirected to "/otp/success"
    And I should see the message "Akun berhasil dibuat dan diverifikasi. Selamat datang!"

  Scenario: Failed Register (missing fields)
    Given I am on the "/register" page
    And I select the "reporter" role
    And I fill in "Name" with ""
    And I fill in "Email" with ""
    And I fill in "Password" with ""
    And I fill in "Confirm Password" with ""
    When I press "Register"
    Then I should see the message "nama wajib diisi."
    And I should see the message "email wajib diisi."
    And I should see the message "kata sandi wajib diisi."
    And I should remain on the "/register" page

  Scenario: Failed Register (email already registered)
    Given an application named "TwinBug Demo" exists
    And a registered account "kelompok2@gmail.com" with password "kelompok123" exists
    And I am on the "/register" page
    And I select the "reporter" role
    And I fill in "Name" with "kelompok2"
    And I fill in "Email" with "kelompok2@gmail.com"
    And I fill in "Application" with "TwinBug Demo"
    And I fill in "Password" with "kelompok123"
    And I fill in "Confirm Password" with "kelompok123"
    And I accept the terms and conditions
    When I press "Register"
    Then I should see the message "email sudah digunakan."
    And I should remain on the "/register" page

  Scenario: Failed Register (password confirmation mismatch)
    Given an application named "TwinBug Demo" exists
    And I am on the "/register" page
    And I select the "reporter" role
    And I fill in "Name" with "kelompok2"
    And I fill in "Email" with "2kelompok@gmail.com"
    And I fill in "Application" with "TwinBug Demo"
    And I fill in "Password" with "kelompok123"
    And I fill in "Confirm Password" with "kelompok456"
    And I accept the terms and conditions
    When I press "Register"
    Then I should see the message "Konfirmasi kata sandi tidak cocok."
    And I should remain on the "/register" page
