<?php

namespace Database\Seeders;

use App\Models\PaymentMode;
use App\Models\Role;
use App\Models\Status;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


    //ADDING INITIAL ROLE
    Role::factory()->create([
        'name'=>'super_admin',
        'guard_name'=>'web'
    ]);

        //ADDING NEW USER
        $user = User::factory()->create([
                'name'=>'James Ganza',
                'email'=>'james.ganza@gmail.com',
                'password'=> bcrypt('1123581321'),
                'roleId' => 1,
        ]);

        //ADDING STATUS
        Status::factory()->create([
            'name'=>'Processing'
        ]);
        Status::factory()->create([
            'name'=>'Cancelled'
        ]);
        Status::factory()->create([
            'name'=>'Paid'
        ]);

        //ADDING PAYMENT MODES
        PaymentMode::factory()->create([
            'name'=>'Cash',
            'createdBy'=>1
        ]);
        PaymentMode::factory()->create([
            'name'=>'Mobile Money',
            'createdBy'=>1
        ]);
        PaymentMode::factory()->create([
            'name'=>'Bank Debit/Credit Card',
            'createdBy'=>1
        ]);

        //ADDING INITIAL TRANSACTION TYPES
        TransactionType::factory()->create([
            'name'=>'Credit'
        ]);
        TransactionType::factory()->create([
            'name'=>'Debit'
        ]);

        //ADDING TRANSACTIONS
        Transaction::factory()->create([
            'name'=>'Sales',
            'typeId'=>1
        ]);
        Transaction::factory()->create([
            'name'=>'Purchases',
            'typeId'=>2
        ]);




    }
}
