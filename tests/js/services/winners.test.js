import {mapToModel, mapCollection} from '../../../assets/js/services/winners.js';
import {expect} from 'chai';
import {Winner} from '../../../assets/js/model/winner.js'; 

/**
 * Tests winners service
 * @author Gemma Black <gblackuk@gmail.com>
 */
describe('Service winners.js', function() {

    let json = {
        data: {
            id: 1,
            attributes: {},
            awardId: 3,
            finalistId: 5,
            categoryId: 4,
            imageId: 4,
            imageUrl: 'http://someimageurl.jpg',
            videoUrl: 'http://someimageurl.jpg',
            videoId: 1111,
            biography: 'Some biography',
            award: {
                id: 3,
                title: 'Some title',
                year: 2017
            },
            finalist: {
                id: 2,
                name: 'Winner name',
                biography: 'Some biography',
                imageUrl: 'http://someimageurl.jpg'
            },
            category: {
                id: 4,
                name: 'Some category',
                description: 'Some category description',
                shortLabel: 'Some category label'
            }
        }
    }

    let expected = new Winner({
        id: 1,
        award_id: 3,
        finalist_id: 5,
        category_id: 4,
        name: 'Winner name',
        biography: 'Some biography',
        youtube_url: 'http://someimageurl.jpg',
        image_id: 4,
        image_url: 'http://someimageurl.jpg',
        video_id: 1111
    })

    let jsonCollection = {
        data: [json.data]
    }

    describe('mapToModel', function() {
        test('should map input to output', function() {
            let result = mapToModel(json.data);
            expect(result.award_id).to.equal(expected.award_id);
            expect(result.id).to.equal(expected.id);
            expect(result.finalist_id).to.equal(expected.finalist_id);
            expect(result.category_id).to.equal(expected.category_id);
            expect(result.biography).to.equal(expected.biography);
            expect(result.name).to.equal(expected.name);
            expect(result.youtube_url).to.equal(expected.youtube_url);
            expect(result.image_id).to.equal(expected.image_id);
            expect(result.image_url).to.equal(expected.image_url);
            expect(result.video_id).to.equal(expected.video_id);
        });
    });

    describe('mapCollection', function() {
        test('should map raw input to resolved promise', function(done) {
            mapCollection(jsonCollection).then(response => {
                expect(response).to.deep.equal([expected]);
                done();
            });
        });
    });
});
