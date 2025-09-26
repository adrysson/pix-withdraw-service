<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;
use Hyperf\DbConnection\Db;
use Ramsey\Uuid\Uuid;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::table('account')->insert([
            [
                'id' => Uuid::uuid4()->toString(),
                'name' => 'John Doe',
                'balance' => 1000.00,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => Uuid::uuid4()->toString(),
                'name' => 'Jane Smith',
                'balance' => 500.00,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
