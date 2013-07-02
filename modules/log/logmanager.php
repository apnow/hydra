<?php


/**
 * Hydra Log system Factory class.
 *  
 * This class defines everything about logging:
 *  - Error reporting level.
 *  - Error, Exception and Shutdown handler definition.
 *  - Methods to Add,Get,Delete and Check log entries.  
 *     
 * Available log subsystems:
 *  - MemLog.   
 *      
 * @version 0.31.0.0.1 
 * @author  Apnow
 * 
 **/
 
class LogManager
{
  // ---------------------- ATTRIBUTES --------------------- //
  // ------------------------------------------------------- //
  
  // Public class attributes:
  public $strClassName      = __CLASS__;
  public $strClassVersion   = '0.31.1';
  
  public $strEnvType        = null;
  
  // Protected class attributes:
  protected $bEnabled       = false;
  protected $iErrorLevel    = E_ALL;
  protected $bDisplayErrors = true;
  
  protected $data           = array();
  
  
  
  // Private class attributes:
  
  // ---------------------- CONS & DES --------------------- //
  // ------------------------------------------------------- //
  
  /**
   * LogManager class default constructor.
   * 
   * 
   **/ 
  public function __construct($strEnvironment=null)
  {
    //echo(__METHOD__." => Class default constructor.<br />");
    
    $this->strEnvType = isset($strEnvironment) ? $strEnvironment : 'production';
    
    // Set Error Handler:
    set_error_handler(array($this, 'Error'));
    
    // Set Exception Handler:
    set_exception_handler(array($this, 'Excepcion'));
    
    // Set Shutdown Handler:
    register_shutdown_function(array($this, 'Shutdown'));
  }
  
  /**
   * LogManager class default destructor.
   * 
   * 
   **/ 
  public function __destruct()
  {
    //echo(__METHOD__." => Class default destructor.<br />");
    
    // Public class attributes:
    unset($this->strClassName);
    unset($this->strClassVersion);
    
    unset($this->strEnvType);
    
    // Protected class attributes:
    unset($this->bEnabled);
    unset($this->iErrorLevel);
    unset($this->bDisplayErrors);
    
    unset($this->data);
    // Private class attributes:
    
  }
  
  /**
   * LogManager configuration method.
   * 
   * This method configures all log referenced elements;   
   * 
   **/    
  public function Conf(array $arrConf=null)
  {
    //echo(__METHOD__.' => Configuration Method:<br />');
    
    if(isset($arrConf))
    {
      $this->bEnabled       = isset($arrConf['enable']) ? $arrConf['enable'] : $this->bEnabled;
      $this->iErrorLevel    = isset($arrConf['level'])  ? $arrConf['level']   : $this->iErrorLevel;
      $this->bDisplayErrors = isset($arrConf['display'])? $arrConf['display'] : $this->bDisplayErrors;
    }
    
    // Set Error Reporting:
    error_reporting($this->iErrorLevel);
    
    // Set Display Errors:
    ini_set('display_errors', $this->bDisplayErrors);
     
  }
  
  /**
   * LogManager error handling method.
   * 
   * This method manages all error issues of the project. It captures all errors and manages them.   
   * 
   * @param integer $errno Error level.
   * @param string  $errstr Error description.
   * @param string  $errfile File name where error has happen.
   * @param string  $errline Line of file where error has happen.
   * @param array   $errcontext Array with Context information when error has happen.            
   *       
   **/
  public function Error($errno, $errstr, $errfile=null, $errline=null, array $errcontext=null)
  {
    echo(__METHOD__." => Error Number : ".$errno."<br />");
    echo(__METHOD__." => Error String : ".$errstr."<br />");
    echo(__METHOD__." => Error File   : ".$errfile."<br />");
    echo(__METHOD__." => Error Line   : ".$errline."<br />");
    echo(__METHOD__." => Error Context: <br />");
    //echo("<pre>");
    //  print_r($errcontext);
    //echo("</pre>");
  }
  
  /**
   * LogManager exception handling method.
   * 
   * This method captures all exception and manages them.   
   * 
   * @param
   *       
   **/
  public function Excepcion()
  {
    
  }
  
  /**
   * LogManager shutdown handling method.
   * 
   * This method manages all shutdown issues of the project. It captures a shutdown message and acts accordingly.   
   * 
   * @param
   *       
   **/
  public function Shutdown()
  {
  }
      
      
  // ---------------------- GETTER ------------------------- //
  // ------------------------------------------------------- //
  
  /**
   * Log Manager strClassName GETTER method.
   * 
   * @return string class name.      
   *  
   **/
  public function GetStrClassName()
  {
    return($this->strClassName);
  }
  
  /**
   * Log Manager strClassVersion GETTER method.
   * 
   * @return string class version.      
   *  
   **/
  public function GetStrClassVersion()
  {
    return($this->strClassVersion);
  }
  
  /**
   * Log Manager strEnvType GETTER method.
   * 
   * @return string Environment type (installation, development, production).      
   *  
   **/
  public function GetStrEnvType()
  {
    return($this->strEnvType);
  }
  
  /**
   * Log Manager data GETTER method.
   * 
   * @return array Associative array of available logger subsystems.      
   *  
   **/
  public function GetData()
  { 
    return($this->data);
  }

  /**
   * Log Manager key GETTER method.
   * 
   * @return array List of logger id available on the Log manager.      
   *  
   **/ 
  public function GetKeys()
  {
    return(array_keys($this->data));
  }
 
  /**
   * Log Manager error level  GETTER method.
   * 
   * @return int Error level.      
   *  
   **/    
  public function GetIErrorLevel()
  {
    return($this->iErrorLevel);
  }
  
  /**
   * Log Manager display errors  GETTER method.
   * 
   * @return boolean True if displaying errors is enabled, false otherwise.      
   *  
   **/   
  public function GetDisplayErrors()
  {
    return($this->bDisplayErrors);
  }   
   
}

?>
