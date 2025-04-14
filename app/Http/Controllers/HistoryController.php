<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoryController
{
    public function fetchHistory(Request $req)
    {
        $data = [];
        $records = DB::table('update_history')->get();
        foreach ($records as $record) {
            array_push($data, array(
                $record->id,
                "<a href='" . route("download-db", ["db_version" => "$record->version"]). "'>" . $record->version . "</a>",
                $record->updated_time,
                $record->editor,
            ));
        }

        return response()->json(["data" => $data]);
    }
}
