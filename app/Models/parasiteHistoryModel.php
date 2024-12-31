<?php
namespace App\Models;
use CodeIgniter\Model;

class parasiteHistoryModel extends Model{
    protected $table = 'parasite_history';
    
    protected $allowedFields = [
        'treatment_date',
        'next_date',
        'weight',
        'product',
        'pet_id',
        'patient_id',
    ];
    
     public function saveParasiteHistory($data)
    {
        
        return $this->insert($data);
    }

    public function getParasiteHistoryByPatientId($patient_id) {
        return $this->where('patient_id', $patient_id)->findAll();
    }


     public function getParasiteHistoryByPetId($pet_id) {
        return $this->where('pet_id', $pet_id)->findAll();
    }
}
    
   