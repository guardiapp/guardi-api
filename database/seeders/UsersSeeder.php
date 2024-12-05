<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Residence;
use App\Models\Building;
use App\Models\Guard;
use App\Models\Resident;
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
            'password' => bcrypt('password123'),
            'type' => 'Admin'
        ]);

        // Crear 3 Managers con sus Residences
        $managers = [];
        for ($i = 1; $i <= 3; $i++) {
            $manager = User::create([
                'name' => $faker->name,
                'email' => "manager{$i}@example.com",
                'password' => bcrypt('password123'),
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
                    'name' => "Edificio {$j}",
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
                    'password' => bcrypt('password123'),
                    'type' => 'Guard'
                ]);

                Guard::create([
                    'user_id' => $guard->id,
                    'residence_id' => $residenceId,
                    'document' => $faker->dni,
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                ]);
            }
        }

        // Crear 30 residentes distribuidos entre los edificios
        $buildings = Building::all();
        foreach ($buildings as $index => $building) {
            for ($l = 1; $l <= 5; $l++) {
                $resident = User::create([
                    'name' => $faker->name,
                    'email' => "resident{$index}{$l}@example.com",
                    'password' => bcrypt('password123'),
                    'type' => 'Resident'
                ]);

                Resident::create([
                    'user_id' => $resident->id,
                    'building_id' => $building->id,
                    'document' => $faker->dni,
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'apartment' => $faker->numberBetween(1, 20),
                ]);
            }
        }
    }
}
