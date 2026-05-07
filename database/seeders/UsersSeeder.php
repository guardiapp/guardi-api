<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\Building;
use App\Models\Guard;
use App\Models\Profile;
use App\Models\Residence;
use App\Models\User;
use App\Models\Visit;
use App\Models\Visitor;
use DateTime;
use Illuminate\Database\Seeder;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $firstNames = ['Juan', 'María', 'Carlos', 'Ana', 'Luis', 'Laura', 'Pedro', 'Carmen', 'Jorge', 'Elena', 'Miguel', 'Isabel'];
        $lastNames = ['García', 'Rodríguez', 'López', 'Martínez', 'Sánchez', 'Pérez', 'González', 'Ruiz', 'Ramírez', 'Torres', 'Flores', 'Díaz'];
        $streets = ['Av. Principal', 'Calle Mayor', 'Plaza Centro', 'Paseo del Río', 'Calle Sol', 'Av. Libertad'];

        $randName = function () use ($firstNames, $lastNames): string {
            return $firstNames[array_rand($firstNames)].' '.$lastNames[array_rand($lastNames)].' '.random_int(1, 99);
        };
        $randFirst = fn () => $firstNames[array_rand($firstNames)];
        $randLast = fn () => $lastNames[array_rand($lastNames)];
        $randCompany = fn () => 'Residencial '.$streets[array_rand($streets)].' '.random_int(1, 999);
        $randAddress = fn () => $streets[array_rand($streets)].' '.random_int(1, 200).', Madrid';
        $randDni = fn () => (string) random_int(10000000, 99999999).chr(random_int(65, 90));
        $randPhone = fn () => '+34 6'.str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
        $randSentence = fn () => 'Nota de visita '.random_int(10000, 99999);

        $randomDateThisMonth = function (): DateTime {
            $now = new DateTime('now');
            $start = (clone $now)->modify('first day of this month')->setTime(0, 0, 0);
            $end = (clone $now)->modify('last day of this month')->setTime(23, 59, 59);

            return (new DateTime)->setTimestamp(random_int($start->getTimestamp(), $end->getTimestamp()));
        };

        $randomDateBetweenOneAndThreeMonthsAhead = function (): DateTime {
            $start = (new DateTime)->modify('+1 month');
            $end = (new DateTime)->modify('+3 months');

            return (new DateTime)->setTimestamp(random_int($start->getTimestamp(), $end->getTimestamp()));
        };

        // Crear usuario Admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => 'password',
            'type' => 'Admin',
            'email_verified_at' => now(),
        ]);

        // Crear Managers con sus Residences
        $managers = [];
        $residences = [];
        for ($i = 1; $i <= 16; $i++) {
            $manager = User::create([
                'name' => $randName(),
                'email' => "manager{$i}@example.com",
                'password' => 'password',
                'type' => 'Manager',
                'email_verified_at' => now(),
            ]);
            $managers[] = $manager;

            $residence = Residence::create([
                'name' => $randCompany(),
                'address' => $randAddress(),
            ]);
            $residences[] = $residence;
            $manager->residences()->attach($residence->id, ['state' => 'ACTIVE']);

            // Crear 3 edificios para cada Residencia
            for ($j = 1; $j <= 3; $j++) {
                Building::create([
                    'residence_id' => $residence->id,
                    'name' => "{$residence->name} {$j}",
                    'floors_number' => 10,
                    'apartments_per_floor' => 4,
                    'active' => true,
                    'information' => 'Cualquier información',
                ]);
            }
        }

        // Crear 6 vigilantes distribuidos entre las residencias
        foreach ($managers as $index => $manager) {
            $residenceId = $residences[$index]->id;
            for ($k = 1; $k <= 2; $k++) {
                $guard = User::create([
                    'name' => $randName(),
                    'email' => "guard{$index}{$k}@example.com",
                    'password' => 'password',
                    'type' => 'Guard',
                    'email_verified_at' => now(),
                ]);

                Guard::create([
                    'user_id' => $guard->id,
                    'residence_id' => $residenceId,
                    'document' => $randDni(),
                    'first_name' => $randFirst(),
                    'last_name' => $randLast(),
                    'phone' => $randPhone(),
                    'active' => true,
                ]);
            }
        }

        // Crear apartamentos distribuidos entre los edificios
        $buildings = Building::all();
        $buildingNumber = 0;
        foreach ($buildings as $index => $building) {
            $buildingNumber++;

            for ($l = 1; $l <= 5; $l++) {
                $resident = User::create([
                    'name' => $randName(),
                    'email' => "resident{$index}{$l}@example.com",
                    'password' => 'password',
                    'type' => 'Resident',
                    'email_verified_at' => now(),
                ]);

                Profile::create([
                    'user_id' => $resident->id,
                    'document' => $randDni(),
                    'first_name' => $randFirst(),
                    'last_name' => $randLast(),
                    'phone' => $randPhone(),
                ]);

                $apartment = Apartment::create([
                    'user_id' => $resident->id,
                    'building_id' => $building->id,
                    'identifier' => $buildingNumber.'-'.$l,
                    'active' => true,
                ]);

                // Crear visitantes aleatorios para cada residente (50% probabilidad)
                if (random_int(0, 1)) {
                    $visitor = Visitor::create([
                        'apartment_id' => $apartment->id,
                        'document' => $randDni(),
                        'first_name' => $randFirst(),
                        'last_name' => $randLast(),
                        'active' => true,
                    ]);

                    // Crear visitas aleatorias para cada visitante
                    for ($v = 1; $v <= random_int(1, 3); $v++) {
                        $qrCode = uniqid('visit_', true);
                        $qrImagePath = "qr_images/{$qrCode}.png";

                        QrCode::format('png')
                            ->size(200)
                            ->generate("QR: {$qrCode}", storage_path("app/public/{$qrImagePath}"));

                        $normalVisit = random_int(0, 1);

                        Visit::create([
                            'apartment_id' => $apartment->id,
                            'visitor_id' => $visitor->id,
                            'qr' => $qrImagePath,
                            'remarks' => $randSentence(),
                            'visit_date' => $normalVisit ? $randomDateThisMonth() : null,
                            'expiration_date' => $normalVisit ? null : $randomDateBetweenOneAndThreeMonthsAhead(),
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
