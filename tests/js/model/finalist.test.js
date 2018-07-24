import {Finalist} from '../../../assets/js/model/finalist.js';

describe('Model finalists.js', function() {
    test('should map defaults', function() {
        let finalist = new Finalist();
        expect(finalist.id).toEqual(null);
        expect(finalist.name).toEqual('');
        expect(finalist.description).toEqual('');
        expect(finalist.imageId).toEqual(null);
        expect(finalist.imageUrl).toEqual('');
        expect(finalist.categoryId).toEqual(null);
        expect(finalist.orderNum).toEqual(null);
        expect(finalist.votes).toEqual(0);
    });

    test('maps inputs', function() {

        let finalist = new Finalist({
            id: 1,
            name: 'Joe Bloggs',
            description: 'Something',
            imageId: 86,
            imageUrl: 'http://image.com/something.jpg',
            categoryId: 3,
            orderNum: 1,
            votes: 12023
        });

        expect(finalist.id).toEqual(1);
        expect(finalist.name).toEqual('Joe Bloggs');
        expect(finalist.description).toEqual('Something');
        expect(finalist.imageId).toEqual(86);
        expect(finalist.imageUrl).toEqual('http://image.com/something.jpg');
        expect(finalist.categoryId).toEqual(3);
        expect(finalist.orderNum).toEqual(1);
        expect(finalist.votes).toEqual(12023);
    })
});