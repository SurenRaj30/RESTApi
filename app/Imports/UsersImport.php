<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Hash; 

class UsersImport implements WithUpserts, FromCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function model(array $row)
    // {
    //     return new User([
    //         'name' => $row[1],
    //         'email' => $row[2],
    //     ]);
    // }

    public function collection()
    {
        return User::select("id", "name", "email")->get();
    }

    public function headings() :array

    {
        return [

            'ID',
            'Name',
            'Email',
        ];
    }

    public function uniqueBy()
    {
        return 'email';
    }

    
}
