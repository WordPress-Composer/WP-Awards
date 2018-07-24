import {
    getWinnerMetaByFinalist,
    winnerMetaFinalists,
    getWinnerByCategory,
    getFinalistsByCategory,
    filterWhere
} from '../../../assets/js/view-helper/winners-edit.js';
import {WinnersMeta} from '../../../assets/js/model/winner-meta.js';
import {Finalist} from '../../../assets/js/model/finalist.js';
import {PossibleWinner} from '../../../assets/js/model/possible-winner.js';
import {Winner} from '../../../assets/js/model/winner.js';

describe('Choose Winners View Model', function() {
    describe('getWinnerMetaByFinalist', function() {
        test('Gets the correct meta', function() {
            let metas = [
                new WinnersMeta({ finalist_id: 9, biography: 'abc' }),
                new WinnersMeta({ finalist_id: 2, biography: '123' }),
                new WinnersMeta({ finalist_id: 5, biography: '***' })
            ];

            let result = getWinnerMetaByFinalist(metas, 2);
            
            expect(result.biography).toEqual('123');
        });

        test('Returns undefined if it can not find meta', function() {
            let metas = [
                new WinnersMeta({ finalist_id: 9, biography: 'abc' }),
                new WinnersMeta({ finalist_id: 2, biography: '123' }),
                new WinnersMeta({ finalist_id: 5, biography: '***' })
            ];

            let result = getWinnerMetaByFinalist(metas, 1);
            
            expect(result).toEqual(undefined);
        });
    });

    describe('winnerMetaFinalists', function() {
        test('When no winners meta exists', function() {
            let metas = [];
            let finalists = [
                new Finalist({ 
                    id: 2, 
                    name: 'Tom',
                    description: 'finalist bio',
                    categoryId: 9,
                    imageId: 3,
                    imageUrl: 'some url'
                })
            ];
            let result = winnerMetaFinalists(metas, finalists);
 
            expect(result[0]).toBeInstanceOf(PossibleWinner);
            expect(result.length).toEqual(1);
            expect(result[0].name).toEqual('Tom');
            expect(result[0].biography).toEqual('finalist bio');
            expect(result[0].category_id).toEqual(9);
            expect(result[0].image_id).toEqual(3);
            expect(result[0].image_url).toEqual('some url');
            expect(result[0].video_type).toEqual('youtube');
            expect(result[0].video_url).toEqual('');
            expect(result[0].votes).toEqual(0);
        });

        test('Returns merged finalist', function() {

            let metas = [
                new WinnersMeta({ 
                    finalist_id: 2, 
                    biography: 'some bio',
                    image_id: 100,
                    image_url: 'meta image url',
                    video_type: 'vimeo',
                    video_url: 'some video url'
                })
            ];

            let finalists = [
                new Finalist({ 
                    id: 2, 
                    name: 'Tom',
                    description: 'finalist bio',
                    categoryId: 9,
                    imageId: 3,
                    imageUrl: 'some url',
                    votes: 99
                })
            ];
            let result = winnerMetaFinalists(metas, finalists);
            expect(result[0]).toBeInstanceOf(PossibleWinner);
            expect(result.length).toEqual(1);
            expect(result[0].name).toEqual('Tom');
            expect(result[0].biography).toEqual('some bio');
            expect(result[0].category_id).toEqual(9);
            expect(result[0].image_id).toEqual(100);
            expect(result[0].image_url).toEqual('meta image url');
            expect(result[0].video_type).toEqual('vimeo');
            expect(result[0].video_url).toEqual('some video url');
            expect(result[0].votes).toEqual(99);
        });
        
    });

    describe('getWinnerByCategory', function() {
        test('Finds winner by category id', function() {
            let winners = [
                new Winner({ category_id: 1, finalist_id: 12 }),
                new Winner({ category_id: 2, finalist_id: 456 }),
                new Winner({ category_id: 3, finalist_id: 324 }),
                new Winner({ category_id: 4, finalist_id: 678 }),
            ];
            
            expect(getWinnerByCategory(winners, 4).finalist_id).toEqual(678);
            expect(getWinnerByCategory(winners, 3).finalist_id).toEqual(324);
            expect(getWinnerByCategory(winners, 2).finalist_id).toEqual(456);
            expect(getWinnerByCategory(winners, 1).finalist_id).toEqual(12);
        });
    });

    describe('getFinalistsByCategory', function() {
        test('Finds finalist by category id', function() {
            let finalists = [
                new Finalist({ categoryId: 1, id: 456 }),
                new Finalist({ categoryId: 3, id: 1 }),
                new Finalist({ categoryId: 3, id: 657567 }),
                new Finalist({ categoryId: 7, id: 234 }),
            ];

            expect(getFinalistsByCategory(finalists, 3).length).toEqual(2);
            expect(getFinalistsByCategory(finalists, 3)[0].id).toEqual(1);
            expect(getFinalistsByCategory(finalists, 3)[1].id).toEqual(657567);

            expect(getFinalistsByCategory(finalists, 1).length).toEqual(1);
            expect(getFinalistsByCategory(finalists, 7).length).toEqual(1);
            expect(getFinalistsByCategory(finalists, 5).length).toEqual(0);
        });
    });

    describe('filterWhere', function() {
        test('Filters out an array', function() {
            let array = [
                { name: 'Jane', id: 99 },
                { name: 'Francoise', id: 234234 },
                { name: 'Tom', id: 5 }
            ]

            expect(filterWhere(array, 'name', 'Tom')[0].id).toEqual(5);
            expect(filterWhere(array, 'name', 'Francoise')[0].id).toEqual(234234);
            expect(filterWhere(array, 'name', 'Jane')[0].id).toEqual(99);
        });
    });
});