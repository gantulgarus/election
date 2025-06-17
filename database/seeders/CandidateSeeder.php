<?php

namespace Database\Seeders;

use App\Models\Candidate;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Candidate::create([
                'name' => "Нэр дэвшигч $i",
                'email' => "candidate$i@example.com",
                'phone' => "9900$i$i$i$i",
                'description' => "Тест нэр дэвшигч $i-ийн танилцуулга.",
                'status' => 'approved',
            ]);
        }
    }
}
