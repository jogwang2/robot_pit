<?php

namespace App\Models\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\BaseManager;
use App\Models\Imports\Import;
use App\Models\Imports\RobotsImport;
use App\Models\Robots\Robot;

class ImportManager extends BaseManager
{
    /**
     * Performs bulk process to the database
     *
     * @param  file  $input
     */
    public function import($user_id, $input)
    {
        // TODO: find ways to improve performance when CSV records becomes too much
        try {
            // get csv contents
            $data = Excel::toArray(new RobotsImport, $input);

            // check if has data
            if(!$data) {
            	$this->setResponse(false, 'Empty file.', '', 400);
            	return;
            }

	        $this->importToRobotTable($user_id, $data);

            $this->setResponseNoData(true, 'CSV file imported successfully.');
        } catch (\Exception $ex){
            $this->setResponse(false, 'Error encountered when importing file.', $ex->getMessage(), 500);
        }
    }

    private function importToRobotTable($user_id, $data)
    {
    	$uid = array('user_id' => $user_id);
        foreach ($data[0] as $key => $value) {

        	// assign user id
        	$data[0][$key] += $uid;

        	try {
	        	// insert into Robot Table
	        	Robot::create($data[0][$key]);
        	} catch (\Exception $ex){
        		// add error detail but continue on importing
        		$msg = sprintf('Error encountered when inserting robot %s.', $value['name']);
        		$responseData = array(
        			'error' => $msg,
        			'error_detail' => $ex->getMessage()
        		);
	            $this->addResponseData($responseData);
	        }
        }
    }
}