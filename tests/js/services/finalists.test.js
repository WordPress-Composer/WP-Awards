import {mapToModel, mapCollection, mapSingle} from '../../../assets/js/services/finalists.js';
import {expect} from 'chai';

describe('Service finalists.js', function() {
    let json = {
        data: {
            id: 5,
            name: 'Some finalist',
            categoryId: 3,
            imageUrl: 'http://someimage.url/image.jpg',
            biography: 'Some biography',
            orderNumber: 3,
            imageId: 3,
            votes: 1213
        }
    }

    let jsonCollection = {
        data: [json.data]
    }

    describe('mapToModel', function() {
        test('should map input to output', function() {
            let result = mapToModel(json.data);
            expect(result.id).to.equal(json.data.id);
            expect(result.name).to.equal(json.data.name);
            expect(result.categoryId).to.equal(json.data.categoryId);
            expect(result.imageUrl).to.equal(json.data.imageUrl);
            expect(result.description).to.equal(json.data.biography);
            expect(result.orderNum).to.equal(json.data.orderNumber);
            expect(result.imageId).to.equal(json.data.imageId);
            expect(result.votes).to.equal(json.data.votes);
        });
    });

    describe('mapSingle', function() {
        test('should map input to output', function(done) {
            let result = mapSingle(json);
            mapSingle(json)
                .then(({finalist}) => {
                    expect(finalist.id).to.equal(json.data.id);
                    expect(finalist.name).to.equal(json.data.name);
                    expect(finalist.categoryId).to.equal(json.data.categoryId);
                    expect(finalist.imageUrl).to.equal(json.data.imageUrl);
                    expect(finalist.description).to.equal(json.data.biography);
                    expect(finalist.orderNum).to.equal(json.data.orderNumber);
                    expect(finalist.imageId).to.equal(json.data.imageId);
                    expect(finalist.votes).to.equal(json.data.votes);
                    done();
                });
        });
    });

    describe('mapCollection', function() {
        test('should map raw input to resolve promise', function(done) {
            mapCollection(jsonCollection)
                .then(response => {
                    let result = response.finalists[0];
                    expect(result.id).to.equal(json.data.id);
                    expect(result.name).to.equal(json.data.name);
                    expect(result.categoryId).to.equal(json.data.categoryId);
                    expect(result.imageUrl).to.equal(json.data.imageUrl);
                    expect(result.description).to.equal(json.data.biography);
                    expect(result.orderNum).to.equal(json.data.orderNumber);
                    expect(result.imageId).to.equal(json.data.imageId);
                    expect(result.votes).to.equal(json.data.votes);
                    done();
                });
        });
    });
});