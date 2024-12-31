<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class medicineModel extends Model{
    protected $table = 'medication';
    
    protected $allowedFields = [
        'name',
        'category',
        'quantity',
        'price',
        'expiration',
    ];
    
    
    
     public function getMedicine($id = false) {
      if($id === false) {
        return $this->findAll();
      } 
      else {
          return $this->where('id', $id)->find();
      }
      
     }

    public function getMedicineQuantityByCategory() {
    return $this->select('category, SUM(quantity) as total_quantity')
                ->groupBy('category')
                ->findAll();
}

public function getLowStockItems() {
    return $this->where('quantity <', 12)
                ->where('quantity >', 0)
                ->findAll();
}

    
    public function getOutOfStockItems() {
    return $this->where('quantity', 0)->findAll();
}

     public function getExpiringSoonItems($days = 30) {
    $currentDate = date('Y-m-d'); 
    $expiryDate = date('Y-m-d', strtotime("+$days days")); 

    return $this->where('expiration >=', $currentDate)
                ->where('expiration <=', $expiryDate)
                ->findAll();
}

    public function deleteItemById($id) {
    return $this->db->table('medication')->delete(['id' => $id]);
}

    
    public function getMedicinesByCategory($category) {
    return $this->where('category', $category)->findAll();
}


}