<?php 

namespace Voting\Controller;

use Voting\Repository\AwardsRepository;
use Voting\Transformer\Domain\AwardTransformer as DomainTransformer;
use Voting\Transformer\AwardTransformer;
use Voting\Transformer\Transform;

use Carbon\Carbon;
use Voting\Domain\AwardState;
use Voting\Domain\Award;
use Voting\Domain\AwardTitle;
use Voting\Domain\AwardYear;
use Voting\Domain\OptionalId;
use Voting\Domain\VotingSchedule;
use Voting\Domain\NominationDates;
use Voting\Domain\VotingDates;
use Voting\Domain\DateFormat;
use Voting\Domain\Category;
use Voting\Domain\CurrentTime;

use Voting\Model\Award as EloquentModel;

/**
 * Awards coontroller
 * @author Gemma Black <gblackuk@gmail.com>
 */
final class AwardController extends BaseController
{

    public function __construct()
    {
        $this->repository = new AwardsRepository;
    }

    /**
     * Create an award
     * @param array $params
     * @return void
     */
    public function create(array $params)
    {

        $award = Award::start(
            new AwardTitle($params['title']),
            new AwardYear($params['year']),
            new VotingSchedule(
                NominationDates::create(
                    Carbon::createFromFormat(DateFormat::DEFAULT, $params['nomination_start_date']),
                    Carbon::createFromFormat(DateFormat::DEFAULT, $params['nomination_end_date'])
                ),
                VotingDates::create(
                    Carbon::createFromFormat(DateFormat::DEFAULT, $params['voting_start_date']),
                    Carbon::createFromFormat(DateFormat::DEFAULT, $params['voting_end_date'])
                )
            )
        );

        $saved = $this->repository->create($award);

        wp_send_json(DomainTransformer::map($saved), 201);
    }


    /**
     * Updates an award
     *
     * @param array $params
     * @return void
     */
    public function update(array $params)
    {

        $award = $this->repository->find($params['award_id']);

        if (!$award) {
            wp_send_json([ 'error' => ['message' => 'Resource does not exist']], 400);
        }

        if ($params['title']) {
            $award = Award::updateTitle($award, new AwardTitle($params['title']));
        }
        
        if ($params['year']) {
            $award = Award::updateYear($award, new AwardYear($params['year']));
        }

        $hasScheduleParams = isset($params['nomination_start_date'])
            && isset($params['nomination_end_date'])
            && isset($params['voting_start_date'])
            && isset($params['voting_end_date']);

        if ($hasScheduleParams) {
            $award = Award::updateSchedule($award, new VotingSchedule(
                NominationDates::create(
                    Carbon::createFromFormat(DateFormat::DEFAULT, $params['nomination_start_date']),
                    Carbon::createFromFormat(DateFormat::DEFAULT, $params['nomination_end_date'])
                ),
                VotingDates::create(
                    Carbon::createFromFormat(DateFormat::DEFAULT, $params['voting_start_date']),
                    Carbon::createFromFormat(DateFormat::DEFAULT, $params['voting_end_date'])
                )
            ));
        }
        
        $updated = $this->repository->update($award);
        
        wp_send_json(DomainTransformer::map($updated), 200);
    }


    /**
     * Deletes an award
     *
     * @param array $params
     * @return void
     */
    public function delete(array $params)
    {
        $award = $this->repository->find($params['id']);
        if (!$award) {
            self::sendErrorMessage('Cannot delete a resource that does not exist');
        }
        $this->repository->delete($award);
        wp_send_json('', 204);
    }


    /**
     * Get all awards
     * @param array $params
     * @return void
     */
    public function all(array $params)
    {
        $collection = EloquentModel::with(['categories', 'finalists'])->get();
        wp_send_json(
            Transform::collection($collection)->using(new AwardTransformer),
            200
        );
    }


    /**
     * All awards
     *
     * @param array $params
     * @return void
     */
    public function get(array $params)
    {
        $model = EloquentModel::with(['categories', 'finalists'])->find($params['id']);
        if (!$model) {
            self::sendErrorMessage('This award does not exist', 'RESOURCE_MISSING_ERROR');
        }
        wp_send_json(
            Transform::model($model)->using(new AwardTransformer),
            200
        );
    }


    /**
     * Archive award
     *
     * @param array $params
     * @param int   $params['award_id']
     * @return void
     */
    public function archive(array $params)
    {
        $award = $this->repository->find($params['award_id']);

        if (!$award) {
            self::sendErrorMessage('Cannot archive a non-existent award', 'MISSING_RESOURCE_EXCEPTION', 404);
        }

        $updated = Award::archive($award, CurrentTime::set());
        $saved = $this->repository->update($updated);

        wp_send_json(DomainTransformer::map($saved), 200);
    }


    /**
     * Go live with award
     *
     * @param array $params
     * @param int   $params['award_id']
     * @return void
     */
    public function goLive(array $params)
    {
        $existingArchive = EloquentModel::where('live', '=', true)->first();

        if ($existingArchive) {
            self::sendErrorMessage('An award is already live. You cannot have 2 live awards.');
        }
        
        $award = $this->repository->find($params['award_id']);

        if (!$award) {
            wp_send_json([ 'error' => ['message' => 'Cannot go-live with a non-existent award']], 404);
        }

        $updated = Award::goLive($award);
        $saved = $this->repository->update($updated);
        wp_send_json(DomainTransformer::map($saved), 200);
    }


    /**
     * Unpublish award
     *
     * @param array $params
     * @param int   $params['award_id']
     * @return void
     */
    public function unpublish(array $params)
    {
        $award = $this->repository->find($params['award_id']);

        if (!$award) {
            self::sendErrorMessage('Cannot unpublish a non-existent award', 'MISSING_RESOURCE_EXCEPTION', 404);
        }

        $updated = Award::unpublish($award);
        $saved = $this->repository->update($updated);
        wp_send_json(DomainTransformer::map($saved), 200);
    }


    /**
     * Publish winners for award
     *
     * @param array $params
     * @param int   $params['award_id']
     * @return void
     */
    public function publishWinners(array $params)
    {
        $award = $this->repository->find($params['award_id']);

        if (!$award) {
            self::sendErrorMessage('This award does not exist');
        }

        $updated = Award::publishWinners($award, Carbon::now());
        $saved = $this->repository->update($updated);
        wp_send_json(DomainTransformer::map($saved), 200);
    }


    /**
     * Unpublish winners for award
     *
     * @param array $params
     * @param int   $params['award_id']
     * @return void
     */
    public function unpublishWinners(array $params)
    {
        $award = $this->repository->find($params['award_id']);

        if (!$award) {
            self::sendErrorMessage('This award does not exist');
        }

        $updated = Award::unpublishWinners($award);
        $saved = $this->repository->update($updated);
        wp_send_json(DomainTransformer::map($saved), 200);
    }
}