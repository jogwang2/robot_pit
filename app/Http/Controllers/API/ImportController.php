<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Imports\ImportManager;

class ImportController extends BaseController
{
    /**
     * Imports robot via CSV file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $user = $request->user();
        $input = $request->file('file');

        $importtManager = new ImportManager();
        $importtManager->import($user, $input);
        return $this->returnResponse($importtManager);
    }
}