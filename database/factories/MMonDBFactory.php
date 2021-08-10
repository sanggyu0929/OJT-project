<?php

namespace Database\Factories;

use App\Models\MMonDB;
use Illuminate\Database\Eloquent\Factories\Factory;
/** @var \Illuminate\Database\Eloquent\Factory $factory */

class MMonDBFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MMonDB::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->word(),
            'content' => $this->faker->paragraph(4)
        ];
    }
}
// $factory->define(MMonDB::class, function (Faker $faker) {
//     return [
//         'title' => $this->$faker->word(),
//         'content' => $this->$faker->paragraph(4)
//     ];
// });