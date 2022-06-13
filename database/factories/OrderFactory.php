<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $numberOfCustomers = Customer::count();
        $user = User::inRandomOrder()
                        ->select('users.*','user_has_counters.counterId')
                        ->join('user_has_counters','user_has_counters.userId','users.id')
                        ->limit(1)
                        ->first();
        //$user = array_rand($users,1);
        $current_time = \Carbon\Carbon::now()->timestamp;

        return [
        'customerId'=>$this->faker->numberBetween(1,$numberOfCustomers),
        'createdBy'=>$user->id,
        'statusId'=>$this->faker->numberBetween(3,4),
        'orderReferenceNumber'=>'ORN'.$current_time,
        'counterId'=>$user->counterId,
        'created_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }
}
