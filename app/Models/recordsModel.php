<?php 
namespace App\Models;  
use CodeIgniter\Model;

class recordsModel extends Model {
    protected $table = 'records';
    protected $allowedFields = [

        'patient_id',
    
        'pet_id',
     ];



     public function getUsersByType($type) {
    return $this->where('user_type', $type)->findAll();
}


    public function getRecords($id = false) {
    if ($id === false) {
        $sql = 'SELECT records.id, records.patient_id, user.first_name as first_name, 
                        user.last_name as last_name, pets.name as pet_name 
                 FROM records
                 LEFT JOIN user ON user.id = records.patient_id
                 LEFT JOIN pets ON pets.id = records.pet_id';

        $querys = $this->db->query($sql);
        
        if ($querys === false) { 
            $error = $this->db->error();
            return 'Query error: ' . $error['message']; 
        }

        return $querys->getResultArray(); 
    } else {
        $sql = 'SELECT records.id, records.patient_id, user.first_name as patient_first_name, 
                        user.last_name as patient_last_name, pets.name as pet_name 
                 FROM records
                 LEFT JOIN user ON user.id = records.patient_id
                 LEFT JOIN pets ON pets.id = records.pet_id
                 WHERE records.patient_id = ?';

        $querys = $this->db->query($sql, [$id]); 
        
        if ($querys === false) {
            $error = $this->db->error();
            return 'Query error: ' . $error['message'];
        }

        return $querys->getResultArray();
    }
}

public function getRecordById($pet_id) {
    $sql = 'SELECT records.id, records.patient_id, user.first_name as patient_first_name, 
                   user.last_name as patient_last_name, pets.name as pet_name, 
                   pets.species as pet_species, pets.breed as pet_breed, pets.sex as pet_sex, 
                   pets.birthdate as pet_birthdate, pets.colorm as pet_colorm, 
                   pets.mchip as pet_mchip, pets.rstatus as pet_rstatus, pets.birthdate as pet_birthdate
            FROM records
            LEFT JOIN user ON user.id = records.patient_id
            LEFT JOIN pets ON pets.id = records.pet_id
            WHERE pets.id = ?';

    $query = $this->db->query($sql, [$pet_id]);

    if ($query === false) {
        $error = $this->db->error();
        echo 'SQL Error: ' . $error['message'];  
        return false; 
    }

    return $query->getRowArray();
}






   public function getMedicalHistory($patient_id) {
    try {
        $query = $this->db->table('medical_history')
            ->where('patient_id', $patient_id)
            ->get();
        return $query->getResultArray();
    } catch (\Exception $e) {
        log_message('error', 'Exception: ' . $e->getMessage());
        return false;
    }
}

public function getVaccineHistory($patient_id) {
    try {
        $query = $this->db->table('vaccine_history')
            ->where('patient_id', $patient_id)
            ->get();
        return $query->getResultArray();
    } catch (\Exception $e) {
        log_message('error', 'Exception: ' . $e->getMessage());
        return false;
    }
}
    public function getDewormHistory($patient_id) {
    try {
        $query = $this->db->table('deworm_history')
            ->where('patient_id', $patient_id)
            ->get();
        return $query->getResultArray();
    } catch (\Exception $e) {
        log_message('error', 'Exception: ' . $e->getMessage());
        return false;
    }
}


// public function getRecordById($id) {
//     $query = $this->db->table('medical_history')
//         ->where('id', $id)
//         ->get();

//     return $query->getRowArray(); // Fetch single record as an array
// }


}
