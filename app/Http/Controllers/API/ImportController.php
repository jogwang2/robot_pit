<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Imports\ImportRepository;

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
        $input = $request->file('file');

        $importtRepository = new ImportRepository();
        $importtRepository->import($input);
        return $this->returnResponse($importtRepository);
    }
}