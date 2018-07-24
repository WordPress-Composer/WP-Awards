import {dto, mapCollection} from '../../../assets/js/services/award-categories.js';
import {expect} from 'chai';

/**
 * Tests schedule item
 * @author Gemma Black <gblackuk@gmail.com>
 */
describe('Service award-categories.js', function() {
    describe('dto', function() {
        test('should map input to output', function() {
            let input = {
                id: 1,
                description: 'Some description',
                name: 'Some name',
                shortLabel: 'some label',
                slug: 'some-slug'
            }

            let output = {
                id: 1,
                description: 'Some description',
                name: 'Some name',
                short_label: 'some label',
                slug: 'some-slug'
            }

            let result = dto(input);

            expect(result).to.deep.equal(output);
        });
    });
});
