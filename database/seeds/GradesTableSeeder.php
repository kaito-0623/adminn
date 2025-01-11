<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 既存のデータを削除
        DB::table('grades')->truncate();

        // 新しいデータを挿入
        DB::table('grades')->insert([
            ['name' => '1年生', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '2年生', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '3年生', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '4年生', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
