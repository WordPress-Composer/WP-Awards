/**
 * View helper for the finalist-edit screen
 * @author Gemma Black <gblackuk@gmail.com>
 */

/**
 * Asynchronous Response object to relay success and errornous messages
 * 
 * @param {number} categoryId
 * @param {number} orderNumber
 * @param {string} message
 * @param {boolean} error
 */
export class AsyncResponse
{
    constructor (categoryId = null, orderNumber = null, message = '', error = false) {
        this.error = error !== false;
        this.message = message;
        this.categoryId = categoryId;
        this.orderNumber = orderNumber;
    }


    /**
     * Can show response
     * 
     * @param {number} categoryId 
     * @param {number} orderNumber 
     */
    canShow(categoryId, orderNumber) {
        return this.message !== ''
            && this.categoryId === categoryId
            && this.orderNumber === orderNumber;
    }
};