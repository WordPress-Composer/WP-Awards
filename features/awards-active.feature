Feature: Current Award
    In order to have a current or active award
    As an administrator
    I need to be able activate an award

    Rules:
    - n/a?

    Scenario: Choosing an active award
        Given an award is not already the active award
        When I click on the make current award
        Then I should see that is it the current award