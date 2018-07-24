Feature: Award Finalists
    In order to list finalists for an award
    As an administrator
    I need to be able to add a finalists for an event category

    Rules:
    - Only a maximum of 3 finalists are allowed for an event category
    - Can be a person or organisation
    - Order number determines their position (but it's not a be-all and end all)

    Scenario: Adding the finalist as a Best Film Director for the 2017 Oscars
        Given there isn't a finalist for Best Film Director
        And the award is the 2017 Oscars 
        When I add the finalist "John Doe"
        Then I should have 1 finalist for the Best Film Director category in the 2017 Oscars 

    Scenario: Adding the 2nd finalist as a Best Film Director for the 2017 Oscars
        Given there is already a finalist for Best Film Director category
        And the award is the 2017 Oscars 
        When I add "Tom Vanguard" as a finalist
        Then I should have 2 finalists for the Best Film Director category in the 2017 Oscars

    Scenario: Adding the 4th finalist as a Best Film Director category in the 2017 Oscars 
        Given there are already 3 finalists for Best Film Director category
        And the award is the 2017 Oscars 
        When I add "Lisa Four" as a finalist
        Then it should tell me that this finalist will not be saved and I will have to remove the finalist 

    Scenario: Removing a finalist for as Best Film Director category in the 2017 Oscars
        Given there are already 3 finalists for Best Film Director category
        And the award is the 2017 Oscars
        When I remove finalist "Cameron James" from this award category
        Then it should remove this finalist
        And I should have only 2 finalists left 

    Scenario: Remove the last finalist as Best Film Director Award in the 2017 Oscarss 
        Given there is only 1 finalists for Best Film Director category
        And the award is the 2017 Oscars
        When I remove finalist "Cameron James" from this award category
        Then it should remove this finalist
        And I should see no finalists

    Scenario: Two admins try to add a 3rd finalist at the same time
        Given there are already 2 finalists for the "Sports Person" category
        And the award is the 2017 Oscarss
        When I add "Cameron James" as a finalist
        And another admin adds "Susie Sue" as a finalist a microsecond later 
        Then I should see "Cameron James" as a finalist
        But the other admin should not see "Susie Sue" as a finalist
        And she should be shown a message that another finalist