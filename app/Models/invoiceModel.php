<?php 
namespace App\Models;  
use CodeIgniter\Model;
use App\Models\userModel;
use App\Models\petModel;
use App\Models\serviceModel;
use App\Models\medicineModel;
use App\Models\appointmentModel;
  
class invoiceModel extends Model{
    protected $table = 'invoice';
    
    protected $allowedFields = [
        'date',
        'patient_id',
        'ser_name',
        // 'ser_qty',
        'ser_price',
        'ser_desc',
        'med_name',
        'med_qty',
        'med_price',
        'med_desc',
        'total',
        'invo_note',
        'appointment_id',
        'pet_owner_name',
        'other_name',
         'ser_dates',  // New field for service dates
    'med_dates'   // New field for medicine dates
    ];
    
    
    
     public function getInvoices($id = false) {
    if($id === false) {
        $sql = "SELECT invoice.id, invoice.date, invoice.total, user.first_name, user.last_name, invoice.pet_owner_name 
                FROM invoice 
                LEFT JOIN user ON user.id = invoice.patient_id 
                ORDER BY invoice.id DESC";
        
        $query = $this->db->query($sql);
        $result = $query->getResultArray();
        return $result;
    } else {
        return $this->where('id', $id)->find();
    }
}

     
     
      public function getInvoicesByPateint($id) {
   
           return $this->where('patient_id', $id)->findAll();
        
    
      
     }

        public function getTotalProfit() {
    $sql = "SELECT SUM(total) as total_profit FROM invoice";
    $query = $this->db->query($sql);
    return $query->getRowArray();
}


     
     public function store($data){
         $session = session();
         $medicineModel = new medicineModel();
         $invoiceModel = new invoiceModel();
        
         $med_ids = json_decode($data['med_name']);
         $med_qty = json_decode($data['med_qty']);
         if($med_ids){
         for($i = 0; $i < count($med_ids); $i++){
             
           $medicine =  $medicineModel->getMedicine($med_ids[$i])[0];
           $remaining = $medicine['quantity'] - $med_qty[$i];
           
           $update = $medicineModel->update($med_ids[$i], array('quantity' => $remaining));
           
         }
         }else{
             $update = 'update';
         }
         if($update){
             
              $inserted = $invoiceModel->save($data);
              if($inserted){
                  return 'success';
              }else{
                  return 'error';
              }
         }
         
        
         
     }
     
