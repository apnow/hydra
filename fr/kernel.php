<?php

// -------------------- SECURITY CHECK ------------------- //
// ------------------------------------------------------- //

defined('BASE') or die('Security Error: Cannot call class directly.');

// ---------------------- INCLUDES ----------------------- //
// ------------------------------------------------------- //

require_once("core/library.php");
require_once('core/resourcemanager.php');

/**
 * Hydra microkernel architecture core class.
 * 
 * @version 0.31.1 
 * @author  Apnow
 * 
 **/
     
final class Kernel 
{
  // ---------------------- ATTRIBUTES --------------------- //
  // ------------------------------------------------------- //
  
  // Public class attributes:
  public static $strClassName         = __CLASS__;
  public static $strClassVersion      = '0.31.1';
  
  // Protected class attributes:
  protected static $strAppName        = 'Hydra';
  protected static $strVersion        = '0.1.0';
  protected static $strEnvType        = 'development';      
     
  // Private class attributes:
  private static $objInstance         = null;
  
  private static $dic                 = null;   // ResourceManager.
  private static $rm                  = null;   // RequestManager.
  
  // ---------------------- CONS & DES --------------------- //
  // ------------------------------------------------------- //
  
  /**
   * Hydra class singleton method.
   * 
   * 
   **/               
  public static function Factory()
  {
    if(!self::$objInstance instanceof self)
    {
      self::$objInstance = new self();
    }
    
    return(self::$objInstance);
  }
  
  /**
   * Hydra class default constructor.
   * 
   * 
   **/ 
  private function __construct()
  {
    //echo(__METHOD__.'() => Class default constructor.<br />');
  }
  

  /**
   * Hydra class default destructor.
   * 
   * 
   **/  
  public function __destruct()
  {
    //echo(__METHOD__.'() => Class default destructor.<br />');
    
  }
  
  // ---------------------- METHOD ------------------------- //
  // ------------------------------------------------------- //
  
  /**
   * Hydra initialization method.
   * 
   * Init all required elements based on received conf info and requiered class instantiation.
   * 
   * @param array $arrConf Application configuration file.         
   * 
   * @return boolean True if we have a correct Kernel instance, false otherwise.
   *       
   **/
  public static function Init(array &$arrConf=null)
  {
    $bRet = false;
    
    if(self::$objInstance)
    {
      // 1 - Init Resource Manager:
      self::$dic = new ResourceManager();
      
      self::$dic->Add('conf', $arrConf);
      self::$dic->Add('library', new Library(isset(self::$dic['conf']['path'])? self::$dic['conf']['path'] : null));
      
      if(self::$dic['library']->Exists('logManager'))
      {
        self::$dic->Add('log', new LogManager(self::$dic['conf']['app']['environment']));
        self::$dic['log']->Conf(self::$dic['conf']['log']);
      }
                                    
      // 2 - Get App Information if available:
      self::$strAppName = isset($arrConf['app']['name'])       ? $arrConf['app']['name']       : self::$strAppName;  
      self::$strVersion = isset($arrConf['app']['version'])    ? $arrConf['app']['version']    : self::$strVersion;
      self::$strEnvType = isset($arrConf['app']['environment'])? $arrConf['app']['environment']: self::$strEnvType;
      
      // 3 - Create a Request Manager instance:
      
      $arrDefRoute = (isset($arrConf['route']) && isset($arrConf['route']['default'])) ? $arrConf['route']['default'] : null;
      if(self::$rm = new RequestManager(new RouteManager($arrDefRoute))) 
        $bRet = true;  
      
    }
    else
      die(__METHOD__." => Error Kernel ".$this->strClassVersion." initialisation failure.");
    
    return($bRet);
  }
  
  /**
   * Hydra Run method.
   * 
   * Starts receiveng requests and manage each of them.      
   *  
   **/ 
  public static function Run()
  {  
    echo("<br/>Hydra Version ".Kernel::GetStrClassVersion()."<br/>".PHP_EOL);
    echo("-----------------------------------<br />".PHP_EOL);
    echo("Initiating ".self::$strAppName." version ".self::$strVersion." [".self::$strEnvType."]<br />".PHP_EOL);
      
    // Launchy our first Request:
    self::$rm->Run();
     
  }
  
  // ---------------------- GETTER ------------------------- //
  // ------------------------------------------------------- //
  
  /**
   * Hydra strClassName GETTER method.
   * 
   * @return string class name.      
   *  
   **/
  public static function GetStrClassName()
  {
    return(self::$strClassName);
  }
  
  /**
   * Hydra strClassVersion GETTER method.
   * 
   * @return string class version.      
   *  
   **/
  public static function GetStrClassVersion()
  {
    return(self::$strClassVersion);
  }
  
  /**
   *  Hydra resourcemanager GETTER method.
   * 
   *  @return object Resource Manager.
   * 
   **/
  public static function GetDIC()
  {
    return(self::$dic);
  }
  
  /**
   *  Hydra requestmanager GETTER method.
   * 
   *  @return object Request Manager.
   * 
   **/
  public static function GetRM()
  {
    return(self::$rm);
  }
       
}

?>
