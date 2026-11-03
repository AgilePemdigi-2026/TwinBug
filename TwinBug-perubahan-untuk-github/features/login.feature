Feature: Login
  As a user, I want to log in to the TwinBug system so that I can access the system.

  Scenario: Login Success
    Given I am on the "/login" page
    And I fill in "Email" with "kelompok2@gmail.com"
    And I fill in "Password" with "kelompok123"
    When I press "Login"
    Then I should be redirected to "/dashboard"
    And I should see the message "Login Berhasil!"

  Scenario: Failed Login (missing fields)
    Given I am on the "/login" page
    And I fill in "Email" with ""
    And I fill in "Password" with ""
    When I press "Login"
    Then I should see the message "Login gagal. Semua field harus diisi!"
    And I should remain on the "/login" page

  Scenario: Failed Login (email not registered)
    Given I am on the "/login" page
    And I fill in "Email" with "unknownuser@gmail.com"
    And I fill in "Password" with "team1122"
    When I press "Login"
    Then I should see the message "Login gagal. Email tidak terdaftar."
    And I should remain on the "/login" page

  Scenario: Failed Login (wrong password)
    Given I am on the "/login" page
    And I fill in "Email" with "kelompok2@gmail.com"
    And I fill in "Password" with "kelompok112"
    When I press "Login"
    Then I should see the message "Login gagal. Email atau password Anda salah."
    And I should remain on the "/login" page
