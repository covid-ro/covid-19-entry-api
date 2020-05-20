<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\BorderCheckpoint;
use App\Declaration;
use App\DeclarationCode;
use App\DeclarationSignature;
use App\IsolationAddress;
use App\ItineraryCountry;
use App\Service\CodeGenerator;
use App\Symptom;
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
        'code' => (new CodeGenerator())->generateDeclarationCode(7)
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
        'document_series' => Str::random(2),
        'document_number' => $faker->numberBetween(9999, 99999999),
        'travelling_from_country_code' => $faker->countryCode,
        'travelling_from_city' => $faker->city,
        'travelling_from_date' => $faker->dateTimeBetween('-1 year', '-1 week'),
        'home_country_return_date' => $faker->dateTimeBetween('-1 week', '-1 day'),
        'travel_route' => $faker->address,
        'q_visited' => $faker->boolean,
        'q_contacted' => $faker->boolean,
        'q_hospitalized' => $faker->boolean,
        'vehicle_type' => $faker->regexify('(auto|ambulance)'),
        'vehicle_registration_no' => Str::random(8),
        'border_crossed_at' => $faker->dateTimeBetween('-1 week', '-1 day'),
        'border_validated_at' => $faker->dateTimeBetween('-1 week', '-1 day'),
        'dsp_validated_at' => $faker->dateTimeBetween('-1 week', '-1 day'),
        'dsp_user_name' => $faker->firstName . ' ' . $faker->lastName
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
        'street' => $faker->streetName,
        'number' => $faker->randomNumber(2),
        'bloc' => $faker->randomNumber(3),
        'entry' => $faker->randomDigitNotNull,
        'apartment' => $faker->randomLetter,
        'city_arrival_date' => $faker->dateTimeBetween('-1 week', '-1 day'),
        'city_departure_date' => $faker->dateTimeBetween('-1 day', '+2 days'),
    ];
});

$factory->define(BorderCheckpoint::class, function (Faker $faker) {
    return [
        'name' => $faker->streetName
    ];
});

$factory->define(DeclarationSignature::class, function (Faker $faker) {
    return [
        'image' => 'data:image/png;base64,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'
    ];
});

$factory->define(Symptom::class, function (Faker $faker) {
    return [
        'name' => $faker->regexify('(fever|swallow|breathing|coughs)')
    ];
});
