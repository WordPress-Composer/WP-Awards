import {PossibleWinner} from '../model/possible-winner.js';
import {maybe} from 'maybes';

class NotRequestedYet {}

class ErrorOnLoad {}

export const Init = () => {
    return {
        NotRequestedYet,
        ErrorOnLoad,
        filterWhere,
        getWinnerByCategory,
        getFinalistsByCategory,
        winnerMetaFinalists,
        getError,
        award: new NotRequestedYet,
        categories: new NotRequestedYet,
        winners: new NotRequestedYet,
        finalists: new NotRequestedYet,
        winnersMeta: new NotRequestedYet,
        sections: [],
        prePublishing: false,
        publishError: null,
        saveError: {
            category_id: null,
            message: null
        },
        awardLoadError: null,
        unpublishing: false
    }
}

export const filterWhere 
    = (array, key, value) => array.filter(found => found[key] === value)

export const getFinalistsByCategory
    = (finalists, categoryId) => finalists.filter(finalist => finalist.categoryId === categoryId);

export const getWinnerByCategory 
    = (winners, categoryId) => winners.find(winner => winner.category_id === categoryId)

export const getWinnerMetaByFinalist
    = (metas, finalistId) => metas.find(meta => meta.finalist_id === finalistId);

export const winnerMetaFinalists
    = (metas, finalists) => 
        finalists.map(finalist => {

            let meta = maybe(getWinnerMetaByFinalist(metas, finalist.id)).orJust({});

            return new PossibleWinner({
                category_id: finalist.categoryId,
                finalist_id: finalist.id,
                biography: maybe(meta.biography).orJust(finalist.description),
                name: finalist.name,
                image_id: maybe(meta.image_id).orJust(finalist.imageId),
                image_url: maybe(meta.image_url).orJust(finalist.imageUrl),
                video_url: maybe(meta.video_url).orJust(''),
                video_type: maybe(meta.video_type).orJust('youtube'),
                video_id: maybe(meta.video_id).orJust(null),
                votes: finalist.votes
            })
        });

export const getError 
    = (error, categoryId) => error.category_id === categoryId ? error.message : null;