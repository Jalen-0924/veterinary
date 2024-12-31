<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\vaccineHistoryModel; // Make sure your model name is correct

class VaccineSeeder extends Seeder
{
    public function run()
    {
        $vaccineHistoryModel = new vaccineHistoryModel();

        // Define actual data for seeding
        $data = [
            [
                'vaccine_date' => '2023-01-15',
                'weight' => 10.5,
                'vaccine' => 'Rabies Vaccine',
            ],
            [
                'vaccine_date' => '2023-03-10',
                'weight' => 12.0,
                'vaccine' => 'Distemper Vaccine',
            ],
            [
                'vaccine_date' => '2023-04-22',
                'weight' => 11.5,
                'vaccine' => 'Bordetella Vaccine',
            ],
            [
                'vaccine_date' => '2023-06-05',
                'weight' => 9.0,
                'vaccine' => 'Parvovirus Vaccine',
            ],
            [
                'vaccine_date' => '2023-07-30',
                'weight' => 15.2,
                'vaccine' => 'Canine Influenza Vaccine',
            ],
            [
                'vaccine_date' => '2023-08-15',
                'weight' => 14.0,
                'vaccine' => 'Leptospirosis Vaccine',
            ],
            // Add more records as needed
        ];

        // Insert each item into the vaccines table
        foreach ($data as $item) {
            $vaccineHistoryModel->insert($item);
        }
    }
}
