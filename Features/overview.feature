Feature: Overview
  In order to manage the current month
  As a logged in user
  I must have a current month to manage

  Scenario: Default view with no default payments
    
    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin"
    Then I should see "has not yet been created, therefore an overview is not able to be calculated"
    When I fill in "salary" with "2000"
    When I press "Create now"
    Then I should see 0 "table tbody tr" elements
    Then I should see "created"

  Scenario: Create new month with 3 default payments
    
    Given the following default payments are expenditure default payments
      | Title       | User  | Price   |
      | Default Foo | 1     | 100     |
      | Default Bar | 1     | 200     |
      | Default Foz | 1     | 175     |
    
    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin"
    When I fill in "salary" with "2000"
    When I press "Create now"
    Then I should see "created"
    Then I should see 3 "table tbody tr" elements
    Then the "span#totalExpenditure" element should contain "475"
    Then the "span#amountToPay" element should contain "475"
    Then the "span#amountLeft" element should contain "1,525.00"

  @javascript @abc
  Scenario: Month with using edit and paid actions

    Given the following month headers are expenditure month headers
      | ID | User | Calendar Date | Month Income |
      | 1  | 1    | NOW           | 1000         |
    
    Given the following expenditures are expenditure
      | ID | Header       | Title | Price | Amount Paid |
      | 1  | NOW          | Foo   | 100   | 50          |
      | 2  | NOW          | Bar   | 200   | 100         |
      | 3  | NOW          | Foo 1 | 175   | 0           |
    
    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin"
    Then I should see 3 "table tbody tr" elements
    Then the "span#totalExpenditure" element should contain "475"
    Then the "span#amountToPay" element should contain "325"
    Then the "span#amountLeft" element should contain "525.00"
    When I follow css "table tr[data-id=\"2\"] a.edit--record"
    Then the "inputTitle" field should contain "Bar"
    Then the "inputPrice" field should contain "200"
    When I fill in "inputPrice" with "300"
    When I press "Save"
    Then I should see "Expenditure Saved"
    Then the "span#totalExpenditure" element should contain "575"
    Then the "amountLeft" element should contain "425.00"
    When I follow css "table tr[data-id=\"2\"] a.paid--record"
    Then I should see "Expenditure set as paid"
    Then the "span#amountToPay" element should contain "225"
    Then I should see 1 "table tbody tr.expenditure-paid" elements
    When I fill in "inputTitle" with "Fruit"
    When I fill in "inputPrice" with "50"
    When I press "Save"
    Then I should see 4 "table tbody tr" elements

  @javascript
  Scenario: Month with using partial paid action

    Given the following month headers are expenditure month headers
      | ID | User | Calendar Date | Month Income |
      | 1  | 1    | NOW           | 1000         |
    
    Given the following expenditures are expenditure
      | ID | Header       | Title | Price | Amount Paid |
      | 1  | NOW          | Foo   | 100   | 50          |
      | 2  | NOW          | Bar   | 200   | 100         |

    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin"
    Then I should see 2 "table tbody tr" elements 
    When I follow css "tr[data-id=\"2\"] a[data-target=\"#expendturePaidModal\"]"
    Then I should see the "expendturePaidModal" modal "Add Payment"
    When I fill in "expenditurePartialPaidMoney_amount" with "100"
    When I press "Add payment"
    Then I should see "Expenditure set as paid"

  @javascript
  Scenario: Month with using partial paid action

    Given the following month headers are expenditure month headers
      | ID | User | Calendar Date | Month Income |
      | 1  | 1    | NOW           | 1000         |
    
    Given the following expenditures are expenditure
      | ID | Header       | Title | Price | Amount Paid |
      | 1  | NOW          | Foo   | 100   | 50          |
      | 2  | NOW          | Bar   | 200   | 100         |

    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin"
    Then I should see 2 "table tbody tr" elements 
    When I follow css "tr[data-id=\"2\"] a[data-target=\"#expendturePaidModal\"]"
    Then I should see the "expendturePaidModal" modal "Add Payment"
    When I fill in "expenditurePartialPaidMoney_amount" with "100"
    When I press "Add payment"
    Then I should see "Expenditure set as paid"

  @javascript
  Scenario: Month with using delete action

    Given the following month headers are expenditure month headers
      | ID | User | Calendar Date | Month Income |
      | 1  | 1    | NOW           | 1000         |
    
    Given the following expenditures are expenditure
      | ID | Header       | Title | Price | Amount Paid |
      | 1  | NOW          | Bar   | 200   | 100         |

    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin"
    Then I should see 1 "table tbody tr" elements 
    When I follow css "tr[data-id=\"1\"] a[data-target=\"#deleteExpenditureModal\"]"
    Then I should see the "deleteExpenditureModal" modal "Delete"
    When I press "Delete"
    Then I should see "Expenditure Deleted"