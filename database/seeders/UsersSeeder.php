<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use App\Models\Residence;
use App\Models\Building;
use App\Models\Guard;
use App\Models\Apartment;
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
            'type' => 'Admin',
            'email_verified_at' => now()
        ]);

        // Crear Managers con sus Residences
        $managers = [];
        for ($i = 1; $i <= 16; $i++) {
            $manager = User::create([
                'name' => $faker->name,
                'email' => "manager{$i}@example.com",
                'password' => bcrypt('password'),
                'type' => 'Manager',
                'email_verified_at' => now()
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
                    'type' => 'Guard',
                    'email_verified_at' => now()
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

        // Crear apartamentos distribuidos entre los edificios
        $buildings = Building::all();
        $buildingNumber = 0;
        foreach ($buildings as $index => $building) {

            $buildingNumber ++;

            for ($l = 1; $l <= 5; $l++) {
                $resident = User::create([
                    'name' => $faker->name,
                    'email' => "resident{$index}{$l}@example.com",
                    'password' => bcrypt('password'),
                    'type' => 'Resident',
                    'email_verified_at' => now()
                ]);

                $residentProfile = Profile::create([
                    'user_id' => $resident->id,
                    'document' => $faker->dni,
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'phone' => $faker->phoneNumber,
                ]);

                $apartment= Apartment::create([
                    'user_id' => $resident->id,
                    'building_id' => $building->id,
                    'identifier' => $buildingNumber. '-'. $l,
                    'active' => true
                ]);

                // Crear visitantes aleatorios para cada residente (50% probabilidad)
                if (rand(0, 1)) { // 50% de probabilidad
                    $visitor = Visitor::create([
                        'apartment_id' => $apartment->id,
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


                        $normalVisit = rand(0, 1);

                        Visit::create([
                            'apartment_id' => $apartment->id,
                            'visitor_id' => $visitor->id,
                            'qr' => $qrImagePath,
                            'remarks' => $faker->sentence,
                            'visit_date' => $normalVisit ? $faker->dateTimeThisMonth : null,
                            'expiration_date' => $normalVisit ? null : $faker->dateTimeBetween('+1 month', '+3 months'),
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
