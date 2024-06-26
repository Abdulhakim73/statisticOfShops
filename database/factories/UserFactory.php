<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{

    public function definition(): array
    {
        $phone = '998';
    for ($i = 0; $i < 9; $i++) {
        $phone .= mt_rand(0, 9); 
    }
        return [
            'name' => fake()->name(),
            'role_id' => 2,
            'phone' => $phone,
            'status' => 'active',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
            'remember_token' => Str::random(10),
        ];
        
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
