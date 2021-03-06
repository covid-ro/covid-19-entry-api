<?php

use App\BorderCheckpoint;
use App\Declaration;
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
        if (empty(Declaration::all()->count())) {
            factory(App\User::class, 500)->create()->each(function ($user) { // create User
                try {
                    factory(App\DeclarationCode::class, rand(1, 2))->create()->each(function ($declarationCode) use ($user) { // create DeclarationCode

                        $declarationStatus = Declaration::AVAILABLE_STATUS_LIST[array_rand(Declaration::AVAILABLE_STATUS_LIST)];

                        $declarationData = [
                            'declarationcode_id' => $declarationCode->id,
                            'user_id' => $user->id,
                            'border_checkpoint_id' => BorderCheckpoint::all()->random(),
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
                        )->each(function ($declaration) use ($declarationData) {
                            $declaration->symptoms()->attach(
                                Symptom::all()->random(rand(0, 4))
                            );

                            factory(App\ItineraryCountry::class, rand(1, 4))->create([ // create ItineraryCountry[]
                                'declaration_id' => $declaration->id
                            ]);

                            if (!empty($declarationData['home_isolated'])) {
                                factory(App\IsolationAddress::class,1)->create([
                                    'declaration_id' => $declaration->id
                                ]);
                            }

                            if (1 === rand(1, 3)) {
                                factory(App\DeclarationSignature::class, 1)->create([ // create DeclarationSignature[]
                                    'declaration_id' => $declaration->id
                                ]);
                            }
                        });
                    });
                } catch (\Throwable $throwable) {
                }
            });
        }
    }
}
