Feature: Going Live With An Award
    In order to have an award go live
    As an administrator
    I need to be able publish the award as live

    Rules:
    - n/a?

    Scenario: Going live with an award
        Given an award is not already live
        When I click on the "go live" button
        Then it should go live with the award
        And I can now see that it is a live event
        And the users on the front end can see it is a live event