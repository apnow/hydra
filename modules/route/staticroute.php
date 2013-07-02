<?php


/**
 * Hydra staticroute class.
 *  
 * This class manages route entries added statically (manually or via conf file).
 * Implementation of IRoute. 
 *    
 * @version 0.31.0.0.1 
 * @author  Apnow
 * 
 **/
class StaticRoute implements IRoute
{
  // ---------------------- ATTRIBUTES --------------------- //
  // ------------------------------------------------------- //
  
  // Public class attributes:
  public $strClassName     = __CLASS__;
  public $strClassVersion  = '0.31.1';
  
  // Protected class attributes:
  protected $data          = null;
  
  // Private class attributes:
  
  // ---------------------- CONS & DES --------------------- //
  // ------------------------------------------------------- //
  
  /**
   * StaticRoute router class default constructor.
   * 
   * 
   **/ 
  public function __construct(ICollection $collection=null)
  {
    echo(__METHOD__." => Class default constructor.<br />");
    
    $this->data = isset($collection) ? $collection : array(); 
  }
  
  /**
   * StaticRoute router class default destructor.
   * 
   * 
   **/ 
  public function __destruct()
  {
    echo(__METHOD__." => Class default destructor.<br />");
    
    // Public attributes:
    unset($this->strClassName);
    unset($this->strClassVersion);
    
    // Protected attributes:
    
    unset($this->data);
    
    // Private attributes:
  }
  
  // ---------------------- METHOD ------------------------- //
  // ------------------------------------------------------- //

  /**
   * Router Add method stores a new route entry identified by name onto the static routing  system.
   *       
   * @param string $strName Name of the route entry to add onto the Route System.
   * @param mixed  $data Route entry info to add.
   * 
   * @return boolean True if route information has been added correctly, false otherwise.
   *    
   * @todo Do we have to check and construct $this->data as a concrete structure definition???.
   *          
   **/  
  public function Add($strName, $data)
  {
    if($strName)
    {
      $this->data[strtolower($strName)] = $data;
    }
      
  }
  
  /**
   * Router Get method checks if an specified route entry exists onto the routing system and returns it.
   * 
   * @param string $strName Identifier of the route entry to obtain.
   * 
   * @return mixed Route entry itself or null if it does not exist.
   * 
   **/
  public function Get($strName)
  {
    $data = null;
    
    if(self::Exists($strName))
      $data = $this->data[strtolower($strName)];
      
    return($data);
  }
  
  /**
   * Router Exists method checks if an specified route entry exists onto the system.
   * 
   * @param string $strName Identifier of the route entry to check.
   * 
   * @return  boolean True if identifier exists onto the router, false otherwise.
   * 
   **/
  public function Exists($strName)
  {
    $ret = false;
    
    if($strName)
    {
      $strName = strtolower($strName);
      $ret = ($this->data[$strName]) ? true : false;
    }
      
    return($ret);
  }
  
  /**
   * Router Delete method erases a route entry from the system.
   * 
   * @param string $strName Identifier of the route entry to erase.
   * 
   * @return boolean True if route entry has been deleted from the container.
   * 
   **/
  public function Delete($strName)
  {
    if(self::Exists($strName))
      unset($this->data[strtolower($strName)]);
    
    return(true);
  }
  
  /**
   * Router Match method matches received resource locator parameter with all available routes onto the static router.
   * 
   * @param mixed $url Resource locator. Should be an string.
   * 
   * @return array Info of the controller/Action and params to execute.
   * 
   * @todo Permit array structured url???   
   **/
  public function Match($url)
  {
    $data = null;
    
    echo(__METHOD__." => Searching match for ".$url.".<br />");
    
    foreach($this->data as $strRouteName => $routeData)
    {
      if(is_string($url) && $routeData['url'] == $url)
      {
        $data = $routeData;
        break;
      }
    }
    
    return($data);
  }
   
  // ---------------------- GETTER ------------------------- //
  // ------------------------------------------------------- //
  
  /**
   * Router data GETTER method.
   * 
   * @return array Associative array of entries.      
   *  
   **/
  public function GetData()
  {
    return($this->data);
  }
  
  /**
   * Router strClassName GETTER method.
   * 
   * @return string class name.      
   *  
   **/
  public function GetStrClassName()
  {
    return($this->strClassName);
  }
  
  /**
   * Router strClassVersion GETTER method.
   * 
   * @return string class version.      
   *  
   **/
  public function GetStrClassVersion()
  {
    return($this->strClassVersion);
  }
}

?>
