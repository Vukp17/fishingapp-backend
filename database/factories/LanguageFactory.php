<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Language>
 */
class LanguageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $languages = [
            'en' => 'English',
            'sr' => 'Serbian',
            'cr' => 'Croation',
            'sl' => 'Slovenian',
            // Add more languages as needed
        ];

        $languageCode = $this->faker->randomElement(array_keys($languages));
        $languageName = $languages[$languageCode];

        return [
            'id' => $languageCode,
            'name' => $languageName,
        ];
    }
  
}
