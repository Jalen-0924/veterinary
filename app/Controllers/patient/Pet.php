<?php

namespace App\Controllers\patient;
use App\Controllers\BaseController;
use App\Models\petModel;
use App\Models\recordsModel;

class Pet extends BaseController
{
    public function index()
    {
         $session = session();
        $data['p_id'] = $_SESSION['id'];
        
		return $this->render_template('patient/pet/add_new',$data);

    }
     public function view_all()
    {
        $session = session();
        $Model = new petModel();
        $data['pets'] = $Model->getPetsByOwner($_SESSION['id']);
		return $this->render_template('patient/pet/view_all',$data);

    }
    
    public function edit($id = null)
    {
        $session = session();
        $Model = new petModel();
        $data['pet'] = $Model->getPets($id);
		return $this->render_template('patient/pet/edit',$data);

    }
    
    public function store()
{
    $session = session();
    $petModel = new petModel();
    $recordsModel = new recordsModel();

    $petName = $this->request->getVar('name');
    $ownerId = $this->request->getVar('p_id'); 
    $species = $this->request->getVar('species');
    $breed = $this->request->getVar('breed');
    $weight = $this->request->getVar('weight');
    $sex = $this->request->getVar('sex');
    $rstatus = $this->request->getVar('rstatus');
    $colorm = $this->request->getVar('colorm');
    $mchip = $this->request->getVar('mchip');
    $birthdate = $this->request->getVar('birthdate');

   
    $existingPet = $petModel->where('name', $petName)
                            ->where('owner_id', $ownerId)
                            ->first();

    if ($existingPet) {
        $session->setFlashdata('error', 'A pet with the name "' . $petName . '" already exists for this owner.');
        return redirect()->to('pet/add');
    } else {
       
        $petData = [
            'owner_id' => $ownerId,
            'name' => $petName,
            'species' => implode(',', $species),
            'breed' => implode(',', $breed),
            'weight' => $weight,
            'sex' => $sex,
            'rstatus' => $rstatus,
            'colorm' => $colorm,
            'mchip' => $mchip,
            'birthdate' => $birthdate,
            'status' => 'Pending'
        ];

        
        $insertedPet = $petModel->save($petData);

        if ($insertedPet) {
            
            $petId = $petModel->getInsertID();

           
            $recordData = [
                'patient_id' => $ownerId, 
                'pet_id' => $petId
            ];

            
            $insertedRecord = $recordsModel->save($recordData);

            if ($insertedRecord) {
                $session->setFlashdata('success', 'Pet has been added and associated with the patient.');
                return redirect()->to('appointment/add_new');
            } else {
                $session->setFlashdata('error', 'Pet added, but there was an error associating it with the patient.');
                return redirect()->to('pet/add');
            }
        } else {
            $session->setFlashdata('error', 'Error adding the pet.');
            return redirect()->to('pet/add');
        }
    }
}


    
    
    
    
    
     public function update(){
        $session = session();
        $Model = new petModel();
        $id = $this->request->getVar('id');
       $data=array(
                    'name' => $this->request->getVar('name'),
                    'species' => implode(',', $this->request->getVar('species')),
                    'breed' => implode(',', $this->request->getVar('breed')),
                    'weight' => $this->request->getVar('weight'),
                    'sex' => $this->request->getVar('sex'),
                    'rstatus' => $this->request->getVar('rstatus'),
                    'colorm' => $this->request->getVar('colorm'),
                    'mchip' => $this->request->getVar('mchip'),
                    'birthdate'  => $this->request->getVar('birthdate'),
                    );
        
        $update = $Model->update($id, $data);
                    if($update){
                   $session->setFlashdata('success', 'Successfully Updated');
                   return redirect()->to('pet/all');
                    }
                    else{
                         $session->setFlashdata('Error', 'Not Updated');
                        return redirect()->to('pet/all');
                    }
        
    }
    
    
    
    
     public function delete($id = null){
        $session = session();
        $Model = new petModel();
        
        $deleted = $Model->where('id', $id)->delete();
        
        if($deleted){
                   $session->setFlashdata('success', 'Successfully Deleted');
                   return redirect()->to('pet/all');
                    }
                    else{
                         $session->setFlashdata('Error', 'Not Deleted');
                        return redirect()->to('pet/all');
                    }
        
    }
    
    

}
