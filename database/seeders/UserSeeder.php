<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Helpers\PhoneValid;

class UserSeeder extends Seeder
{
    /**
     * Запустить наполнение базы данных.
     * php artisan db:seed --class=UserSeeder
     */
    public function run(): void
    {

        $downloadsPath = $_SERVER['HOMEDRIVE'] . $_SERVER['HOMEPATH'] . '\\Downloads\\';

        $filename = 'ready.csv';
        $fullPath = $downloadsPath . $filename;

        $this->command->info($fullPath);

        if (file_exists($fullPath)) {
            // Или построчное чтение CSV
            $file = fopen($fullPath, 'r');
            while (($line = fgetcsv($file)) !== false) {

                if ($line[3] != '') {
                    $phone = $line[3];
                } elseif ($line[4] != '') {
                    $phone = $line[4];
                } else {
                    $phone = '';
                }

                $phone = PhoneValid::Clear($phone);

                if ($phone == '') continue;

                //$this->command->info($line[0] . ' - ' .  $line[2] . ' - ' .  $phone);
                DB::beginTransaction();

                try {
                    DB::table('users')->insert([
                        'name' => $line[0],
                        'lawyer' => 1,
                        'city' => 'Архангельская область',
                        'city_id' => 46,
                        'address' => $line[2],
                        'phone' => $phone,
                        'email' => null,
                    ]);
                    DB::commit(); 
                    $this->command->info('Seeder выполнен успешно!' . ' - ' . $line[0]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->command->error('Ошибка в Seeder: ' . $e->getMessage() . ' - ' . $line[0]);
                }
            }

            fclose($file);

        } else {
            $this->command->info('Файл не найден');
        }
    }
}
