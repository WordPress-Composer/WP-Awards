import {mapToModel, mapCollection, mapSingle} from '../../../assets/js/services/stats.js';

describe('Stats Model', () => {
    let json = {
        data: {
            id: 5,
            submissions: 9823,
            votes: 234098
        }
    }

    let jsonCollection = {
        data: [json.data]
    }

    describe('mapToModel', function() {
        test('should map input to output', function() {
            let result = mapToModel(json.data);
            expect(result.awardId).toBe(json.data.id);
            expect(result.submissions).toBe(json.data.submissions);
            expect(result.votes).toBe(json.data.votes);
        });
    });

    describe('mapCollection', function() {
        test('should map raw input to resolve promise', function(done) {
            mapCollection(jsonCollection)
                .then(response => {
                    let result = response.stats[0];
                    expect(result.awardId).toBe(json.data.id);
                    expect(result.submissions).toBe(json.data.submissions);
                    expect(result.votes).toBe(json.data.votes);
                    done();
                });
        });
    });
});