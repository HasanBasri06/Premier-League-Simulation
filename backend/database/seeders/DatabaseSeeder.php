<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Team;
use App\Models\TeamPower;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('teams')->insert([
            [
                'name' => 'Beşiktaş',
                'image' => asset('storage/assets/team_images/besiktas.png'),
                'created_at' => now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Galatasaray',
                'image' => asset('storage/assets/team_images/galatasaray.png'),
                'created_at' => now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Fenerbahçe',
                'image' => asset('storage/assets/team_images/fenerbahce.png'),
                'created_at' => now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Trabzonspor',
                'image' => asset('storage/assets/team_images/trabzonspor.png'),
                'created_at' => now()->format('Y-m-d H:i:s')
            ],
        ]);
    }
}
