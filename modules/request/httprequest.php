<?php


/**
 * Hydra HttpRequest class.
 *  
 * This class manages internal requests defined by the routing system.
 * Implementation of IRequest. 
 *    
 * @version 0.31.0.0.1 
 * @author  Apnow
 * 
 **/
class HttpRequest implements IRequest
{
  // ---------------------- ATTRIBUTES --------------------- //
  // ------------------------------------------------------- //
  
  // Public class attributes:
  public $strClassName     = __CLASS__;
  public $strClassVersion  = '0.31.1';
  
  // Protected class attributes:
  private $id            = null;
  private $strController = null;
  private $strAction     = null;
  private $arrParams     = null;
  
  private $strAgent      = null;
  private $strAccept     = null;
  private $strLanguage   = null;
  private $strEncoding   = null;
  
  private $status        = null; 
  private $output        = null;
  
  
  // ---------------------- CONS & DES --------------------- //
  // ------------------------------------------------------- //
  
  /**
   * HttpRequst request class default constructor.
   * 
   * 
   **/ 
  public function __construct(array &$data)
  {
    //echo(__METHOD__." => Class default constructor.<br />");
    
    $this->id             = isset($data['id'])         ? $data['id']         : null;
    $this->strController  = isset($data['controller']) ? $data['controller'] : null;
    $this->strAction      = isset($data['action'])     ? $data['action']     : null;
    $this->arrParams      = isset($data['params'])     ? $data['params']     : null;
    
    $this->strAgent       = isset($data['format']['agent'])      ? $data['format']['agent']      : null;
    $this->strAccept      = isset($data['format']['accept'])     ? $data['format']['accept']     : null;
    $this->strLanguage    = isset($data['format']['language'])   ? $data['format']['language']   : null;
    $this->strEncoding    = isset($data['format']['encoding'])   ? $data['format']['encoding']   : null;
    
    // Execute Controller/Action
    $this->Run();
  }
  
  /**
   * HttpRequest request class default destructor.
   * 
   * 
   **/ 
  public function __destruct()
  {
    //echo(__METHOD__." => Class default destructor.<br />");
    
    // Public attributes:
    unset($this->strClassName);
    unset($this->strClassVersion);
    
    // Private attributes:
    unset($this->id);
    unset($this->strController);
    unset($this->strAction);
    unset($this->arrParams);
    
    unset($this->strAgent);
    unset($this->strAccept);
    unset($this->strLanguage);
    unset($this->strEncoding);
    
    unset($this->status);
    unset($this->output);
    
  }
  
  // ---------------------- METHOD ------------------------- //
  // ------------------------------------------------------- //
  
  private function Run()
  {
      // Create the controller instance and call to the method with the params:
      list($this->status, $this->output) = call_user_func_array(array(new $this->strController($this->strAgent, $this->strAccept, $this->strLanguage, $this->strEncoding), $this->strAction), $this->arrParams);
      
      echo(__METHOD__." => Status: ".$this->status."<br />");
      echo(__METHOD__." => Rdo: ".$this->output."<br />");
      
      return($this->status);
      
  }
  
  public function GetStatus()
  {
    return($this->status);
  }
  
  public function GetData()
  {
    return ($this->output);
  }
}

?>
