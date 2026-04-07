<?php

namespace Database\Factories;

use App\Models\Album;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlbumFactory extends Factory
{
    protected $model = Album::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'artist' => fake()->name(),
            'description' => fake()->paragraph(),
            'cover_url' => fake()->imageUrl(400, 400, 'music', true),
        ];
    }
}
