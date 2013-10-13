Feature: Default Payments
  In order to manage default payment
  As a logged in user
  I must be on the correct page

  Scenario: Add a default saving
    
    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin/month/default"
    When I fill in "inputTitle" with "Foo Default Saving"
    When I fill in "inputPrice" with "100"
    When I press "Add"
    Then I should see "Default Payment Saved"
    Then I should see "Foo Default Saving"

  Scenario: Add a default saving
    
    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin/month/default"
    When I fill in "inputTitle" with "Default Foo Saving"
    When I fill in "inputPrice" with "100"
    When I press "Add"
    Then I should see "Default Payment Saved"
    Then I should see "Default Foo Saving"

  @javascript
  Scenario: Edit an existing default saving
  
    Given the following default payments are expenditure default payments
      | ID | Title       | User | Price   |
      | 1  | Default Foo | 1    | 100     |
    
    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin/month/default"
    Then I should see "Default Foo"
    When I follow "Edit"
    Then the "inputTitle" field should contain "Default Foo"
    When I fill in "inputTitle" with "Default Foo Changed"
    When I press "Update"
    Then I should see "Default Payment Saved"
    Then I should see "Default Foo Changed"

  Scenario: Delete an existing default saving
  
    Given the following default payments are expenditure default payments
      | ID | Title       | User | Price   |
      | 1  | Default Foo | 1    | 100     |
    
    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin/month/default"
    Then I should see "Default Foo"
    When I follow "default_1_delete"
    Then I should see "Default Payment Deleted"
    Then I should not see "Default Foo"