<?php
namespace Voting\Controller;

use Voting\Model\Nominations;
use Voting\Model\Vote;

/**
 * @author Gemma Black <gblackuk@gmail.com>
 *
 * Downloads controller mainly for grabbing csvs
 */
class DownloadsController extends BaseController
{

	/**
	 * Gets all nominations for an award (as csv or json)
	 * @param array $params
	 * @return void
	 */
	public function nominations(array $params)
	{
		$nominations = Nominations::with('category')->where('award_id', '=', $params['award_id'])->get();
        $nominations = $nominations->map(function(Nominations $nomination) {
            return [
                'id' => $nomination->id,
                'category' => $nomination->category->name,
                'nominee' => $nomination->nominee_name,
                'reason' => $nomination->nomination_reason,
                'user_ip' => $nomination->user_ip,
                'user_email' => $nomination->user_email,
                'user_name' => $nomination->user_name,
                'date' => $nomination->created_at,
                'award_id' => $nomination->award_id,
                'award' => $nomination->award->title,
                'year' => $nomination->award->year
            ];
        });

		if ($nominations && isset($params['format']) && $params['format'] === 'csv') {
			$this->makeCSV($nominations->toArray());
		}

		wp_send_json($nominations, 200);
    }
    
    /**
     * Gets all votes for an award (as csv or json)
     *
     * @param array $params
     * @return void
     */
    public function votes(array $params)
    {
        $votes = Vote::with('finalist')->where('award_id', '=', $params['award_id'])->get();
        $mapped = $votes->map(function(Vote $vote) {
            return [
                'id' => $vote->id,
                'finalist' => $vote->finalist->name,
                'user_ip' => $vote->user_ip,
                'user_name' => $vote->user_name,
                'user_email' => $vote->user_email,
                'newsletter' => $vote->newsletter,
                'date' => $vote->created_at,
                'award_id' => $vote->award_id,
                'award' => $vote->award->title,
                'year' => $vote->award->year
            ];
        });
        
        if ($votes && isset($params['format']) && $params['format'] === 'csv') {
			$this->makeCSV($mapped->toArray());
		}

		wp_send_json($votes, 200);
    }

    /**
     * Make CSV
     * @todo put into own class
     *
     * @param array $data
     * @return void
     */
    private function makeCSV(array $data)
    {
        header('Content-Type: application/excel');
        header('Content-Disposition: attachment; filename="votes.csv"');
        $file = fopen('php://output', 'w');

        if (count($data) > 0) {
            fputcsv($file, array_keys($data[0]));
        }

        foreach ($data as $dataPoint) {
            $value = $dataPoint;
            fputcsv($file, $dataPoint);
        }

        fclose($file);
        exit;
    }

}
