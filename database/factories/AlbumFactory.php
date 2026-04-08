<?php

namespace Database\Factories;

use App\Models\Album;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlbumFactory extends Factory
{
    protected $model = Album::class;

    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'title' => $title,
            'artist' => fake()->name(),
            'description' => fake()->paragraph(),
            'cover_url' => 'https://picsum.photos/seed/'.urlencode($title).'/800/800',
        ];
    }
}
