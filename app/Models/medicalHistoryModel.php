<?php
namespace App\Models;
use CodeIgniter\Model;

class medicalHistoryModel extends Model{
    protected $table = 'medical_history';
    
    protected $allowedFields = [
        'date',
        'diagnosis',
        'treatment',
        'results',
        'patient_id',
    ];
    
     public function saveMedicalHistory($data)
    {
        
        return $this->insert($data);
    }

    public function getMedicalHistoryByPatientId($patient_id) {
        return $this->where('patient_id', $patient_id)->findAll();
    }

      public function getMedicalHistory($patient_id)
    {
        return $this->where('patient_id', $patient_id)->findAll();
    }
}
    
   