<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Declaration;
use App\DeclarationCode;
use App\IsolationAddress;
use App\ItineraryCountry;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'phone_number' => $faker->phoneNumber,
        'country_code' => $faker->countryCode,
        'token' => $faker->password(16, 32)
    ];
});

$factory->define(DeclarationCode::class, function (Faker $faker) {
    return [
        'code' => strtoupper(Str::random(6))
    ];
});

$factory->define(Declaration::class, function (Faker $faker) {
    return [
        'name' => $faker->lastName,
        'surname' => $faker->firstName,
        'email' => $faker->email,
        'cnp' => $faker->numberBetween(1111111111111, 2999999999999),
        'birth_date' => $faker->dateTimeBetween('-50 years', '-18 years'),
        'sex' => $faker->regexify('(M|F)'),
        'document_type' => $faker->regexify('(passport|identity_card)'),
        'document_series' => Str::random(16),
        'document_number' => $faker->numberBetween(9999, 99999999),
        'travelling_from_country_code' => $faker->countryCode,
        'travelling_from_city' => $faker->city,
        'travelling_from_date' => $faker->dateTimeBetween('-1 year', '-1 week'),
        'home_country_return_date' => $faker->dateTimeBetween('-1 week', '-1 day'),
        'question_1_answer' => Str::random(rand(32, 256)),
        'question_2_answer' => Str::random(rand(32, 256)),
        'question_3_answer' => Str::random(rand(32, 256)),
        'symptom_fever' => $faker->boolean,
        'symptom_swallow' => $faker->boolean,
        'symptom_breathing' => $faker->boolean,
        'symptom_cough' => $faker->boolean,
        'vehicle_type' => $faker->regexify('(auto|ambulance)'),
        'vehicle_registration_no' => Str::random(8)
    ];
});

$factory->define(ItineraryCountry::class, function (Faker $faker) {
    return [
        'country_code' => $faker->countryCode
    ];
});

$factory->define(IsolationAddress::class, function (Faker $faker) {
    return [
        'city' => $faker->city,
        'county' => $faker->country,
        'city_full_address' => $faker->streetAddress,
        'city_arrival_date' => $faker->dateTimeBetween('-1 week', '-1 day'),
        'city_departure_date' => $faker->dateTimeBetween('-1 day', '+2 days'),
    ];
});
