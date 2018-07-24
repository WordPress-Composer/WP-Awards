/**
 * Category model (dto)
 * @author Gemma Black <gblackuk@gmail.com>
 */
export class Category {
    constructor(id, name, description, slug, short_label) {
        this.id = id;
        this.name = name;
        this.description = description;
        this.slug = slug;
        this.short_label = short_label;
    }
}
export default Category;