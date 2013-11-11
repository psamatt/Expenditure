Feature: Update account
  In order to change my user account
  As a logged in user
  I change my user details

  Scenario: Alter full name with anything
    
    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin/profile/edit"
    When I fill in "fullname" with "Matt Goodwin Testing"
    When I press "Save"
    Then I should see "Account updated"

  Scenario: Alter full name with nothing
    
    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin/profile/edit"
    When I fill in "fullname" with ""
    When I press "Save"
    Then I should see "Account updated"
    Then I should see "matt.goodwin491@gmail.com" in the "a.dropdown-toggle" element

  Scenario: Alter paid day with 0
    
    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin/profile/edit"
    When I fill in "inputPaidDay" with "0"
    When I press "Save"
    Then I should see "Warning"
    Then I should see "The selected paid day must be at least the 1st"

  Scenario: Alter paid day with 50
    
    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin/profile/edit"
    When I fill in "inputPaidDay" with "50"
    When I press "Save"
    Then I should see "Warning"
    Then I should see "You cannot have a paid day greater than 31"

  Scenario: Alter paid day with 10
    
    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin/profile/edit"
    When I fill in "inputPaidDay" with "10"
    When I press "Save"
    Then I should see "Account updated"

  @javascript
  Scenario: A new password with confirmation but only 5 characters long
    
    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin/profile/edit"
    When I follow "Change password"
    Then I should see "New Password"
    When I fill in "userPassword_password_first" with "abcde"
    When I fill in "userPassword_password_second" with "abcde"
    When I press "Update password"
    Then I should see "Warning"
    Then I should see "Password must be 6 characters"

  @javascript
  Scenario: A new password with over 5 characters long but not matching
    
    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin/profile/edit"
    When I follow "Change password"
    Then I should see "New Password"
    When I fill in "userPassword_password_first" with "abCd12eWz"
    When I fill in "userPassword_password_first" with "abCd12eXXXX"
    When I press "Update password"
    Then I should see "Warning"

  @javascript
  Scenario: A new password with confirmation that contains an upper case number and a number and long
    
    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin/profile/edit"
    When I follow "Change password"
    When I fill in "userPassword_password_first" with "abCd12eWz"
    When I fill in "userPassword_password_second" with "abCd12eWz"
    When I press "Update password"
    Then I should see "Password updated"