<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GoogleSheetData;

class GoogleSheetController extends Controller
{
    public function index()
    {
        $data = GoogleSheetData::all();
        return view('google_sheet.index', compact('data'));
    }

    public function fetch(Request $request)
    {
        $request->validate([
            'sheet_link' => 'required|url'
        ]);

        // Extract spreadsheetId
        preg_match('/\/d\/([a-zA-Z0-9-_]+)/', $request->sheet_link, $matches);
        $spreadsheetId = $matches[1] ?? null;

        if (!$spreadsheetId) {
            return back()->with('error', 'Invalid Google Sheet link');
        }

        $csvUrl = "https://docs.google.com/spreadsheets/d/{$spreadsheetId}/export?format=csv";
        $csvData = @file_get_contents($csvUrl);

        if ($csvData === false) {
            return back()->with('error', 'Unable to fetch Google Sheet (maybe private?)');
        }

        $rows = array_map('str_getcsv', explode("\n", trim($csvData)));
        $header = array_shift($rows); // column names

        $rowIndex = 2; // start after header
        foreach ($rows as $row) {
            if (empty(array_filter($row))) continue; // skip empty rows
            if (count($row) !== count($header)) continue; // skip malformed rows

            $rowData = array_combine($header, $row);

            GoogleSheetData::updateOrCreate(
                ['sheet_row_number' => $rowIndex],
                ['data' => json_encode($rowData, JSON_UNESCAPED_UNICODE)]
            );

            $rowIndex++;
        }

        return redirect()->route('google.sheet.index')->with('success', 'Data fetched successfully!');
    }

    public function update(Request $request, $id)
    {
        $rowData = $request->input('data'); // single row object now

        if (empty($rowData)) {
            return response()->json(['success' => false, 'message' => 'No data provided']);
        }

        $row = GoogleSheetData::find($id);

        if (!$row) {
            return response()->json(['success' => false, 'message' => 'Row not found']);
        }

        $row->update([
            'data' => json_encode($rowData, JSON_UNESCAPED_UNICODE),
        ]);

        return response()->json([
            'success' => true,
            'row' => [
                'id' => $row->id,
                'sheet_row_number' => $row->sheet_row_number,
                'data' => $rowData
            ]
        ]);
    }



    public function store(Request $request)
    {
        $rows = $request->input('rows', []);

        if (empty($rows)) {
            return response()->json(['success' => false, 'message' => 'No data provided']);
        }

        $maxRow = GoogleSheetData::max('sheet_row_number') ?? 1;
        $inserted = [];

        foreach ($rows as $rowData) {
            $maxRow++;
            $newRow = GoogleSheetData::create([
                'sheet_row_number' => $maxRow,
                'data' => json_encode($rowData, JSON_UNESCAPED_UNICODE),
            ]);

            $inserted[] = [
                'id' => $newRow->id,
                'sheet_row_number' => $newRow->sheet_row_number,
                'data' => $rowData
            ];
        }

        return response()->json(['success' => true, 'rows' => $inserted]);
    }


}
