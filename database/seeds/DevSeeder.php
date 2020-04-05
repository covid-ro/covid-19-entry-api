<?php

use App\BorderCheckpoint;
use App\Symptom;
use Illuminate\Database\Seeder;

/**
 * Class DevSeeder
 */
class DevSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 1000)->create()->each(function ($user) { // create User
            factory(App\DeclarationCode::class, 1)->create()->each(function ($declarationCode) use ($user) { // create DeclarationCode
                factory(App\Declaration::class, rand(1, 2))->create([ // create Declaration[]
                    'declarationcode_id' => $declarationCode->id,
                    'user_id' => $user->id,
                    'border_checkpoint_id' => BorderCheckpoint::all()->random()
                ])->each(function ($declaration) {
                    $declaration->symptoms()->attach(
                        Symptom::all()->random(rand(0,4))
                    );

                    factory(App\ItineraryCountry::class, rand(1, 4))->create([ // create ItineraryCountry[]
                        'declaration_id' => $declaration->id
                    ]);

                    factory(App\IsolationAddress::class, rand(1, 3))->create([ // create IsolationCountry[]
                        'declaration_id' => $declaration->id
                    ]);

                    factory(App\DeclarationSignature::class, 1)->create([ // create DeclarationSignature[]
                        'declaration_id' => $declaration->id
                    ]);
                });
            });
        });
    }
}
