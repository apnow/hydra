<?php


// -------------------- SECURITY CHECK ------------------- //
// ------------------------------------------------------- //

defined('BASE') or die('Error cannot call class directly.');


/**
 * Hydra request manager class.
 * 
 * This class stores and manages all type of requests (internal, curl, soap, shell, ...)
 *    
 * @version 0.31.1 
 * @author  Apnow
 *
 * @todo make all elements   
 **/
 
class RequestManager
{
  // ---------------------- ATTRIBUTES --------------------- //
  // ------------------------------------------------------- //
  
  // Public class atributes:
  public $strClassName     = __CLASS__;
  public $strClassVersion  = '0.31.1';
  
  // Protected class atributes: 
  protected $router        = null;
  protected $data          = null;
   
  // Private class attributes:
  
  // ---------------------- CONS & DES --------------------- //
  // ------------------------------------------------------- //
  
  /**
   * RequestManager class default constructor.
   * 
   * 
   **/
  public function __construct(RouteManager $route)
  {
    //echo(__METHOD__.'() => Class default constructor.<br />');
    
    $this->router = $route;
    $this->data   = array();  
  }
  
  /**
   * RequestManager class default destructor.
   * 
   * 
   **/
  public function __destruct()
  {
    //echo(__METHOD__.'() => Class default destructor.<br />');
    
    // Public class attributes:
    unset($this->strClassName);
    unset($this->strClassVersion);
    
    // Protected class attributes:
    unset($this->router);
    unset($this->data);
    
    // Private class attributes:
  }
  
  // ---------------------- METHODS ------------------------ //
  // ------------------------------------------------------- //
  
  public function Run($data=null)
  {
    $arrMVC = null;
    
    if(!isset($data))
    {
      $arrMVC = $this->router->Match($_SERVER['HTTP_HOST'], str_replace(BASE, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)), strtoupper($_SERVER['REQUEST_METHOD']));
    }
    else
    {
      if(isset($data['url']))
      {
        $strHost = parse_url($_SERVER['REQUEST_URI'], PHP_URL_HOST);
        
        if($strHost === $_SERVER['HTTP_HOST'])
        {
          $strUri = str_replace(BASE, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
          $strMethod = isset($data['method']) ? $data['method'] : 'GET';
          
          $arrMVC = $this->router->Match($strHost, $strUri, $strMethod, $data);
        }
        else
        {
          // External Request:
          $this->data[$strHost][$data['id']] = new CurlRequest($data);
        }  
          
      }
      
    }
    
    if($arrMVC)
    {
      // Internal request:
      echo(__METHOD__." => Obtained MVC Data:<br />");
      echo("<pre>");
        print_r($arrMVC);
      echo("</pre>");
      
      $this->data[$_SERVER['HTTP_HOST']][$arrMVC['id']] = new HttpRequest($arrMVC);
      
      //trigger_error("ESTO ES UN PUTO ERROR");
    }
    
    
    
  }
  
  // ---------------------- GETTER ------------------------- //
  // ------------------------------------------------------- //
 
 /**
   * RequestManager strClassName GETTER method.
   * 
   * @return string class name.      
   *  
   **/
  public function GetStrClassName()
  {
    return($this->strClassName);
  }
  
  /**
   * RequestManager strClassVersion GETTER method.
   * 
   * @return string class version.      
   *  
   **/
  public function GetStrClassVersion()
  {
    return($this->strClassVersion);
  }    
  
  /**
   * RequestManager data GETTER method.
   * 
   * @return array Associative array of created request instances.      
   *  
   **/
  public function GetData()
  {
    return($this->data);
  }
   
  
}
?>
