import {mapToModel, mapAward, mapSingle, mapCollection} from '../../../assets/js/services/awards.js';
import {expect} from 'chai';

describe('Service awards.js', function() {
    let json = {
        data: {
            id: 123,
            title: 'Some award title',
            year: '2017',
            nominationStartDate: '2017-01-01 00:00:00',
            nominationEndDate: '2017-02-01 00:00:00',
            votingStartDate: '2017-03-01 00:00:00',
            votingEndDate: '2017-04-01 00:00:00',
            isLive: 0,
            isArchived: 1,
            winnersAnnouncedOn: null,
            categories: [{
                id: 7,
                name: 'Category name',
                description: 'category description',
                shortLabel: 'some category label',
                slug: 'some-label'
            }]
        }
    }

    let jsonCollection = {
        data: [json.data]
    }

    describe('mapAward', function() {
        test('should map input to output', function() {
            let result = mapAward(json.data);
            expect(result.id, 'id').to.equal(json.data.id)
            expect(result.year, 'year').to.equal(parseInt(json.data.year));
            expect(result.title).to.equal(json.data.title);
            expect(result.nomination_start_date, 'nomination start date').to.equal(json.data.nominationStartDate);
            expect(result.nomination_end_date, 'nomination end date').to.equal(json.data.nominationEndDate);
            expect(result.voting_start_date, 'voting start date').to.equal(json.data.votingStartDate);
            expect(result.voting_end_date, 'voting end date').to.equal(json.data.votingEndDate);
            expect(result.live, 'is live').to.equal(json.data.isLive);
            expect(result.archived, 'is archived').to.equal(json.data.isArchived);
            expect(result.winners_announced_on, 'winners announced on').to.equal(json.data.winnersAnnouncedOn);
        });
    });

    describe('mapSingle', function() {
        test('should map raw input to resolved promise of awards and award categories', function(done) {
            mapSingle(json).then(response => {
                
                expect(response.award.id, 'id').to.equal(json.data.id);
                expect(response.award.year, 'year').to.equal(parseInt(json.data.year));
                expect(response.award.title, 'title').to.equal(json.data.title);
                expect(response.award.nomination_start_date, 'nomination start datea').to.equal(json.data.nominationStartDate);
                expect(response.award.nomination_end_date, 'nomination end date').to.equal(json.data.nominationEndDate);
                expect(response.award.voting_start_date, 'voting start date').to.equal(json.data.votingStartDate);
                expect(response.award.voting_end_date, 'voting end date').to.equal(json.data.votingEndDate);
                expect(response.award.live, 'is live').to.equal(json.data.isLive);
                expect(response.award.archived, 'is archived').to.equal(json.data.isArchived);
                expect(response.award.winners_announced_on, 'winners announced on').to.equal(json.data.winnersAnnouncedOn);

                expect(response.categories[0].id).to.equal(json.data.categories[0].id);
                expect(response.categories[0].name).to.equal(json.data.categories[0].name);
                expect(response.categories[0].description).to.equal(json.data.categories[0].description);
                expect(response.categories[0].short_label).to.equal(json.data.categories[0].shortLabel);
                expect(response.categories[0].slug).to.equal(json.data.categories[0].slug);

                done();
            });
        });
    });

    describe('mapCollection', function() {
        test('should map raw input to resolved promise of award and award categories', function(done) {
            mapCollection(jsonCollection).then(response => {
                let award = response[0].award;
                let categories = response[0].categories;

                expect(award.id, 'id').to.equal(json.data.id);
                expect(award.year, 'year').to.equal(parseInt(json.data.year));
                expect(award.title, 'title').to.equal(json.data.title);
                expect(award.nomination_start_date, 'nomination start datea').to.equal(json.data.nominationStartDate);
                expect(award.nomination_end_date, 'nomination end date').to.equal(json.data.nominationEndDate);
                expect(award.voting_start_date, 'voting start date').to.equal(json.data.votingStartDate);
                expect(award.voting_end_date, 'voting end date').to.equal(json.data.votingEndDate);
                expect(award.live, 'is live').to.equal(json.data.isLive);
                expect(award.archived, 'is archived').to.equal(json.data.isArchived);
                expect(award.winners_announced_on, 'winners announced on').to.equal(json.data.winnersAnnouncedOn);

                expect(categories[0].id).to.equal(json.data.categories[0].id);
                expect(categories[0].name).to.equal(json.data.categories[0].name);
                expect(categories[0].description).to.equal(json.data.categories[0].description);
                expect(categories[0].short_label).to.equal(json.data.categories[0].shortLabel);
                expect(categories[0].slug).to.equal(json.data.categories[0].slug);

                done();
            });
        });
    });
});