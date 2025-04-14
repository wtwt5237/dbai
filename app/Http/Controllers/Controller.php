<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller
{
    public function index()
    {
        $tables = DB::select('SHOW TABLES');
        $db_size = array();
        foreach ($tables as $table) {
            $cur_name = $table->Tables_in_dbai;
            if (str_contains($cur_name, 'dbai_')) {
                $size = DB::table($cur_name)->count();
                $cur_name = str_replace(['dbai_', '_'], ['', ' '], $cur_name);
                $db_size[$cur_name] = $size;
            }
        };
        return view('home/home', ['db_size' => $db_size]);
    }
}
