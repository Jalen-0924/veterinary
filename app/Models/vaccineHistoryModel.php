<?php
namespace App\Models;
use CodeIgniter\Model;

class vaccineHistoryModel extends Model{
    protected $table = 'vaccine_history';
    
    protected $allowedFields = [
        'vaccine_date',
        'next_date',
        'weight',
        'vaccine',
        'pet_id',
        'patient_id',
    ];
    
     public function saveVaccineHistory($data)
    {
        
        return $this->insert($data);
    }

    public function getVaccineHistoryByPatientId($patient_id) {
        return $this->where('patient_id', $patient_id)->findAll();
    }


     public function getVaccineHistoryByPetId($pet_id) {
        return $this->where('pet_id', $pet_id)->findAll();
    }
}
    
   