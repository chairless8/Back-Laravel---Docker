<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\Phone;
use App\Models\Email;
use App\Models\Address;
use Faker\Factory as Faker;

class ContactSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 5000; $i++) {
            $contact = Contact::create([
                'name' => $faker->name,
                'notes' => $faker->sentence,
                'birthday' => $faker->date(),
                'website' => $faker->url,
                'company' => $faker->company,
            ]);

            // Añadir teléfonos
            for ($j = 0; $j < rand(1, 3); $j++) {
                $contact->phones()->create([
                    'phone_number' => $faker->phoneNumber,
                ]);
            }

            // Añadir emails
            for ($j = 0; $j < rand(1, 2); $j++) {
                $contact->emails()->create([
                    'email' => $faker->unique()->safeEmail,
                ]);
            }

            // Añadir direcciones
            for ($j = 0; $j < rand(1, 2); $j++) {
                $contact->addresses()->create([
                    'address_line' => $faker->streetAddress,
                    'city' => $faker->city,
                    'state' => $faker->state,
                    'postal_code' => $faker->postcode,
                    'country' => $faker->country,
                ]);
            }
        }
    }
}

