<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        //Generating For Orders
        $numberOfCustomers = Customer::count();
        $user = User::inRandomOrder()
                        ->select('users.*','user_has_counters.counterId')
                        ->join('user_has_counters','user_has_counters.userId','users.id')
                        ->limit(1)
                        ->first();
        $current_time = \Carbon\Carbon::now()->timestamp;

        //Generating for OrderItem


            Order::factory()->count(20000)->create()->each(
                function($order) use ($faker){

                     //Generating for OrderItem
                    $item = OrderItem::factory()->create([
                                            'orderId'=>$order->id,
                                            'productId'=>$faker->numberBetween(3,42),
                                            'quantity'=>$faker->numberBetween(1,5)
                                        ]);
                    $order->amount = $item->quantity*$item->product->price;
                    //$order->OrderItems()->save($items);
                    $order->save();

                    if($order->statusId==3){
                        //Generating for Payments
                        Payment::factory()->create([
                            'orderId'=>$order->id,
                            'transactionId'=>1,
                            'paymentModeId'=>$faker->numberBetween(1,3),
                            'amount'=>$order->amount,
                            'createdBy'=>$order->createdBy,
                            'created_at'=>$order->created_at,
                            //'update_at'=>$order->updated_at
                        ]);
                    }


                }
            );

    }
}
