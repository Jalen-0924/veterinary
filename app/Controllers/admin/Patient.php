<?php

namespace App\Controllers\admin;
use App\Controllers\BaseController;
use App\Models\userModel;
use App\Models\petModel;

class Patient extends BaseController
{
    public function index()
    {

		return $this->render_template('admin/patient/add_new');

    }
     public function view_all()
    {
         
         $session = session();
        $Model = new userModel();
        $data['doctors'] = $Model->getUsersByType('patient');
		return $this->render_template('admin/patient/view_all',$data);
		

    }
    public function deleted($id = null){
        $session = session();
        $Model = new userModel();
        $petModel = new petModel();
        
        $deleted = $Model->where('id', $id)->delete();
        $deleted_pet = $petModel->where('owner_id', $id)->delete();
        
        if($deleted){
                   $session->setFlashdata('success', 'Successfully Deleted');
                   
                               return redirect()->to('admin/patient/all');
                   
                    }
                    else{
                         $session->setFlashdata('Error', 'Not Deleted');
                        
                               return redirect()->to('admin/patient/all');
                        
                    }
        
    }
    
    
    
    public function view_all_patent($id)
    {
         
        $session = session();
        $Model = new petModel();
        $data['pets'] = $Model->getPetsByOwner($id);
       	$Model = new userModel();
        $data['petowne'] = $Model->getUsers($id);
		return $this->render_template('admin/patient/pet/view_all',$data);
		

    }
    
    
    public function add_pet($id)
    {
         
        $session = session();
        
       	$Model = new userModel();
        $data['petowne'] = $Model->getUsers($id);
		return $this->render_template('admin/patient/pet/add_new',$data);
		

    }
    
    public function add_new_pet()
{
    $session = session();
    $Model = new petModel();
    
    $name = $this->request->getVar('name');
    $species = $this->request->getVar('species');
    $breed = $this->request->getVar('breed');
    $age = $this->request->getVar('age');
    $weight = $this->request->getVar('weight');
    $gender = $this->request->getVar('gender');
    $owner_id = $this->request->getVar('owner_id');
    $rstatus = $this->request->getVar('rstatus');  // added
    $colorm = $this->request->getVar('colorm');    // added
    $mchip = $this->request->getVar('mchip');      // added
    $birthdate = $this->request->getVar('birthdate');  // added
    $status = $this->request->getVar('status');        // added
    
    for ($i = 0; $i < count($name); $i++) {
        
        $existingPet = $Model->where('name', $name[$i])
                             ->where('owner_id', $owner_id)
                             ->first();
                             
        if ($existingPet) {
            $session->setFlashdata('error', 'A pet with the name "' . $name[$i] . '" already exists for this owner.');
            return redirect()->back()->withInput(); 
        }
        
        // Prepare data to insert
        $data = array(
            'name' => $name[$i],
            'species' => $species[$i],
            'breed' => $breed[$i],
            'weight' => $weight[$i],
            'sex' => $gender[$i],  // corrected 'gender' to 'sex' to match model field
            'owner_id' => $owner_id,
            'rstatus' => $rstatus[$i],  // added
            'colorm' => $colorm[$i],    // added
            'mchip' => $mchip[$i],      // added
            'birthdate' => $birthdate[$i],  // added
            'status' => $status[$i],        // added
        );
        
        // Save pet data to database
        $inserted = $Model->save($data);
    }
    
    // Check if insertion was successful
    if ($inserted) {
        $session->setFlashdata('success', 'Successfully Inserted');
        return redirect()->to('admin/pet/all/' . $owner_id);
    } else {
        $session->setFlashdata('error', 'Not Inserted');
        return redirect()->to('admin/pet/all/' . $owner_id);
    }
}

    
    public function getPetTypes()
{
    $petModel = new petModel();
    $data['pets'] = $petModel->select('species, COUNT(*) as count')
                             ->groupBy('species')
                             ->findAll();
    return $this->render_template('admin/patient/pet_types', $data);
}

}
