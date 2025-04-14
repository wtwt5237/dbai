<?php

namespace App\Http\Controllers;

use Shuchkin\SimpleXLSX;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

//use App\Exports\ExportUsers;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\LazyCollection;
use File;


class DatabaseController
{
    public function database($db_name = 'default')
    {
        if (!Auth::check() and ($db_name == 'scrna')) {
        // if (!Auth::check() and ($db_name == 'scrna' or $db_name == 'receptor')) {
            return redirect('database')->with('authError', 'Oops. You don\'t have the access permission.');
        }

        $tables = Auth::check() ? config('global.db_to_display.user') : config('global.db_to_display.guest');

        $cur_name = $db_name == 'default' ? strtolower($tables[0]) : $db_name;
        $headers = DB::select('show columns from ' . 'dbai_' . $cur_name);
        $dict = DB::select('select * from dictionary');

        return view('database/database', ['db_name' => $cur_name, 'tabs' => $tables, 'headers' => $headers, 'dict' => $dict]);
    }

    public function fetchTable($db_name)
    {
        $db_name = 'dbai_' . $db_name;
        $headers = DB::select('show columns from ' . $db_name);
        $header_arr = [];
        foreach ($headers as $header) {
            array_push($header_arr, array(
                "title" => $header->Field
            ));
        }

        $rows = DB::table($db_name)->select('*')->get();
        $row_arr = [];
        foreach ($rows as $row) {
            $tmp = [];
            $count = 0;
            foreach ($row as $key => $val) {
                if ($count == 0) {
                    $id = $val;
                    $count++;
                }
                if ((str_contains($key, '_data') or str_contains($key, '_File') or $key == 'Data') and !empty($val) and $val != 'NA') {
                    $val = "<a href='" . route("download-file", ["db_name" => "$db_name", "id" => "$id", "f_name" => "$val"]) . "'>" . $val . "</a>";
                }
                $tmp[] = $val;
            }
            $row_arr[] = $tmp;
        }

        return response(array($header_arr, $row_arr));
    }

    public function fileDownload($db_name, $id, $f_name)
    {
        if (str_contains($f_name, $id)) return Storage::download('/download/' . $db_name . '/' . $f_name);
        else return Storage::download('/download/' . $db_name . '/' . $id . '_' . $f_name);
    }

    public function dbDownload($db_version)
    {
        $zip = new \ZipArchive();
        $fileName = 'zipFile.zip';
        if ($zip->open(storage_path('app/data/' . $fileName), \ZipArchive::CREATE) == TRUE) {
            $files = File::files(storage_path('app/data/' . $db_version));
            foreach ($files as $key => $value) {
                $relativeName = basename($value);
                $zip->addFile($value, $relativeName);
            }
            $zip->close();
        }
        return response()->download(storage_path('app/data/' . $fileName));
    }

}
