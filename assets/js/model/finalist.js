/**
 * Finalist model (dto)
 * @author Gemma Black <gblackuk@gmail.com>
 */
export class Finalist {
    constructor({
        id = null,
        name = '',
        description = '',
        imageId = null,
        imageUrl = '',
        categoryId = null,
        orderNum = null,
        votes = 0
    } = {}) {
        this.id = id;
        this.name = name;
        this.description = description;
        this.imageId = imageId;
        this.imageUrl = imageUrl ? imageUrl : '';
        this.categoryId = categoryId;
        this.orderNum = orderNum;
        this.votes = votes;
    }
}