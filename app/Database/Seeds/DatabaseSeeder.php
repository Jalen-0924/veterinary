<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder; // Ensure this uses CodeIgniter's Seeder

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Call your MedicationSeeder here
        $this->call(MedicationSeeder::class);
    }
}
