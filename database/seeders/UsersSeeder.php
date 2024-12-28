<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Residence;
use App\Models\Building;
use App\Models\Guard;
use App\Models\Resident;
use App\Models\Visitor;
use App\Models\Visit;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Faker\Factory as Faker;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_ES'); // Generar datos en español

        // Crear usuario Admin
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'type' => 'Admin'
        ]);

        // Crear Managers con sus Residences
        $managers = [];
        for ($i = 1; $i <= 16; $i++) {
            $manager = User::create([
                'name' => $faker->name,
                'email' => "manager{$i}@example.com",
                'password' => bcrypt('password'),
                'type' => 'Manager'
            ]);
            $managers[] = $manager;

            $residence = Residence::create([
                'user_id' => $manager->id,
                'name' => $faker->company,
                'address' => $faker->address,
            ]);

            // Crear 3 edificios para cada Residencia
            for ($j = 1; $j <= 3; $j++) {
                Building::create([
                    'residence_id' => $residence->id,
                    'name' => "{$residence->name} {$j}",
                    'floors_number' => 10,
                    'apartments_per_floor' => 4,
                    'active' => true,
                    'information' => "Cualquier información"
                ]);
            }
        }

        // Crear 6 vigilantes distribuidos entre las residencias
        foreach ($managers as $index => $manager) {
            $residenceId = Residence::where('user_id', $manager->id)->first()->id;
            for ($k = 1; $k <=2; $k++) {
                $guard = User::create([
                    'name' => $faker->name,
                    'email' => "guard{$index}{$k}@example.com",
                    'password' => bcrypt('password'),
                    'type' => 'Guard'
                ]);

                Guard::create([
                    'user_id' => $guard->id,
                    'residence_id' => $residenceId,
                    'document' => $faker->dni,
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'phone' => $faker->phoneNumber,
                    'active' => true
                ]);
            }
        }

        // Crear residentes distribuidos entre los edificios
        $buildings = Building::all();
        foreach ($buildings as $index => $building) {
            for ($l = 1; $l <= 5; $l++) {
                $resident = User::create([
                    'name' => $faker->name,
                    'email' => "resident{$index}{$l}@example.com",
                    'password' => bcrypt('password'),
                    'type' => 'Resident'
                ]);

                $residentModel= Resident::create([
                    'user_id' => $resident->id,
                    'building_id' => $building->id,
                    'document' => $faker->dni,
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'apartment' => $faker->numberBetween(1, 20),
                    'phone' => $faker->phoneNumber,
                    'active' => true
                ]);

                // Crear visitantes aleatorios para cada residente (50% probabilidad)
                if (rand(0, 1)) { // 50% de probabilidad
                    $visitor = Visitor::create([
                        'resident_id' => $residentModel->id,
                        'document' => $faker->dni,
                        'first_name' => $faker->firstName,
                        'last_name' => $faker->lastName,
                        'active' => true,
                    ]);

                    // Crear visitas aleatorias para cada visitante
                    for ($v = 1; $v <= rand(1, 3); $v++) {
                        $qrCode = uniqid('visit_', true);
                        $qrImagePath = "qr_images/{$qrCode}.png";

                        // Generar código QR y guardarlo
                        QrCode::format('png')
                            ->size(200)
                            ->generate("QR: {$qrCode}", storage_path("app/public/{$qrImagePath}"));

                        Visit::create([
                            'resident_id' => $residentModel->id,
                            'visitor_id' => $visitor->id,
                            'qr' => $qrImagePath,
                            'remarks' => $faker->sentence,
                            'visit_date' => rand(0, 1) ? $faker->dateTimeThisMonth : null,
                            'expiration_date' => rand(0, 1) ? $faker->dateTimeBetween('+1 month', '+3 months') : null,
                            'cancelled' => false,
                            'visited' => false,
                            'entry_time' => null,
                        ]);
                    }
                }
            }
        }
    }
}
