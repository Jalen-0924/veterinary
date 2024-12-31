<?php

namespace App\Controllers\doctor;
use App\Controllers\BaseController;
use App\Models\medicineModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Medication extends BaseController
{
    public function index()
    {

		return $this->render_template('doctor/medication/add_new');

    }
     public function view_all()
    {
        $session = session();
        $Model = new medicineModel();
        $data['medicines'] = $Model->getMedicine();

        $medicineModel = new medicineModel();
        $data['lowStockItems'] = $medicineModel->getLowStockItems();


        $medicineModel = new medicineModel();
        $data['outOfStockItems'] = $medicineModel->getOutOfStockItems();

        $medicineModel = new medicineModel();
        $data['expiringSoonItems'] = $medicineModel->getExpiringSoonItems();
        
		return $this->render_template('doctor/medication/view_all',$data);

    }
    
     public function view_all_medicine()
    {
        $session = session();
        $Model = new medicineModel();
        $data['medicines'] = $Model->getMedicine();
		return $this->render_template('doctor/medication_report',$data);

    }
    

     public function edit($id = null)
    {
        $session = session();
        $Model = new medicineModel();
        $data['medicine'] = $Model->getMedicine($id);
		return $this->render_template('doctor/medication/edit',$data);

    }
    
    
    public function store(){
        $session = session();
        $Model = new medicineModel();
        
         $data=array(
                    'name' => $this->request->getVar('name'),
                    'category' => $this->request->getVar('category'),
                    'quantity' => $this->request->getVar('quantity'),
                    'price' => $this->request->getVar('price'),
                    'expiration' => $this->request->getVar('expiration'),
                    );
        
        $inserted = $Model->save($data);
        
        if($inserted){
                   $session->setFlashdata('success', 'Successfully Inserted');
                   return redirect()->to('medicne/add');
                    }
                    else{
                         $session->setFlashdata('Error', 'Not Inserted');
                        return redirect()->to('medicne/all');
                    }
        
    }
    
  public function update()
{
    $session = session();
    $Model = new medicineModel();
    $id = $this->request->getVar('id');

    // Get expiration date or set as null if not provided
    $expiration = $this->request->getVar('expiration') ?: null;

    // Collect data
    $data = [
        'name' => $this->request->getVar('name'),
        'category' => $this->request->getVar('category'),
        'price' => $this->request->getVar('price'),
        'quantity' => $this->request->getVar('quantity'),
        'expiration' => $expiration,
    ];

    // Update data
    $update = $Model->update($id, $data);

    // Handle response
    if ($update) {
        $session->setFlashdata('success', 'Successfully Updated');
        return redirect()->to('medicne/all');
    } else {
        $session->setFlashdata('error', 'Not Updated');
        return redirect()->to('medicne/all');
    }
}



    
    
      public function delete($id = null)
    {
        $session = session();
        $Model = new medicineModel();

        if ($id !== null) {
            $deleted = $Model->delete($id);

            if ($deleted) {
                $session->setFlashdata('success', 'Successfully Deleted');
            } else {
                $session->setFlashdata('error', 'Not Deleted');
            }
        } else {
            $session->setFlashdata('error', 'Invalid ID');
        }

        return redirect()->to('medicne/all');
    }


   public function delete_exp() {
    $selectedItems = $this->request->getPost('selectedItems');
    
    if (!empty($selectedItems)) {
        $medicineModel = new medicineModel(); 
        foreach ($selectedItems as $itemId) {
            $medicineModel->deleteItemById($itemId);
        }
        session()->setFlashdata('success', 'Items have been deleted successfully.');
    }
    
    return redirect()->to('medicne/all');
}
    public function delete_low() {
    $selectedItems = $this->request->getPost('selectedItems');
    
    if (!empty($selectedItems)) {
        $medicineModel = new medicineModel(); 
        foreach ($selectedItems as $itemId) {
            $medicineModel->deleteItemById($itemId);
        }
        session()->setFlashdata('success', 'Items have been deleted successfully.');
    }
    
    return redirect()->to('medicne/all');
}


    public function restock()
{
    $session = session();
    $Model = new medicineModel();

    
    $itemIds = $this->request->getVar('item_ids');
    $quantities = $this->request->getVar('quantities');

    if (!empty($itemIds) && !empty($quantities)) {
        foreach ($itemIds as $index => $itemId) {
            $quantityToAdd = (int)$quantities[$index]; 

    
            $currentItem = $Model->find($itemId);
            if ($currentItem) {
                $newQuantity = $currentItem['quantity'] + $quantityToAdd; 

                $Model->update($itemId, ['quantity' => $newQuantity]);
            }
        }

        $session->setFlashdata('success', 'Items successfully restocked.');
    } else {
        $session->setFlashdata('error', 'No items to restock.');
    }

    return redirect()->to('medicne/all'); 
}
    
   
      public function download_report()
    {
        
        $medicineModel = new medicineModel();
        $medicines = $medicineModel->getMedicine();

       
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

      
        $sheet->setCellValue('A1', 'Item Name');
        $sheet->setCellValue('B1', 'Category');
        $sheet->setCellValue('C1', 'Quantity');
        $sheet->setCellValue('D1', 'Price');
        $sheet->setCellValue('E1', 'Stock Value');
        $sheet->setCellValue('F1', 'Expiration Date');

        
        $row = 2; 
        foreach ($medicines as $medicine) {
            $sheet->setCellValue("A{$row}", $medicine['name']);
            $sheet->setCellValue("B{$row}", $medicine['category']);
            $sheet->setCellValue("C{$row}", $medicine['quantity']);
            $sheet->setCellValue("D{$row}", $medicine['price']);
            $sheet->setCellValue("E{$row}", $medicine['price'] * $medicine['quantity']);
            $sheet->setCellValue("F{$row}", !empty($medicine['expiration']) ? date('F d, Y', strtotime($medicine['expiration'])) : '');
            $row++;
        }

       
        $filename = 'Inventory Report' . date('F-d-Y') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

       
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    
}
