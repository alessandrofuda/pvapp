<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $row = 1;
        $now = Carbon::now();

        if (($handle = fopen(database_path('seeders/areas_italy.csv'), "r")) !== false) {
            while (($data = fgetcsv($handle, 0, ",")) !== false) {
                if ($row === 1) {
                    $row++;
                    continue;
                }
                $row++;

                $dbRowData = [
                    'id' => $data[0],
                    'region_id' => $data[1],
                    'region_name' => $data[2],
                    'province_id' => $data[3],
                    'province_code' => $data[4],
                    'province_name' => $data[5],
                    'town' => $data[6],
                    'capoluogo' => $data[7],
                    'created_at' => $now
                ];

                DB::table('areas')->insert($dbRowData);
            }
            fclose($handle);
        }
    }
}
