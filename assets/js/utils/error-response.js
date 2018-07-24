/**
 * Error response utility
 * @author Gemma Black <gblackuk@gmail.com>
 */

import {maybe, just, nothing} from 'maybes';

/**
 * Server error class
 * @property {string} name
 * @property {number} status
 * @property {string} code
 */
export class ServerError extends Error {
    constructor ({ message, code, status }) {
        super(message);
        this.name = 'ServerError';
        Error.captureStackTrace(this, this.constructor);
        this.status = status || 500;
        this.code = code || 'SERVER_ERROR';
    }
};

/**
 * API response structure (Maybe)
 * @param {object} response
 * @return {maybe} maybe
 */
export const APIResponseStructure 
    = response => 
        maybe(response).orJust(null)
        && maybe(response.data).orJust(null)
        && maybe(response.status).orJust(null)
        && maybe(response.data.error).orJust(null)
        && maybe(response.data.error.code).orJust(null)
        && maybe(response.data.error.message).orJust(null)
        ? just(response) : nothing;

/**
 * Maps server response error
 * @param {object} response 
 * @return {object}
 */
export const mapServerErrorResponse
    = response => 
        ({
            code: response.data.error.code,
            status: response.status,
            message: response.data.error.message
        });

/**
 * Checks for API Error Response
 * @param {object} err 
 */
export const APIError
    = err => maybe(err).orJust(null) && maybe(err.response).orJust(null)
        ? just(err.response) : nothing;

/**
 * Main function that checks if error response is a server error, otherwise
 * it throws a generic error
 * @throws ServerError
 * @throws Error
 * @param {object} err
 */
export default function(err) {

    let serverError = maybe(err)
        .flatMap(APIError)
        .flatMap(APIResponseStructure)
        .map(mapServerErrorResponse)
        .orJust(null);

    if (serverError !== null) {
        throw new ServerError(serverError);
    }
    
    throw new Error(err);

}