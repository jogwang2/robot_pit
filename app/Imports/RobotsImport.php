<?php

namespace App\Imports;

use App\Robot\Robot;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RobotsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Robot([
            'user_id'     => $row['user_id'],
            'name'        => $row['name'],
            'speed'       => $row['speed'], 
            'weight'      => $row['weight'], 
            'power'       => $row['power'], 
        ]);
    }
}
