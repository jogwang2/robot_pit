<?php

namespace App\Models\Imports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\BaseRepository;
use App\Models\Imports\Import;
use App\Models\Imports\RobotsImport;
use App\Models\Robots\Robot;

class ImportRepository extends BaseRepository
{
    /**
     * Performs bulk create robots
     *
     * @param  file  $input
     * @return null
     */
    public function import($input)
    {
        Log::info('Importing robots.');
        $user = Auth::user();

        // TODO: find ways to improve performance when CSV records becomes too much (by the thousands)
        // Maybe break file into chunks and create async jobs
        try {
            // get csv contents
            $data = Excel::toArray(new RobotsImport, $input);

            // check if has data
            if(!$data) {
            	$this->setResponse(false, 'Empty file.', '', 400);
            	return;
            }

	        $this->importToRobotTable($user->id, $data);

            Log::info('Importing robots successful.');
            $this->setResponseNoData(true, 'CSV file imported successfully.');
        } catch (\Exception $ex){
            Log::error('Importing robots failed.', ['error' => $ex->getMessage()]);
            $this->setResponse(false, 'Error encountered when importing file.', $ex->getMessage(), 500);
        }
    }

    /**
     * Assigns user id to each rows and saves them to robots table
     *
     * @param  int   $user_id
     * @param  array $data
     * @return null
     */
    private function importToRobotTable($user_id, $data)
    {
        Log::debug('Importing robots to table.');

    	$uid = array('user_id' => $user_id);
        foreach ($data[0] as $key => $value) {

        	// assign user id
        	$data[0][$key] += $uid;

        	try {
	        	// insert into Robot Table
	        	$robot = Robot::create($data[0][$key]);
                Log::debug('Saved robot to table.', ['name' => $robot->name]);
        	} catch (\Exception $ex){
        		// add error detail but continue on importing
        		$msg = sprintf('Error encountered when inserting robot %s.', $value['name']);
        		$responseData = array(
        			'error' => $msg,
        			'error_detail' => $ex->getMessage()
        		);
                Log::debug('Failed to save robot to table.', ['error' => $ex->getMessage()]);
	            $this->addResponseData($responseData);
	        }
        }
    }
}