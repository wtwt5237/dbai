<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Shuchkin\SimpleXLSX;
use Carbon\Carbon;

class DatasetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /***********************
         *** Get the parameters from command
         **********************/
        $folder_name = env('FOLDER_NAME', 1);

        echo "=== Start data seeding ===\n";
        $start = microtime(true);

        /**
         * Dictionary
         **/
        echo "Start loading Dictionary...\n";

        $dict = [];
        $file = SimpleXLSX::parse(storage_path('app/data/dictionary.xlsx'));
        $sheet_name = $file->sheetNames();
        foreach ($sheet_name as $k => $name) {
            // dictionary main table
            if ($k == 1) {
                foreach ($file->rows($k) as $i => $row) {
                    // header
                    if ($i == 0) {
                        $headers = $row;
                        continue;
                    }
                    $data = array_combine($headers, $row);
                    DB::table('dictionary')->insert($data);

                    // $dict[DB name][col name] = 'Required' or 'Optional'
                    $dict[$row[1]][$row[0]] = $row[4];
                }
                break;
            }
        }

        /**
         * pre-defined error messages
         **/
        $errors = [
            'ERROR: Col name [%s] not exsit',
            'ERROR: Required value not found [%s][%u]',
            'ERROR: ID not found [%s][%u]',
            'ERROR: Duplicated ID [%s][%u]',
            'ERROR: Invalid format [%s][%u]',
        ];

        /**
         * Individual
         **/
        echo "Start loading Individual...\n";
        if (($handle = fopen(storage_path('app/data/' . $folder_name . '/Individual.csv'), "r")) !== FALSE) {
            $count = 0;
            $headers = [];
            while (($data = fgetcsv($handle)) !== FALSE) {
                if ($count == 0) {
                    $headers = $data;
                    foreach ($headers as $header) {
                        if (!array_key_exists($header, $dict['Individual'])) dd(sprintf($errors[0], $header));
                    }
                } else {
                    $row = array_combine($headers, $data);
                    foreach ($row as $k => $v) {
                        $v = escape($v);
                        if ($dict['Individual'][$k] == 'Required' and empty($v)) dd(sprintf($errors[1], $k, $count + 1));
                        // check format and if duplicated records exist
                        if ($k == 'ID_Individual') {
                            if (DB::table('dbai_individual')->where($k, $v)->exists()) dd(sprintf($errors[3], $k, $count + 1));
                            if (!preg_match('/^Individual_[A-Za-z0-9].*$/i', $v)) dd(sprintf($errors[4], $k, $count + 1));
                        }
                        if ($k == 'Death_status' or $k == 'Relapse_status') {
                            if (!empty($v) and !preg_match('/^(Yes|No)$/i', $v)) dd(sprintf($errors[4], $k, $count + 1));
                        }
                    }
                    DB::table('dbai_individual')->insert($row);
                }
                $count++;
            }
            fclose($handle);
        }

        /**
         * Sample
         **/
        echo "Start loading Sample...\n";
        if (($handle = fopen(storage_path('app/data/' . $folder_name . '/Sample.csv'), "r")) !== FALSE) {
            $count = 0;
            $headers = [];
            while (($data = fgetcsv($handle)) !== FALSE) {
                if ($count == 0) {
                    $headers = $data;
                    foreach ($headers as $header) {
                        if (!array_key_exists($header, $dict['Sample'])) dd(sprintf($errors[0], $header));
                    }
                } else {
                    $row = array_combine($headers, $data);
                    foreach ($row as $k => $v) {
                        $v = escape($v);
                        if ($dict['Sample'][$k] == 'Required' and empty($v)) dd(sprintf($errors[1], $k, $count + 1));
                        // check format and if duplicated records exist
                        if ($k == 'ID_Sample') {
                            if (DB::table('dbai_sample')->where($k, $v)->exists()) dd(sprintf($errors[3], $k, $count + 1));
                            if (!preg_match('/^Sample_[A-Za-z0-9].*$/i', $v)) dd(sprintf($errors[4], $k, $count + 1));
                        }
                        if ($k == 'ID_Individual') {
                            if (DB::table('dbai_individual')->where($k, $v)->doesntExist()) dd(sprintf($errors[2], $k, $count + 1));
                            if (!preg_match('/^Individual_[A-Za-z0-9].*$/i', $v)) dd(sprintf($errors[4], $k, $count + 1));
                        }
                        //                        if ($k == 'Type') {
                        //                            if (!preg_match('(Tumor|Metastasis|Adjacent|Blood|Other)', $v)) dd(sprintf($errors[4], $k, $count + 1));
                        //                        }
                        if (str_contains($k, 'HLA') and !empty($v)) {
                            if (!str_starts_with($v, $k)) dd(sprintf($errors[4], $k, $count + 1));
                        }
                        if (str_contains($k, 'KIR') and !empty($v)) {
                            if (!preg_match('/^(Yes|No)_(Kpi|PING|Experiment)$/i', $v)) dd(sprintf($errors[4], $k, $count + 1));
                        }
                    }
                    DB::table('dbai_sample')->insert($row);
                }
                $count++;
            }
            fclose($handle);
        }

        /**
         * CyTOF initial
         **/
        echo "Start loading CyTOF initial...\n";
        if (($handle = fopen(storage_path('app/data/' . $folder_name . '/CyTOF_init.csv'), "r")) !== FALSE) {
            $count = 0;
            $headers = [];
            while (($data = fgetcsv($handle)) !== FALSE) {
                if ($count == 0) {
                    $headers = $data;
                    foreach ($headers as $header) {
                        if (!array_key_exists($header, $dict['CyTOF'])) dd(sprintf($errors[0], $header));
                    }
                } else {
                    $row = array_combine($headers, $data);
                    foreach ($row as $k => $v) {
                        $v = escape($v);
                        if ($dict['CyTOF'][$k] == 'Required' && empty($v)) dd(sprintf($errors[1], $k, $count + 1));
                        // check format and if duplicated records exist
                        if ($k == 'ID_CyTOF') {
                            if (DB::table('dbai_cytof')->where($k, $v)->exists()) dd(sprintf($errors[3], $k, $count + 1));
                            if (!str_starts_with($v, 'CyTOF_')) dd(sprintf($errors[4], $k, $count + 1));
                        }
                        if ($k == 'ID_Individual') {
                            if (!empty($v) && !str_starts_with($v, 'Individual_')) dd(sprintf($errors[4], $k, $count + 1));
                        }
                        if ($k == 'ID_Sample') {
                            if (!empty($v) && !str_starts_with($v, 'Sample_')) dd(sprintf($errors[4], $k, $count + 1));
                        }
                        if ($k == 'FCS_File') {
                            if (explode('.', $v)[1] != 'fcs') dd(sprintf($errors[4], $k, $count + 1));
                        }
                        if ($k == 'Other_data') {
                            if (!empty($v) and explode('.', $v)[1] != 'zip') dd(sprintf($errors[4], $k, $count + 1));
                        }
                    }
                    DB::table('dbai_cytof')->insert($row);
                }
                $count++;
            }
            fclose($handle);
        }

        /**
         * CyTOF
         **/
        echo "Start loading CyTOF...\n";
        if (($handle = fopen(storage_path('app/data/' . $folder_name . '/CyTOF.csv'), "r")) !== FALSE) {
            $count = 0;
            $headers = [];
            while (($data = fgetcsv($handle)) !== FALSE) {
                if ($count == 0) {
                    $headers = $data;
                    foreach ($headers as $header) {
                        if (!array_key_exists($header, $dict['CyTOF'])) dd(sprintf($errors[0], $header));
                    }
                } else {
                    $row = array_combine($headers, $data);
                    foreach ($row as $k => $v) {
                        $v = escape($v);
                        if ($dict['CyTOF'][$k] == 'Required' && empty($v)) dd(sprintf($errors[1], $k, $count + 1));
                        // check format and if duplicated records exist
                        if ($k == 'ID_CyTOF') {
                            if (DB::table('dbai_cytof')->where($k, $v)->exists()) dd(sprintf($errors[3], $k, $count + 1));
                            if (!str_starts_with($v, 'CyTOF_')) dd(sprintf($errors[4], $k, $count + 1));
                        }
                        if ($k == 'ID_Individual') {
                            if (DB::table('dbai_individual')->where($k, $v)->doesntExist() && !empty($v)) dd(sprintf($errors[2], $k, $count + 1));
                            if (!empty($v) && !str_starts_with($v, 'Individual_')) dd(sprintf($errors[4], $k, $count + 1));
                        }
                        if ($k == 'ID_Sample') {
                            if (DB::table('dbai_sample')->where($k, $v)->doesntExist() && !empty($v)) dd(sprintf($errors[2], $k, $count + 1));
                            if (!empty($v) && !str_starts_with($v, 'Sample_')) dd(sprintf($errors[4], $k, $count + 1));
                        }
                        if ($k == 'FCS_File') {
                            if (explode('.', $v)[1] != 'fcs') dd(sprintf($errors[4], $k, $count + 1));
                        }
                        if ($k == 'Other_data') {
                            if (!empty($v) and explode('.', $v)[1] != 'zip') dd(sprintf($errors[4], $k, $count + 1));
                        }
                    }
                    DB::table('dbai_cytof')->insert($row);
                }
                $count++;
            }
            fclose($handle);
        }

        /**
         * Receptor
         **/
        echo "Start loading Receptor...\n";
        if (($handle = fopen(storage_path('app/data/' . $folder_name . '/Receptor.csv'), "r")) !== FALSE) {
            $count = 0;
            $headers = [];
            while (($data = fgetcsv($handle)) !== FALSE) {
                if ($count == 0) {
                    $headers = $data;
                    foreach ($headers as $header) {
                        if (!array_key_exists($header, $dict['Receptor'])) dd(sprintf($errors[0], $header));
                    }
                } else {
                    $row = array_combine($headers, $data);
                    foreach ($row as $k => $v) {
                        $v = escape($v);
                        if ($dict['Receptor'][$k] == 'Required' and empty($v)) dd(sprintf($errors[1], $k, $count + 1));
                        // check format and if duplicated records exist
                        if ($k == 'ID_Receptor') {
                            if (DB::table('dbai_receptor')->where($k, $v)->exists()) dd(sprintf($errors[3], $k, $count + 1));
                            if (!preg_match('/^Receptor_[A-Za-z0-9].*$/i', $v)) dd(sprintf($errors[4], $k, $count + 1));
                        }
                        if ($k == 'ID_Individual') {
                            if (DB::table('dbai_individual')->where($k, $v)->doesntExist()) dd(sprintf($errors[2], $k, $count + 1));
                            if (!preg_match('/^Individual_[A-Za-z0-9].*$/i', $v)) dd(sprintf($errors[4], $k, $count + 1));
                        }
                        if ($k == 'ID_Sample') {
                            if (DB::table('dbai_sample')->where($k, $v)->doesntExist() && !empty($v)) dd(sprintf($errors[2], $k, $count + 1));
                            if (!empty($v) && !str_starts_with($v, 'Sample_')) dd(sprintf($errors[4], $k, $count + 1));
                        }
                        if ($k == 'Receptor' and !empty($v)) {
                            $receptor_arr = ["TRA", "TRB", "IGH", "IGL", "IGK", "TRG", "TRD", "NA", "BCR"];
                            $values = explode(',', $v);
                            foreach ($values as $value) {
                                if (!in_array(escape($value), $receptor_arr)) dd(sprintf($errors[4], $k, $count + 1));
                            }
                        }
                    }
                    DB::table('dbai_receptor')->insert($row);
                }
                $count++;
            }
            fclose($handle);
        }

        /**
         * T Interaction
         **/
        echo "Start loading T Interaction...\n";
        if (($handle = fopen(storage_path('app/data/' . $folder_name . '/T_interaction.csv'), "r")) !== FALSE) {
            $count = 0;
            $headers = [];
            while (($data = fgetcsv($handle)) !== FALSE) {
                if ($count == 0) {
                    $headers = $data;
                    foreach ($headers as $header) {
                        if (!array_key_exists($header, $dict['Tinteraction'])) dd(sprintf($errors[0], $header));
                    }
                } else {
                    $row = array_combine($headers, $data);
                    foreach ($row as $k => $v) {
                        $v = escape($v);
                        if ($dict['Tinteraction'][$k] == 'Required' and empty($v)) dd(sprintf($errors[1], $k, $count + 1));
                        // check format and if duplicated records exist
                        if ($k == 'ID_Tinteraction') {
                            if (DB::table('dbai_t_interaction')->where($k, $v)->exists()) dd(sprintf($errors[3], $k, $count + 1));
                            if (!preg_match('/^Tinteraction_[A-Za-z0-9].*$/i', $v)) dd(sprintf($errors[4], $k, $count + 1));
                        }
                        if ($k == 'Interaction') {
                            if (!preg_match('/^(Yes|No)$/i', $v)) dd(sprintf($errors[4], $k, $count + 1));
                        }
                        if ($k == 'Va') {
                            if (!preg_match('/^TRAV.*$/i', $v)) dd(sprintf($errors[4], $k, $count + 1));
                        }
                        if ($k == 'Vb') {
                            if (!preg_match('/^TRBV.*$/i', $v)) dd(sprintf($errors[4], $k, $count + 1));
                        }
                        if ($k == 'CDR3a' or $k == 'CDR3b' or $k == 'Epitope') {
                            if (!preg_match('/^[A-Za-z]*$/i', $v)) dd(sprintf($errors[4], $k, $count + 1));
                        }
                    }
                    DB::table('dbai_t_interaction')->insert($row);
                }
                $count++;
            }
            fclose($handle);
        }

        /**
         * B Interaction
         **/
        echo "Start loading B Interaction...\n";
        if (($handle = fopen(storage_path('app/data/' . $folder_name . '/B_interaction.csv'), "r")) !== FALSE) {
            $count = 0;
            $headers = [];
            while (($data = fgetcsv($handle)) !== FALSE) {
                if ($count == 0) {
                    $headers = $data;
                    foreach ($headers as $header) {
                        if (!array_key_exists($header, $dict['Binteraction'])) dd(sprintf($errors[0], $header));
                    }
                } else {
                    $row = array_combine($headers, $data);
                    foreach ($row as $k => $v) {
                        $v = escape($v);
                        if ($dict['Binteraction'][$k] == 'Required' and empty($v)) dd(sprintf($errors[1], $k, $count + 1));
                        // check format and if duplicated records exist
                        if ($k == 'ID_Binteraction') {
                            if (DB::table('dbai_b_interaction')->where($k, $v)->exists()) dd(sprintf($errors[3], $k, $count + 1));
                            if (!preg_match('/^Binteraction_[A-Za-z0-9].*$/i', $v)) dd(sprintf($errors[4], $k, $count + 1));
                        }
                        if ($k == 'Interaction') {
                            if (!preg_match('/^(Yes|No)$/i', $v)) dd(sprintf($errors[4], $k, $count + 1));
                        }
                        if ($k == 'Vh' or $k == 'CDR3h' or $k == 'Antigen' or (($k == 'Vl' or $k == 'CDR3l' or $k == 'Vk' or $k == 'CDR3k') and !empty($v))) {
                            if (!preg_match('/^[A-Za-z]*$/i', $v)) dd(sprintf($errors[4], $k, $count + 1));
                        }
                        if ($k == 'Class') {
                            $class_arr = ["M", "D", "G1", "G2", "G3", "G4", "A1", "A2", "E"];
                            if (!in_array($v, $class_arr)) dd(sprintf($errors[4], $k, $count + 1));
                        }
                    }
                    DB::table('dbai_b_interaction')->insert($row);
                }
                $count++;
            }
            fclose($handle);
        }

        $end = microtime(true);
        $executionTime = $end - $start;

        echo "=== Successfully Seeding! ===\n";
        echo "=== Data seeding time: " . round($executionTime, 2) . " seconds ===\n";
    }
}
