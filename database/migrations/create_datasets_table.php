<?php

require_once('vendor/autoload.php');

use Shuchkin\SimpleXLSX;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Str;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $file = SimpleXLSX::parse(storage_path('app/data/dictionary.xlsx'));
        $ds_names = $file->sheetNames();
        foreach ($ds_names as $k => $name) {
            // skip 'Explanation' sheet
            if ($k == 0) continue;

            // Dictionary Main
            if ($k == 1) {
                Schema::create('dictionary', function (Blueprint $table) {
                    $table->id();
                    $table->string('variable_name');
                    $table->string('database_name');
                    $table->string('display_search');
                    $table->string('display_initial');
                    $table->string('required');
                });
            } // Other DB sheets
            else {
                $name = 'dbai_' . strtolower(str_replace(' ', '', $name));
                Schema::create($name, function (Blueprint $table) use ($file, $k) {
                    $row_count = 0;
                    foreach ($file->rows($k) as $row) {
                        if ($row_count == 0) {
                            $row_count++;
                            continue;
                        }
                        $table->string($row[0])->nullable();
                        $row_count++;
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $file = SimpleXLSX::parse(storage_path('app/data/dictionary.xlsx'));
        $ds_names = $file->sheetNames();
        foreach ($ds_names as $k => $name) {
            // skip 'Explanation' sheet
            if ($k == 0) continue;

            // Dictionary Main
            if ($k == 1) {
                Schema::dropIfExists('dictionary');
            } // Other DB sheets
            else {
                $name = 'dbai_' . strtolower(str_replace(' ', '', $name));
                Schema::dropIfExists($name);
            }
        }

    }
};
