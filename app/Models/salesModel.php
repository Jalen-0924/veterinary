<?php 
namespace App\Models;  
use CodeIgniter\Model;

class SalesModel extends Model {
    protected $table = 'sales';
    
    protected $allowedFields = [
        'month',
        'year',
        'ser_name',
        'ser_qty',
        'ser_price',
        'med_name',
        'med_qty',
        'med_price',
        'total'
    ];

 
    public function getSales($start_date = null, $end_date = null) {
    $builder = $this->db->table('invoice');  
    
    $builder->select('date,total');
    
    if ($start_date !== null && $end_date !== null) {
        $builder->where('date >=', $start_date);
        $builder->where('date <=', $end_date);
    }
    
    $query = $builder->get();
    
    if ($query === false) {
        echo $this->db->getLastQuery();
        print_r($this->db->error());
        return false;
    }
    
    return $query->getResultArray();
}

    public function getSalesData($month, $year) {
        $builder = $this->db->table('invoice');
        return $builder->where('MONTH(date)', $month)
                       ->where('YEAR(date)', $year)
                       ->get()
                       ->getResultArray();
    }
    
    public function getSalesCount() {
    $model = new SalesModel();
    return $model->countAll(); 
}
    public function getSalesForDateRange($start_date, $end_date) {
    $builder = $this->db->table('invoice');
    return $builder->where('date >=', $start_date)
                   ->where('date <=', $end_date)
                   ->get()
                   ->getResultArray();
}

}
