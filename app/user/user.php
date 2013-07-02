<?php

/**
 * Hydra User management class.
 * 
 * This class manages everything related to user information
 * It implements multiple methods to operate with user: Add, Get, Update, Delete.
 *    
 * @version 0.31.1 
 * @author  Apnow
 * 
 **/
 
class User
{
  // Public class attributes:
  public $strAgent  = null;
  public $strAccept = null;
  public $strLang   = null;
  public $strEncoding = null;
  
  // Protected class attributes:
  
  // Private class attributes:
  
  /**
   * User class constructor.
   *         
   * @version 0.31.0.0.1 
   * @author  Apnow
   * 
   **/
  public function __construct($strAgent=null, $strAccept=null, $strLang=null, $strEncoding=null)
  {
    //echo(__METHOD__.'() => Class default constructor.<br />');  
    echo(__METHOD__." Agent    => ".$strAgent."<br />");
    echo(__METHOD__." Accept   => ".$strAccept."<br />");
    echo(__METHOD__." Language => ".$strLang."<br />");
    echo(__METHOD__." Encoding => ".$strEncoding."<br />");
    
  }
  
  /**
   * User class constructor.
   *         
   * @version 0.31.0.0.1 
   * @author  Apnow
   * 
   **/
  public function __destruct()
  {
    //echo(__METHOD__.'() => Class default destructor.<br />');  
  }
  
 /**
  * User Add method.
  * 
  * Adds a new User to the user object. If it exists an user with the same e-mail, the actual user would not be
  * added.
  * 
  * @param string $strName User name.
  * @param md5    $strPassword User Password.
  * @param email  $strMail User Email.
  * @param string $strDesc User Description.
  *  
  * @return boolean True if user has been added to the User collection, false otherwise.
  * 
  * @author Apnow
  * 
  * @route POST /user/add      
  * @route POST /user/insert  
  * @route POST /user         
  * 
  **/
  public function Add($strName, $strPassword, $strEmail, $strDesc='Hydra User')
  {
    //echo(__METHOD__." => Adding user ".$strName."<br />");
    //echo(__METHOD__." => Password: ".$strPassword."<br />");
    //echo(__METHOD__." => E-mail: ".$strEMail."<br />");
    //echo(__METHOD__." => Description: ".$strDesc."<br />");
    
    return(true);
  }
 
 /**
  * User Get method.
  * 
  * Gets information about an specific existing user from the collection. If the user does not exists, 
  * it returns a text with a "user <userid> does not exist".
  * 
  * @param integer $id User identification.  
  *  
  * @return string user information or error string otherwise.
  * 
  * @author Apnow
  * 
  * @route GET /user/{id}     
  * @route GET /user/
  * @route GET /user
  * 
  **/
  public function Get($id=null)
  { 
    $status = null;
    $output = null;
      
    
    $hndModel = new UserModel();
    
    if($data = $hndModel->Get($id))
    {
      
      $status = 200;
      $output = new UserView($data, $this->strAccept, $this->strAgent);
    }
    
    return array($status, $output);
  }
  
/**
  * User Exits method.
  * 
  * Check if a specific users is onto the collection. If the user does not exists, 
  * it returns a text with a "user <userid> does not exist".
  * 
  * @param integer $id User identification.
  *  
  * @return string user information or error string otherwise.
  * 
  * @author Apnow
  * 
  **/
  public function Exists($id=null)
  {
      echo(__METHOD__." => Checking user: ".$id." existance onto the collection.<br />");
      
      
  } 

  
}
?>
