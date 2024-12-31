<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class userModel extends Model{
    protected $table = 'user';
    
    protected $allowedFields = [
        'first_name',
        'last_name',
        'email',
        'password',
        'user_type',
        'city',
        'zipcode',
        'phone',
        'address',
        'token',
        'profile_pic',
        'otp',
    ];
    
    
     public function check_email($email)
    {
        return $this->where('email', $email)->first();
    }
	
     public function getUsers($id = false) {
      if($id === false) {
        return $this->findAll();
      } 
      else {
          return $this->where('id', $id)->find();
      }
      
     }
     

     public function getUsersByType($type) {
    return $this->where('user_type', $type)->findAll();
}

     // public function getUsersByType($type = false) {
      
     //  if($type != 'false') {
     //      return $this->where('user_type', $type)->findAll();
     //  }
      
     // }
     
     
    
    
    
}