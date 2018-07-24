<?php 

namespace Voting\Transformer;

use Illuminate\Database\Eloquent\Model;
use Voting\Model\Winner;
use Voting\Domain\DateFormat;

/**
 * Transforms the Eloquent winner model into an API friendly structure
 * following the JSON spec
 * @author Gemma Black <gblackuk@gmail.com>
 */
final class WinnerTransformer extends Transformer
{

    /**
     * Builds model attributes structure. For custom structure, extend class
     *
     * @param Award $award
     * @return void
     */
    public function getItem(Model $category)
    {
        return $this->build($category);
    }

    private function build(Winner $winner)
    {
        return array_merge(Builder::buildWinner($winner), [
            'award' => [
                'id' => $winner->award->id,
                'title' => $winner->award->title,
                'year' => $winner->award->year,
                'nominationStartDate' => $winner->award->nomination_open->format(DateFormat::DEFAULT),
                'nominationEndDate' => $winner->award->nomination_close->format(DateFormat::DEFAULT),
                'votingStartDate' => $winner->award->voting_open->format(DateFormat::DEFAULT),
                'votingEndDate' => $winner->award->voting_close->format(DateFormat::DEFAULT),
                'winnersAnnouncedOn' => isset($winner->award->winner_announcement_date)
                    ? $winner->award->winner_announcement_date->format(DateFormat::DEFAULT) : null
            ],
            'category' => [
                'id' => $winner->category->id,
                'name' => stripslashes($winner->category->name),
                'description' => stripslashes($winner->category->description),
                'shortLabel' => stripslashes($winner->category->short_label),
                'slug' => $winner->category->slug
            ]
        ]);
    }

}