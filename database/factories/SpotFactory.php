<?php

namespace Database\Factories;
use App\Models\Spot;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Spot>
 */
class SpotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' =>User::factory(),
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'lat' => $this->faker->latitude(),
            'lng' => $this->faker->longitude(),
            'image_id' => $this->faker->imageUrl(), //represnt images id on server
        ];


    }
}
