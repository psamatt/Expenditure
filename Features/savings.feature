Feature: User Savings
  In order to manage my user savings
  As a logged in user
  I amend my savings

  Scenario: View initial saving with paid day user
    
    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin/savings"
    Then I should not see a "table#savingsTbl" element
    Then I should see a "form#expenditureForm" element

  Scenario: Add Saving
    
    Given I am logged in as "mgoodwin@gmail.com" with "Admin123"
    When I go to "/admin/savings"
    When I fill in "inputTitle" with "Behat Saving Test"
    When I fill in "targetDate" with "01-01-2020"
    When I fill in "targetAmount" with "1000"
    When I fill in "amountSaved" with "20"
    When I press "Save"
    Then I should see "Saving Saved"
    Then I should see 1 "tr.saving_manage" element

  @javascript
  Scenario: Edit an existing saving
    
    Given the following savings are expenditure savings
      | ID | Title      | User | Target Date   | Target Amount | Saved Amount |
      | 1  | Saving Foo | 1    | 7th May 2020  | 10000         | 100          |
    
    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin/savings"
    Then I should see 1 "table#savingsTbl" element
    Then I should see "Saving Foo"
    When I follow css "a[data-title=\"Saving Foo\"]"
    Then the "inputTitle" field should contain "Saving Foo"
    When I fill in "inputTitle" with "Saving Foo Changed"
    When I press "Save"
    Then I should see "Saving saved"
    Then I should see "Saving Foo Changed"

  @javascript @incomplete
  Scenario: Delete an existing saving
  
    Given the following savings are expenditure savings
      | ID | Title      | User | Target Date   | Target Amount | Saved Amount |
      | 1  | Saving Foo | 1    | 7th May 2020  | 10000         | 100          |
    
    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin/savings"
    Then I should see 1 "table#savingsTbl" element
    Then I should see "Saving Foo"

  @javascript @incomplete
  Scenario: Add money to an existing saving
    
    Given the following savings are expenditure savings
      | ID | Title      | User | Target Date   | Target Amount | Saved Amount |
      | 1  | Saving Foo | 1    | 7th May 2020  | 10000         | 100          |
    
    Given I am logged in as "mgoodwin@gmail.com" with "Admin123"
    When I go to "/admin/savings"