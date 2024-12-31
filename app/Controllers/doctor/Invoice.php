<?php

namespace App\Controllers\doctor;
use App\Controllers\BaseController;
use App\Models\userModel;
use App\Models\serviceModel;
use App\Models\medicineModel;
use App\Models\invoiceModel;

class Invoice extends BaseController
{



   public function index(){
        
        $session = session();
        $Model = new medicineModel();
        $medicineModel = new medicineModel();
        $serviceModel = new serviceModel();
        $userModel = new userModel();
        
        $data['medicines'] = $medicineModel->getMedicine();
        $data['services'] = $serviceModel->getServices();
      
        $data['patient'] = $userModel->getUsersByType('patient');
        
        return $this->render_template('doctor/invoice/add_new',$data);
    }
    
      public function view_all(){
        
        $session = session();
        $Model = new invoiceModel();
        
        $data['invoices'] = $Model->getInvoices();
        
        return $this->render_template('doctor/invoice/all_bill',$data);
    }
   
    
      public function  patient_all(){
        
        $session = session();
        $Model = new invoiceModel();
       
        $data['invoices'] = $Model->getInvoicesByPateint($_SESSION['id']);
        
        return $this->render_template('patient/invoice/all_bill',$data);
    }
    
    
    public function get_service_price(){
         $session = session();
        $serviceModel = new serviceModel();
        
        $ser_id = $this->request->getVar('id');
         
        return json_encode($serviceModel->getServices($ser_id));
    }
    
     public function get_medicine_price(){
         $session = session();
        $medicineModel = new medicineModel();
        
        $ser_id = $this->request->getVar('id');
         
        return json_encode($medicineModel->getMedicine($ser_id));
    }
    
    public function getMedicinesByCategory() {
    $medicineModel = new medicineModel();
    $category = $this->request->getVar('category');

  
    $items = $medicineModel->getMedicinesByCategory($category);
    return json_encode($items);
}

    
    public function store()
{
    $session = session();
    $model = new invoiceModel();

    $petOwner = $this->request->getVar('patient');
    $petOwnerName = ($petOwner == 'others') ? $this->request->getVar('petOwnerName') : null;
    $currentDate = date('Y-m-d');

    // Service and medicine data
    $serviceNames = $this->request->getVar('serviceName') ?? [];
    $servicePrices = $this->request->getVar('servicePrice') ?? [];
    $medicineNames = $this->request->getVar('medicineName') ?? [];
    $medicineQtys = $this->request->getVar('medicineQty') ?? [];
    $medicinePrices = $this->request->getVar('medicinePrice') ?? [];

    // Prepare dates
    $serviceDates = array_fill(0, count($serviceNames), $currentDate);
    $medicineDates = array_fill(0, count($medicineNames), $currentDate);

    // Check for an existing invoice
    $existingInvoice = null;

    if ($petOwner !== 'others') {
        // Search by pet owner if not "Others"
        $existingInvoice = $model->getInvoiceByPetOwner($petOwner);
    } else if ($petOwnerName) {
        // Search by custom pet owner name if "Others"
        $existingInvoice = $model->getInvoiceByCustomName($petOwnerName);
    }

    if ($existingInvoice) {
        // Merge new items into the existing invoice
        $mergedServices = array_merge(json_decode($existingInvoice['ser_name'], true) ?? [], $serviceNames);
        $mergedServicePrices = array_merge(json_decode($existingInvoice['ser_price'], true) ?? [], $servicePrices);
        $mergedMedicines = array_merge(json_decode($existingInvoice['med_name'], true) ?? [], $medicineNames);
        $mergedMedicineQtys = array_merge(json_decode($existingInvoice['med_qty'], true) ?? [], $medicineQtys);
        $mergedMedicinePrices = array_merge(json_decode($existingInvoice['med_price'], true) ?? [], $medicinePrices);
        $mergedServiceDates = array_merge(json_decode($existingInvoice['ser_dates'], true) ?? [], $serviceDates);
        $mergedMedicineDates = array_merge(json_decode($existingInvoice['med_dates'], true) ?? [], $medicineDates);

        $data = [
            'ser_name' => json_encode($mergedServices),
            'ser_price' => json_encode($mergedServicePrices),
            'med_name' => json_encode($mergedMedicines),
            'med_qty' => json_encode($mergedMedicineQtys),
            'med_price' => json_encode($mergedMedicinePrices),
            'ser_dates' => json_encode($mergedServiceDates),
            'med_dates' => json_encode($mergedMedicineDates),
            'total' => array_sum($mergedServicePrices) + array_sum($mergedMedicinePrices),
        ];

        $updated = $model->updateInvoice($existingInvoice['id'], $data);

        if ($updated) {
            $session->setFlashdata('success', 'Invoice updated successfully.');
            return redirect()->to('invoice/all');
        }
    } else {
        // Create a new invoice
        $data = [
            'date' => $currentDate,
            'invo_note' => $this->request->getVar('note'),
            'patient_id' => $petOwner,
            'pet_owner_name' => $petOwnerName,
            'ser_name' => json_encode($serviceNames),
            'ser_price' => json_encode($servicePrices),
            'med_name' => json_encode($medicineNames),
            'med_qty' => json_encode($medicineQtys),
            'med_price' => json_encode($medicinePrices),
            'ser_dates' => json_encode($serviceDates),
            'med_dates' => json_encode($medicineDates),
            'total' => array_sum($servicePrices) + array_sum($medicinePrices),
        ];

        $inserted = $model->store($data);

        if ($inserted) {
            $session->setFlashdata('success', 'Invoice created successfully.');
            return redirect()->to('invoice/all');
        }
    }

    $session->setFlashdata('error', 'Failed to create/update the invoice.');
    return redirect()->to('invoice/all');
}


    
    
    
        public function delete($id = null){
        $session = session();
        $Model = new invoiceModel();
        
        $deleted = $Model->delete_invoice($id);
        
        if($deleted == 'success'){
                   $session->setFlashdata('success', 'Successfully Deleted');
                         return redirect()->to('invoice/all');
                   
                    }
                    else{
                         $session->setFlashdata('Error', 'Not Deleted');
                         return redirect()->to('invoice/all');
                        
                    }
        
    }
    
    
    
    public function billing_report_print($id){
         $session = session();
        $Model = new invoiceModel();
        
        $data['print'] = $Model->print_invoice($id);
        return view('doctor/invoice/print',$data);
    }
    
    
}