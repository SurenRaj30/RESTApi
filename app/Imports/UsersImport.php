<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Hash; 

class UsersImport implements WithUpserts,ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'id' => $row['id'],
            'name' => $row['name'],
            'email' =>$row['email'],
        ]);
    }

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