     public function getInvoiceCountByMonth() {
    $sql = "SELECT 
                MONTH(date) as month, 
                COUNT(*) as invoice_count 
            FROM invoice 
            GROUP BY MONTH(date)";
    
    $query = $this->db->query($sql);
    return $query->getResultArray();
}


    
     
     
     
     
        public function delete_invoice($id){
    $session = session();
    $medicineModel = new medicineModel();
    $invoiceModel = new invoiceModel();

   
    $invoice = $invoiceModel->where('id', $id)->find();

  
    if (empty($invoice)) {
        return 'Invoice not found';
    }

    $invoice = $invoice[0];

    
    $med_ids = json_decode($invoice['med_name'], true) ?? [];
    $med_qty = json_decode($invoice['med_qty'], true) ?? [];

   
    if (count($med_ids) !== count($med_qty)) {
        return 'Mismatched medicine IDs and quantities';
    }

   
    if (empty($med_ids) || empty($med_qty)) {
        return 'No medicines to update';
    }

   
    $update = false;

    
    for ($i = 0; $i < count($med_ids); $i++) {
        $medicine = $medicineModel->getMedicine($med_ids[$i]);

     
        if (empty($medicine)) {
            return 'Insufficient Item';
        }

        $medicine = $medicine[0];
        $remaining = $medicine['quantity'] + $med_qty[$i];

       
        $update = $medicineModel->update($med_ids[$i], array('quantity' => $remaining));
        if (!$update) {
            return 'Error';
        }
    }

    
    if ($update) {
        $deleted = $invoiceModel->where('id', $id)->delete();
        if ($deleted) {
            return 'success';
        } else {
            return 'Error ';
        }
    }

    return 'Unexpected error';
}

public function getInvoiceByCustomName($name)
{
    return $this->where('pet_owner_name', $name)->first();
}



public function print_invoice($id) {
    $session = session();
    $medicineModel = new medicineModel();
    $invoiceModel = new invoiceModel();
    $userModel = new userModel();
    $serviceModel = new serviceModel();

    $main_data = [];
    $invoice = $invoiceModel->getInvoices($id)[0] ?? null;

    if (!$invoice) {
        return [];
    }

    // Basic invoice details
    $main_data['id'] = $invoice['id'];
    $main_data['date'] = $invoice['date'];
    $main_data['total'] = $invoice['total'];
    $main_data['note'] = $invoice['invo_note'];
    $main_data['pet_owner_name'] = $invoice['pet_owner_name'] ?? '';
    $main_data['other_name'] = $invoice['other_name'] ?? '[]';
    $main_data['ser_dates'] = $invoice['ser_dates'] ?? '[]';  // Add this line
    $main_data['med_dates'] = $invoice['med_dates'] ?? '[]';  // Add this line

    if (!empty($invoice['patient_id']) && $invoice['patient_id'] !== 'others') {
        $main_data['patient'] = $userModel->getUsers($invoice['patient_id']);
    } else {
        $main_data['patient'] = [];
    }

    // Decode medication and service IDs
    $med_ids = json_decode($invoice['med_name'], true) ?? [];
    $ser_ids = json_decode($invoice['ser_name'], true) ?? [];

    $medicines = [];
    $medicine_details = [];

    // Fetch medicine details
    if ($med_ids) {
        foreach ($med_ids as $med_id) {
            $medicine = $medicineModel->getMedicine($med_id)[0] ?? null;
            if ($medicine && isset($medicine['name'])) {
                $medicine_details[] = [
                    'id' => $medicine['id'],
                    'name' => $medicine['name']
                ];
                $medicines[] = $medicine['name'];
            }
        }
    }

    // Encode all medicine data
    $main_data['medicines'] = json_encode($medicines);
    $main_data['medicine_details'] = json_encode($medicine_details);
    $main_data['med_qty'] = $invoice['med_qty'];
    $main_data['med_amount'] = $invoice['med_price'];
    $main_data['med_desc'] = $invoice['med_desc'];

    // Initialize $services and $service_details
    $services = [];
    $service_details = [];

    // Fetch service details if any service IDs exist
    if ($ser_ids) {
        foreach ($ser_ids as $i => $ser_id) {
            if ($ser_id === 'Others') {
                // Handle 'Others' service type
                $other_names = json_decode($invoice['other_name'], true) ?? [];
                $service_details[] = [
                    'id' => 'Others',
                    'name' => isset($other_names[$i]) ? $other_names[$i] : 'Other Service',
                    'is_custom' => true
                ];
                $services[] = isset($other_names[$i]) ? $other_names[$i] : 'Other Service';
            } else {
                // Handle regular services
                $service = $serviceModel->getServices($ser_id)[0] ?? null;
                if ($service && isset($service['name'])) {
                    $service_details[] = [
                        'id' => $service['id'],
                        'name' => $service['name'],
                        'price' => $service['price'],
                        'is_custom' => false
                    ];
                    $services[] = $service['name'];
                }
            }
        }
    }

    // Encode all service data
    $main_data['services'] = json_encode($services);
    $main_data['service_details'] = json_encode($service_details);
    $main_data['ser_price'] = $invoice['ser_price'];
    $main_data['ser_desc'] = $invoice['ser_desc'];

    return $main_data;
}

    
    


    public function getInvoicesWithUserDetails($start_date, $end_date)
{
    $sql = "SELECT invoice.*, user.first_name, user.last_name 
            FROM invoice 
            LEFT JOIN user ON user.id = invoice.patient_id 
            WHERE invoice.date >= ? AND invoice.date <= ?";
    $query = $this->db->query($sql, [$start_date, $end_date]);
    return $query->getResultArray();
}

public function getInvoiceByPetOwner($petOwnerId)
{
    return $this->db->table('invoice')
        ->where('patient_id', $petOwnerId)
        ->orderBy('id', 'DESC') // Get the latest invoice
        ->get()
        ->getRowArray();
}

public function updateInvoice($invoiceId, $data)
{
    return $this->db->table('invoice')
        ->where('id', $invoiceId)
        ->update($data);
}


}