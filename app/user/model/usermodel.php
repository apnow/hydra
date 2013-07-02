<?php

class UserModel
{
  // Publib attributes:
  
  // Protected attributes:
  protected $strName      = null;
  protected $strPassword  = null;
  protected $strMail      = null;
  protected $strDesc      = null;
  
  // Private attributes:
  
  // Class default constructor:
  public function __construct()
  {
    
  }
  
  // Class default destructor:
  public function __destruct()
  {
    
  }
  
  public function Get($id=null)
  {
    $data = null;
    
    if($id)
    {
      $data['id']       = $id;
      $data['name']     = 'Ibon Gojenola';
      $data['password'] = '*******';
      $data['email']    = 'ibongojenola@gmail.com';
    }
    
    return($data);
  }
}
?>