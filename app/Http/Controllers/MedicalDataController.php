<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\DataExport;
use App\Imports\DataImport;
use App\Models\MedicalData;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Facades\Excel;

class MedicalDataController extends Controller
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function index()
    {
        // $users = MedicalData::all(['diagnosis_code', 'description'])->orderBy(DB::raw('LENGTH(description)'), 'desc');
        $users = DB::table('medical_data')
        ->select('diagnosis_code as Code', 'description as Description', 'label as Label')
        ->orderBy(DB::raw('LENGTH(description)'), 'desc') // 'asc' for ascending, 'desc' for descending
        ->get();
  
        
        // return response()->json($users);
        // return view('users', compact('users'));
        // return view('data');
        return view('db_data', ['diagnoses' => $users]);
    }
          
         
    /**
    * @return \Illuminate\Support\Collection
    */
    public function import(Request $request)
    {
        // Perform the import
        Excel::import(new DataImport, $request->file('file'));

        return back()->with('success', 'Data imported successfully.');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function export() 
    {
        return Excel::download(new DataExport, 'data.xlsx');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    // public function getExcelDataAsJson(Request $request)
    // {
    //     // Validate the uploaded file
    //     $request->validate([
    //         'file' => 'required|file|mimes:xlsx,xls'
    //     ]);

    //     // Read the Excel file
    //     $file = $request->file('file');
    //     $data = Excel::toArray(new class implements ToArray, WithHeadingRow {
    //         public function array(array $array)
    //         {
    //             return $array;
    //         }
    //     }, $file);

    //     // Flatten the array and remove duplicates based on 'description'
    //     $flattenedData = array_merge(...$data);
    //     $uniqueData = collect($flattenedData)
    //         ->map(function ($item) {
    //             return array_filter($item, function ($value) {
    //                 return $value !== null && $value !== '';
    //             });
    //         })
    //         ->unique('description') // Remove duplicates based on 'description'
    //         ->values()
    //         ->toArray();

    //     // Sort by description length
    //     usort($uniqueData, function ($a, $b) {
    //         return strlen($b['description']) - strlen($a['description']);
    //     });

    //     // Calculate total rows
    //     $totalRows = count($uniqueData);

    //     // Create response data
    //     $responseData = [
    //         'totalRows' => $totalRows,
    //         'data' => $uniqueData
    //     ];

    //     // Convert response data to JSON
    //     return response()->json($responseData, 200);
    // }

    /***************************************** */

//     public function getExcelDataAsJson(Request $request)
// {
//     // Validate the uploaded file
//     $request->validate([
//         'file' => 'required|file|mimes:xlsx,xls'
//     ]);

//     // Read the Excel file
//     $file = $request->file('file');
//     $data = Excel::toArray(new class implements ToArray, WithHeadingRow {
//         public function array(array $array)
//         {
//             return $array;
//         }
//     }, $file);

//     // Flatten the array and remove duplicates based on 'description'
//     $flattenedData = array_merge(...$data);
//     $uniqueData = collect($flattenedData)
//         ->map(function ($item) {
//             return array_filter($item, function ($value) {
//                 return $value !== null && $value !== '';
//             });
//         })
//         ->unique('description') // Remove duplicates based on 'description'
//         ->values()
//         ->toArray();

//     // Sort by 'label' and then by description length
//     usort($uniqueData, function ($a, $b) {
//         // Compare by 'label' first
//         $labelComparison = strcmp($a['label'], $b['label']);
//         if ($labelComparison !== 0) {
//             return $labelComparison;
//         }
        
//         // If labels are the same, sort by the length of 'description'
//         return strlen($b['description']) - strlen($a['description']);
//     });

//     // Calculate total rows
//     $totalRows = count($uniqueData);

//     // Create response data
//     $responseData = [
//         'totalRows' => $totalRows,
//         'data' => $uniqueData
//     ];

//     // Convert response data to JSON
//     return response()->json($responseData, 200);
// }

public function getExcelDataAsJson(Request $request)
{
    // Validate the uploaded file
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls'
    ]);

    // Read the Excel file
    $file = $request->file('file');
    $data = Excel::toArray(new class implements ToArray, WithHeadingRow {
        public function array(array $array)
        {
            return $array;
        }
    }, $file);

    // Flatten the array and remove duplicates based on 'description'
    $flattenedData = array_merge(...$data);
    $uniqueData = collect($flattenedData)
        ->map(function ($item) {
            return array_filter($item, function ($value) {
                return $value !== null && $value !== '';
            });
        })
        ->unique('description') // Remove duplicates based on 'description'
        ->values()
        ->toArray();

    // Define custom sorting
    usort($uniqueData, function ($a, $b) {
        // Define priority for labels: 'title' should come before 'diagnosis'
        $labelPriority = [
            'title' => 1,
            'diagnosis' => 2,
        ];

        // Compare by 'label' based on priority
        $labelComparison = $labelPriority[$a['label']] <=> $labelPriority[$b['label']];
        if ($labelComparison !== 0) {
            return $labelComparison;
        }

        // If labels are the same, sort by the length of 'description'
        return strlen($b['description']) - strlen($a['description']);
    });

    // Calculate total rows
    $totalRows = count($uniqueData);

    // Create response data
    $responseData = [
        'totalRows' => $totalRows,
        'data' => $uniqueData
    ];

    // Convert response data to JSON
    return response()->json($responseData, 200);
}


    /***************************************** */


    /**
    * @return \Illuminate\Support\Collection
    */
    public function downloadJson(Request $request)
    {
        // Retrieve and decode JSON data from request
        $jsonData = $request->input('json_data');
        $data = json_decode($jsonData, true);
        
        // Check if data is decoded correctly
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON data'], 400);
        }
        
        // Transform the data to match the desired structure
        $formattedData = array_map(function($item) {
            return [
                'Code' => $item['diagnosis_code'] ?? 'N/A',
                'Description' => $item['description'] ?? 'N/A',
                'Label' => $item['label'] ?? 'N/A'
            ];
        }, $data['data'] ?? []);
        
       if($request->pretty_print == 'on'){
           
           // Pretty print JSON data
           $prettyJsonData = json_encode($formattedData, JSON_PRETTY_PRINT);
       } else {

           $prettyJsonData = json_encode($formattedData);
        }
        
        return response()->stream(function() use ($prettyJsonData) {
            echo $prettyJsonData;
        }, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="data.json"',
        ]);
    }
    
}
