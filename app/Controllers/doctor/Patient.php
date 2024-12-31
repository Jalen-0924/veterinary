<?php

namespace App\Controllers\doctor;
use App\Controllers\BaseController;
use App\Models\userModel;
use App\Models\petModel;
use App\Models\recordsModel;

class Patient extends BaseController
{
    public function index()
    {

		return $this->render_template('doctor/patient/add_new');

    }
     public function view_all()
    {
         
         $session = session();
        $Model = new userModel();
        $data['doctors'] = $Model->getUsersByType('patient');
		return $this->render_template('doctor/patient/view_all',$data);
		

    }
    public function deleted($id = null){
        $session = session();
        $Model = new userModel();
        $petModel = new petModel();
        
        $deleted = $Model->where('id', $id)->delete();
        $deleted_pet = $petModel->where('owner_id', $id)->delete();
        
        if($deleted){
                   $session->setFlashdata('success', 'Successfully Deleted');
                   
                               return redirect()->to('doctor/patient/all');
                   
                    }
                    else{
                         $session->setFlashdata('Error', 'Not Deleted');
                        
                               return redirect()->to('doctor/patient/all');
                        
                    }
        
    }
    
    
    
    public function view_all_patient($id)
    {
         
        $session = session();
        $Model = new petModel();
        $data['pets'] = $Model->getPetsByOwner($id);
       	$Model = new userModel();
        $data['petowner'] = $Model->getUsers($id);
		return $this->render_template('doctor/patient/pet/view_all',$data);
		

    }
    
    
    public function add_pet($id)
    {
         
        $session = session();
        
       	$Model = new userModel();
        $data['petowner'] = $Model->getUsers($id);
		return $this->render_template('doctor/patient/pet/add_new',$data);
		

    }
    
    public function add_new_pet()
{
    $session = session();
    $petModel = new petModel();
    $recordsModel = new recordsModel(); 

    $name = $this->request->getVar('name');
    $species = $this->request->getVar('species');
    $breed = $this->request->getVar('breed');
    $age = $this->request->getVar('age');
    $weight = $this->request->getVar('weight');
    $gender = $this->request->getVar('gender');
    $owner_id = $this->request->getVar('owner_id');

    for ($i = 0; $i < count($name); $i++) {
        $existingPet = $petModel->where('name', $name[$i])
                             ->where('owner_id', $owner_id)
                             ->first();

        if ($existingPet) {
            $session->setFlashdata('error', 'A pet with the name "' . $name[$i] . '" already exists for this owner.');
            return redirect()->back()->withInput(); 
        }

        $petData = [
            'name' => $name[$i],
            'species' => $species[$i],
            'breed' => $breed[$i],
            'age' => $age[$i],
            'weight' => $weight[$i],
            'gender' => $gender[$i],
            'owner_id' => $owner_id,
            'status' => 'Pending'
        ];

      
        $insertedPet = $petModel->save($petData);
        
       
        if ($insertedPet) {
            $petId = $petModel->getInsertID(); 

          
            $recordData = [
                'patient_id' => $owner_id, 
                'pet_id' => $petId, 
            ];

            
            $insertedRecord = $recordsModel->save($recordData);

            if ($insertedRecord) {
                $session->setFlashdata('success', 'Pet has been added and associated with the patient.');
            } else {
                $session->setFlashdata('error', 'Error associating pet with the patient.');
            }
        }
    }

  
    if ($insertedPet) {
        return redirect()->to('doctor/pet/all/' . $owner_id);
    } else {
        $session->setFlashdata('error', 'An error occurred. Pet not added.');
        return redirect()->to('doctor/pet/all/' . $owner_id);
    }
}




    public function update_status($id)
{
    $session = session();
    $Model = new petModel();
    $status = $this->request->getPost('status');

    
    $pet = $Model->find($id);
    $owner_id = $pet['owner_id']; 
    
   
    $Model->update($id, ['status' => $status]);

  
    if ($status === 'Confirm') {
        $session->setFlashdata('success', 'Pet has been confirmed.');
    } elseif ($status === 'Decline') {
        $session->setFlashdata('error', 'Pet has been declined.');
    } else {
        $session->setFlashdata('info', 'Pet status updated.');
    }

   
    return redirect()->to('doctor/pet/all/' . $owner_id);
}


    
    public function getPetTypes()
{
    $petModel = new petModel();
    $data['pets'] = $petModel->select('species, COUNT(*) as count')
                             ->groupBy('species')
                             ->findAll();
    return $this->render_template('doctor/patient/pet_types', $data);
}

    
}
