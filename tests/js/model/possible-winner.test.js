import {PossibleWinner} from '../../../assets/js/model/possible-winner.js';

describe('Model finalists.js', function() {
    test('should map defaults', function() {
        let possibleWinner = new PossibleWinner;
        expect(possibleWinner.id).toEqual(null);
        expect(possibleWinner.category_id).toEqual(null);
        expect(possibleWinner.finalist_id).toEqual(null);
        expect(possibleWinner.name).toEqual('');
        expect(possibleWinner.biography).toEqual('');
        expect(possibleWinner.image_id).toEqual(null);
        expect(possibleWinner.image_url).toEqual('');
        expect(possibleWinner.video_url).toEqual('');
        expect(possibleWinner.video_type).toEqual('youtube');
    });

    test('maps inputs', function() {
        let possibleWinner = new PossibleWinner({
            id: 1,
            category_id: 4,
            finalist_id: 9,
            name: 'Joe Bloggs',
            biography: 'Rodeo',
            image_id: 9,
            image_url: 'http://image.com/1.jpg',
            video_url: 'http://some.com/video.mp4',
            video_type: 'vimeo'
        });

        expect(possibleWinner.id).toEqual(1);
        expect(possibleWinner.category_id).toEqual(4);
        expect(possibleWinner.finalist_id).toEqual(9);
        expect(possibleWinner.name).toEqual('Joe Bloggs');
        expect(possibleWinner.biography).toEqual('Rodeo');
        expect(possibleWinner.image_id).toEqual(9);
        expect(possibleWinner.image_url).toEqual('http://image.com/1.jpg');
        expect(possibleWinner.video_url).toEqual('http://some.com/video.mp4');
        expect(possibleWinner.video_type).toEqual('vimeo');
        
    });
});