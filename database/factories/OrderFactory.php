<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        $requester = User::inRandomOrder()->first()?->id ?? User::factory()->create()->id;

        return [
            'client' => $this->faker->company(),
            'os_reference' => $this->faker->sentence(8),
            'description' => $this->faker->sentence(8),
            'notes' => $this->faker->optional()->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 2000),
            'expected_delivery' => $this->faker->optional()->dateTimeBetween('now', '+30 days'),
            'carrier' => $this->faker->company(),
            'status' => $this->faker->randomElement(['pendente', 'andamento', 'concluido', 'cancelado']),
            'requester_id' => $requester,
        ];
    }
}
