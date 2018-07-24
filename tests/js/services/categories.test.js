import {mapToModel, mapSingle, mapCollection} from '../../../assets/js/services/categories.js';
import {Category} from '../../../assets/js/model/category.js';
import {expect} from 'chai';

/**
 * Tests categories service
 * @author Gemma Black <gblackuk@gmail.com>
 */
describe('Service categories.js', function() {
    let json = {
        data: {
            id: 1,
            name: 'Some name',
            description: 'Some description',
            slug: 'some-slug',
            shortLabel: 'some label'
        }
    }

    let jsonCollection = {
        data: [json.data]
    }

    let expected = new Category(1, 'Some name', 'Some description', 'some-slug', 'some label');

    describe('mapToModel', function() {
        test('should map input to output', function() {
            let result = mapToModel(json.data);
            expect(result).to.deep.equal(expected);
        });
    });

    describe('mapSingle', function() {
        test('should map raw input to resolved promise', function(done) {
            let output = new Category(1, 'Some name', 'Some description', 'some-slug', 'some label');
            mapSingle(json).then(response => {
                expect(response.category).to.deep.equal(expected);
                done();
            });
        });
    });

    describe('mapCollection', function() {
        test('should map raw input to resolved promise', function(done) {
            mapCollection(jsonCollection).then(response => {
                expect(response.categories).to.deep.equal([expected]);
                done();
            });
        });
    });
});
