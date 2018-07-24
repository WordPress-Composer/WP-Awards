Feature: Award winners
    In order to announce winners for an award
    As an administrator
    I need to be able to select a winner from finalists

    Rules:
    - Only 1 winner per category
    - Only select winners from finalists selected
    - Winners are not finalised until the winners have been published

    Scenario: Selecting a winner from finalists
        Given there isn't a winner for a category
        And there are finalists for that category
        When I select a winner
        Then I should see that winner selected

    Scenario: Selecting another winner from finalists for a category
        Given there is already a winner for a category
        When I attempt to select a winner
        Then I shouldn't be able to select a winner
        And first I have to unselect the winner for the category
        Then I should be able to select a new winner for the category

    Scenario: Selecting a winner when there isn't a finalist
        Given there isn't a winner
        When I attempt to select a winner
        Then I should not be able to select a winner
        And I will have to first enter a finalist
        Then select a winner