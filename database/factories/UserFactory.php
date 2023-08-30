<?php

namespace Database\Factories;

use App\Http\Controllers\Enum\UserTypeEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Pranto Kumar Saha',
            'user_type' => UserTypeEnum::ADMIN,
            'email' => 'officialprantosaha@gmail.com',
            'mobile_number' => '01306357873',
            'email_verified_at' => now(),
            'password' => '$2y$10$i5U9Xkbz1.33QSlopN5dw.mroEv1OavOvBEHHpuRVgJKwAr02aITK', // 123456
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
