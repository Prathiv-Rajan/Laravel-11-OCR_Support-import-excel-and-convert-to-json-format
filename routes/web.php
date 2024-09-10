<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicalDataController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('datas', [MedicalDataController::class, 'index']);
Route::post('data-import', [MedicalDataController::class, 'import'])->name('data.import');


Route::view('/excel-to-json-datas', 'excel_to_json_data');

Route::post('/get-excel-data-json', [MedicalDataController::class, 'getExcelDataAsJson']);
Route::post('/download-json', [MedicalDataController::class, 'downloadJson']);