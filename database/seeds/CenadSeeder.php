<?php

use App\Declaration;
use App\Symptom;
use Illuminate\Database\Seeder;

/**
 * Class CenadSeeder
 */
class CenadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 50)->create()->each(function ($user) { // create User
            try {
                factory(App\DeclarationCode::class, rand(1, 2))->create()->each(function ($declarationCode) use ($user) { // create DeclarationCode

                    $declarationStatus = Declaration::STATUS_BORDER_VALIDATED;

                    $dspMeasures = [null, 'hospital', 'quarantine', 'isolation'];

                    $declarationData = [
                        'declarationcode_id' => $declarationCode->id,
                        'user_id' => $user->id,
                        'border_checkpoint_id' => 49, // Cenad
                        'dsp_measure' => $dspMeasures[array_rand($dspMeasures)],
                        'status' => $declarationStatus
                    ];

                    /**
                     * Case 1: Declaration status is Phone Validated
                     */
                    if (Declaration::STATUS_PHONE_VALIDATED === $declarationStatus) {
                        $declarationData['border_crossed_at'] = null;
                        $declarationData['border_validated_at'] = null;
                        $declarationData['dsp_validated_at'] = null;
                        $declarationData['dsp_user_name'] = null;
                    }

                    /**
                     * Case 2: Declaration status is Border Invalid
                     */
                    if (Declaration::STATUS_PHONE_VALIDATED === $declarationStatus) {
                        $declarationData['border_crossed_at'] = null;
                        $declarationData['border_validated_at'] = null;
                        $declarationData['dsp_validated_at'] = null;
                        $declarationData['dsp_user_name'] = null;
                    }

                    /**
                     * Case 3: Declaration status is Border Validated
                     */
                    if (Declaration::STATUS_BORDER_VALIDATED === $declarationStatus) {
                        $declarationData['dsp_validated_at'] = null;
                        $declarationData['dsp_user_name'] = null;
                    }

                    /**
                     * Case 2: Declaration status is DSP Validated
                     */
                    if (Declaration::STATUS_DSP_VALIDATED === $declarationStatus) {

                    }

                    factory(App\Declaration::class, 1)->create( // create Declaration[]
                        $declarationData
                    )->each(function ($declaration) {
                        $declaration->symptoms()->attach(
                            Symptom::all()->random(rand(0, 4))
                        );

                        factory(App\ItineraryCountry::class, rand(1, 4))->create([ // create ItineraryCountry[]
                            'declaration_id' => $declaration->id
                        ]);

                        factory(App\IsolationAddress::class, rand(1, 3))->create([ // create IsolationCountry[]
                            'declaration_id' => $declaration->id
                        ]);

                        if (1 === rand(1, 3)) {
                            factory(App\DeclarationSignature::class, 1)->create([ // create DeclarationSignature[]
                                'declaration_id' => $declaration->id
                            ]);
                        }
                    });
                });
            } catch (\Throwable $throwable) {}
        });
    }
}
