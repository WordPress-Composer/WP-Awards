<?php 

namespace Voting\Repository;

use Voting\Domain\AwardState;
use Voting\Domain\Award;
use Voting\Domain\AwardTitle;
use Voting\Domain\AwardYear;
use Voting\Domain\Id;
use Voting\Domain\VotingSchedule;
use Voting\Domain\NominationDates;
use Voting\Domain\VotingDates;
use Voting\Domain\DateFormat;
use Voting\Domain\Category;
use Carbon\Carbon;
use Voting\Model\Award as EloquentModel;
use Illuminate\Database\Eloquent\Collection;
use Voting\Hydrator\Award as Hydrator;

/**
 * Repository for getting award domain objects
 * @todo add logic if award does not exist
 * @author Gemma Black <gblackuk@gmail.com>
 */
class AwardsRepository
{

    /**
     * Creates an award
     * @param Award $award
     * @return void
     */
    public function create(Award $award)
    {
        $model = new EloquentModel;
        $model->title = $award->title()->string();
        $model->year = $award->year()->string();
        $model->nomination_open = $award->schedule()->nominations()->start();
        $model->nomination_close = $award->schedule()->nominations()->end();
        $model->voting_open = $award->schedule()->voting()->start();
        $model->voting_close = $award->schedule()->voting()->end();
        $model->save();
        return $this->map($model);
    }

    /**
     * Updates an award
     *
     * @param Award $award
     * @return void
     */
    public function update(Award $award)
    {
        $model = EloquentModel::find($award->id()->value());
        $model->title = $award->title()->string();
        $model->year = $award->year()->string();
        $model->nomination_open = $award->schedule()->nominations()->start();
        $model->nomination_close = $award->schedule()->nominations()->end();
        $model->voting_open = $award->schedule()->voting()->start();
        $model->voting_close = $award->schedule()->voting()->end();
        $model->archived = $award->isArchived();
        $model->live = $award->isLive();
        $model->winner_announcement_date = $award->winnersPublishedOn();
        $model->save();
        return $this->map($model);
    }

    /**
     * Deletes an award
     *
     * @param Award $award
     * @return void
     */
    public function delete(Award $award)
    {
        $model = EloquentModel::find($award->id()->value());
        $model->delete();
    }

    /**
     * Finds an award by id
     *
     * @param integer $id
     * @return void
     */
    public function find($id)
    {
        $model = EloquentModel::with(['categories'])->find($id);
        return $model ? $this->map($model) : null;
    }

    /**
     * Finds all awards
     *
     * @return void
     */
    public function findAll()
    {
        $model = EloquentModel::get();
        return $model ? $this->mapMany($model) : [];
    }


    /**
     * Maps many awards back to domain objects
     *
     * @param Collection $awards
     * @return void
     */
    private function mapMany(Collection $awards)
    {
        return $awards->map(function($award) {
            return $this->map($award);
        })->toArray();
    }


    /**
     * Maps an eloquent award back into a domain object
     *
     * @param EloquentModel $award
     * @return void
     */
    private function map(EloquentModel $award)
    {
        $hydrator = new Hydrator;
        $hydrator->setId(new Id($award->id));
        $hydrator->setTitle(new AwardTitle($award->title));
        $hydrator->setYear(new AwardYear($award->year));
        $hydrator->setSchedule(new VotingSchedule(
            NominationDates::create(
                $award->nomination_open,
                $award->nomination_close
            ),
            VotingDates::create(
                $award->voting_open,
                $award->voting_close
            )
        ));
        $hydrator->setIsLive($award->live);
        $hydrator->setIsArchived($award->archived);
        $hydrator->setWinnersPublishedOn($award->winner_announcement_date);
        return $hydrator->hydrate();
    }
}