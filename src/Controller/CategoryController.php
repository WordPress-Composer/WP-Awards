<?php

namespace Voting\Controller;

use Voting\Model\Category;
use Voting\Transformer\CategoryTransformer;
use Voting\Transformer\Transform;

/**
 * A Category Controller for api endpoints
 * @author VL
 * @author Gemma Black <gblackuk@gmail.com>
 */
class CategoryController extends BaseController
{

	protected $config;

	/**
	 * Creates category
	 *
	 * @param array $params
	 * @return void
	 */
	public function create(array $params)
	{

		$hasNecessaryParams = !empty($params['name'])
			&& !empty($params['description'])
			&& !empty($params['short_label']);

		if (!$hasNecessaryParams) {
			self::sendErrorMessage('name, description and short_label is needed to create a category');
		}

		$potentiallyExisting = Category::withTrashed()->where('name', $params['name'])->first();

		if ($potentiallyExisting && !$potentiallyExisting->trashed()) {
			self::sendErrorMessage('This category name already exists.');
		}

		// Restore deleted from the dust
		if ($potentiallyExisting && $potentiallyExisting->trashed()) {
			$deletedCategory = $potentiallyExisting;
			$deletedCategory->restore();
		}

		$category = $deletedCategory ? $deletedCategory : new Category;
		$category->name = $params['name'];
		$category->description = $params['description'];
		$category->short_label = $params['short_label'];
		$category->slug = sanitize_title($params['name']);
		$category->save();

		wp_send_json(Transform::model($category)->using(new CategoryTransformer), 201);
	}


	/**
	 * Deletes a category
	 *
	 * @param array $params
	 * @return void
	 */
	public function delete(array $params)
	{
		$category = Category::withCount(['awards'])->where('id', $params['category_id'])->first();

		if (!$category) {
			self::sendErrorMessage('Category does not exist');
		}

		if ($category->awards_count > 0) {
			self::sendErrorMessage($category->name . ' is already linked to an award. You cannot delete it');
		}

		$category->delete();

		wp_send_json(null, 204);
	}


	/**
	 * Updates a category
	 *
	 * @param array $params
	 * @return void
	 */
	public function update(array $params)
	{
		$category = Category::find($params['id']);

		if (!$category) {
			self::sendErrorMessage('Category with id: ' . $params['id'] .' does not exist');
		}

		$category->short_label = isset($params['short_label']) ? $params['short_label'] : $category->short_label;
		$category->description = isset($params['description']) ? $params['description'] : $category->description;
		$category->save();
		wp_send_json(Transform::model($category)->using(new CategoryTransformer), 200);
	}


	/**
	 * Gets all categories
	 *
	 * @param array $params
	 * @return void
	 */
	public function all(array $params)
	{
		$categories = Category::get();
		wp_send_json(Transform::collection($categories)->using(new CategoryTransformer), 200);
	}


	/**
	 * Gets a single category
	 *
	 * @param array $params
	 * @return void
	 */
	public function get(array $params)
	{
		$category = Category::find($params['id']);
		if (!$category) {
            self::sendErrorMessage('Resource does not exist');
        }
		wp_send_json(Transform::model($category)->using(new CategoryTransformer), 200);
	}

}
