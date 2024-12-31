<?php
namespace App\Models;
use CodeIgniter\Model;

class dewormHistoryModel extends Model{
    protected $table = 'deworm_history';
    
    protected $allowedFields = [
        'deworm_date',
        'r_date',
        'weight',
        'product',
        'pet_id',
    ];
    
     public function saveDewormHistory($data)
    {
        
        return $this->insert($data);
    }

    public function getDewormHistoryByPatientId($patient_id) {
        return $this->where('patient_id', $patient_id)->findAll();
    }


      public function getDewormHistoryByPetId($pet_id)
    {
        return $this->where('pet_id', $pet_id)->findAll();
    }
}
    
   