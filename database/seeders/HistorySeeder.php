<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * Update History
         **/
        echo "Start loading Update History...\n";

        if (($handle = fopen(storage_path('app/data/history.csv'), "r")) !== FALSE) {
            $count = 0;
            $headers = [];
            while (($data = fgetcsv($handle)) !== FALSE) {
                if ($count == 0) $headers = $data;
                else {
                    $row = array_combine($headers, $data);
                    DB::table('update_history')->insert($row);
                }
                $count++;
            }
            fclose($handle);
        }
    }
}
