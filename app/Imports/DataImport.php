<?php

namespace App\Imports;

use App\Models\MedicalData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DataImport implements ToModel, WithHeadingRow //, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new MedicalData([
            'diagnosis_code'     => $row['diagnosis_code'],
            'description'    => $row['description'], 
            'label'    => $row['label'], 
        ]);

    }

     /**
     * Write code on Method
     *
     * @return response()
     */
    // public function rules(): array
    // { 
    //     return [
    //         'diagnosis_code' => 'required',
    //         'description' => 'required',
    //     ];
    // }
}
