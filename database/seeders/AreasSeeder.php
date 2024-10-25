<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws Exception
     */
    public function run(): void
    {
        try{
            $this->command->info('starting seeder..');
            $row = 1;
            $now = Carbon::now();

            $this->truncateAreasTable();

            $this->command->info('opening areas_italy.csv ..');
            if (($handle = fopen(database_path('seeders/areas_italy.csv'), "r")) !== false) {
                $this->command->info('Start to writing areas table..');

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

                $this->command->info('End to writing.');
                $this->regenerateRegionsAndProvincesTables();
                $this->command->info('Seeder completed');
            }

        }catch(Exception $e){
            $err = 'Error in '.__METHOD__.': '.$e->getMessage();
            Log::error($err);
            throw new Exception($err);
        }
    }

    /**
     * @throws Exception
     */
    private function truncateAreasTable() : void
    {
        try{
            $this->command->info('Truncate areas table..');
            DB::unprepared('SET FOREIGN_KEY_CHECKS = 0;');
            DB::unprepared('TRUNCATE TABLE areas');
            DB::unprepared('SET FOREIGN_KEY_CHECKS = 1;');

        }catch(Exception $e){
            $err = 'Error in '.__METHOD__.': '.$e->getMessage();
            Log::error($err);
            throw new Exception($err);
        }
    }

    /**
     * @throws Exception
     */
    private function regenerateRegionsAndProvincesTables() : void
    {
        try{
            $this->command->info('Regenerate regions and provinces tables.');
            DB::unprepared('SET FOREIGN_KEY_CHECKS = 0;');
            DB::unprepared('TRUNCATE TABLE regions');
            DB::unprepared('INSERT INTO regions (id, name, created_at) (SELECT DISTINCT region_id,region_name,created_at FROM areas)');

            DB::unprepared('TRUNCATE TABLE provinces');
            DB::unprepared('INSERT INTO provinces (id, code, name, created_at) (SELECT DISTINCT province_id,province_code,province_name,created_at FROM areas)');
            DB::unprepared('SET FOREIGN_KEY_CHECKS = 1;');

        }catch(Exception $e){
            $err = 'Error in '.__METHOD__.': '.$e->getMessage();
            Log::error($err);
            throw new Exception($err);
        }
    }
}
