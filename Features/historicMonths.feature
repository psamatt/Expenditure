Feature: Historic Months
  In order to view historic months
  As a logged in user
  I must click to view them

  Scenario: View no historic months
    
    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin/month/historic"
    Then I should see "No month headers have been found"

  Scenario: View an existing historic months
    
    Given the following month headers are expenditure month headers
    | ID | User | Calendar Date | Month Income |
    | 1  | 1    | 1st May 2010  | 1000         |
    
    Given the following expenditures are expenditure
    | ID | Header       | Title | Price | Amount Paid |
    | 1  | 1st May 2010 | Foo   | 100   | 50          |
    | 2  | 1st May 2010 | Bar   | 100   | 100         |
    | 3  | 1st May 2010 | Foo 1 | 200   | 0           |
    
    Given I am logged in as "matt.goodwin491@gmail.com" with "Admin123"
    When I go to "/admin/month/historic"
    Then I should see "May 2010"
    When I follow "May 2010"
    Then I should see 1 "table tr.expenditure-paid" element
    Then I should see 1 "table tr.expenditure-partial-paid" element
    Then the "span#amountPaid" element should contain "150"
    Then the "span#amountLeft" element should contain "850"
    Then the "span#total-price" element should contain "400"
    Then the "span#total-paid" element should contain "150"