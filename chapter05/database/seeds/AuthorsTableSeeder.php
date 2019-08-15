<?php

use Illuminate\Database\Seeder;

class AuthorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Authors テーブルにレコードを10件登録する
        $now = \Carbon\Carbon::now();
        for ($i = 1; $i < 10; $i++) {
            $author = [
                'name' => '著者名' . $i,
                'kana' => 'チョシャメイ' . $i,
                'created_at' => $now,
                'updated_at' => $now
            ];
            DB::table('authors')->insert($author);
        }
    }
}
