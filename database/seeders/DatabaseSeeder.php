<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin user ──────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // ── Default categories ─────────────────────────
        $categories = [
            ['name' => 'Nhẫn', 'description' => 'Các loại nhẫn vàng, bạc, kim cương'],
            ['name' => 'Dây chuyền', 'description' => 'Dây chuyền và mặt dây chuyền'],
            ['name' => 'Vòng tay', 'description' => 'Vòng tay và lắc tay'],
            ['name' => 'Bông tai', 'description' => 'Bông tai và khuyên tai'],
            ['name' => 'Lắc chân', 'description' => 'Lắc chân và vòng chân'],
            ['name' => 'Bộ trang sức', 'description' => 'Bộ trang sức đồng bộ'],
            ['name' => 'Trang sức vàng', 'description' => 'Trang sức vàng 18K, 24K'],
            ['name' => 'Trang sức bạc', 'description' => 'Trang sức bạc 925'],
            ['name' => 'Trang sức kim cương', 'description' => 'Trang sức gắn kim cương'],
            ['name' => 'Quà tặng', 'description' => 'Trang sức làm quà tặng'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
