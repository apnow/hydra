<?php


// -------------------- SECURITY CHECK ------------------- //
// ------------------------------------------------------- //

defined('BASE') or die('Security Error: Cannot call class directly.');


/**
 * Hydra resource manager class.
 * 
 * This class stores all type of elements (objects, arrays, strings, etc) to reuse them accross all the web.
 *    
 * @version 0.31.1 
 * @author  Apnow
 *  
 **/
 
class ResourceManager implements ArrayAccess
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
   * Resource Manager container class default constructor.
   * 
   * @param array Array with elements/objects to store in the resource manager or an empty array to initialize the array.   
   * 
   **/ 
  public function __construct($values=null)
  {
    //echo(__METHOD__." => Class default constructor.<br />");

    $this->data = ($values) ? $values : array();
    
  }
  
  /**
   * Resource Manager container class default destructor.
   * 
   * 
   **/ 
  public function __destruct()
  {
    //echo(__METHOD__." => Class default destructor.<br />");
    
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
   * Resource Manager Add method stores a new element onto the bucket based on its name.
   * 
   * The added element might be an instance of a known object or any element to be loaded as
   * database conf info, log info, etc.
   * 
   * @param string $strName Name of the object/element to store.
   * @param mixed  $data Object/Element to the store.
   * 
   * @return nothing.
   * 
   * @todo Do I have to return the added object ???
   *       
   **/
  public function Add($strName, $data)
  {
    $this->data[strtolower($strName)] = $data;
  }
  
  /**
   * Resource Manager offsetSet method stores a new element onto the bucket based on its name.
   * 
   * The added element might be an instance of a known object or any element to be loaded as
   * database conf info, log info, etc.
   * Alias method of Add.   
   * 
   * @param string $strName Name of the object/element to store.
   * @param mixed  $data Object/Element to the store.
   * 
   * @return nothing.
   * 
   * @todo Do I have to return the added object ???
   *       
   **/
  public function OffsetSet($strName, $data)
  {
    self::Add($strName, $data);
  }
  
   
  /**
   * Resource Manager Exists method checks if an specified element exists onto the container.
   * 
   * @param string $strName Identified of the object to check.
   * 
   * @return  boolean True if object name exists onto the container, false otherwise.
   * 
   **/
  public function Exists($strName)
  {
    return(array_key_exists(strtolower($strName), $this->data));
      
  }
  
  /**
   * Resource Manager OffsetExists method checks if an specified element exists onto the container.
   * 
   * Alias method of Exists.      
   * 
   * @param string $strName Identified of the object to check.
   * 
   * @return  boolean True if object name exists onto the container, false otherwise.
   * 
   **/
  public function OffsetExists($strName)
  {
    return(self::Exists($strName));
  }
  
  
  /**
   * Resource Manager Get method checks if an specified element exists onto the container and returns it.
   * 
   * @param string $strName Identifier of the object or element to obtain.
   * 
   * @return mixed Object instance or element itself or null if it does not exist.
   * 
   **/
  public function Get($strName)
  {
    
    $ret = null;
    
    if(self::Exists($strName))
    {
      $strName = strtolower($strName);
      
      // Check if it´s a closure:
      //if($this->data[$strName] instanceof Closure))
      if(is_object($this->data[$strName]) && method_exists($this->data[$strName], '__invoke'))
        $ret = $this->data[$strName]($this);
      else
        $ret = $this->data[$strName];
    }
    
    return($ret);
      
  }
  
  /**
   * Resource Manager OffsetGet method checks if an specified element exists onto the container and returns it.
   * 
   * Alias method of Get.
   *       
   * @param string $strName Identifier of the object or element to obtain.
   * 
   * @return mixed Object instance or element itself or null if it does not exist.
   * 
   **/
  public function OffsetGet($strName)
  {
    return(self::Get($strName));
  }
   
  /**
   * ResourceManager Delete method erases an object instance or element from the container.
   * 
   * @param string $strName Identifier of the object/element to erase.
   * 
   * @return boolean True if object/element has been deleted from the container.
   * 
   **/    
  public function Delete($strName)
  {
    if(self::Exists($strName))
    {
      unset($this->data[strtolower($strName)]);
    }
      
    return(true);
  }
  
  /**
   * ResourceManager OffsetUnset method erases an object instance or element from the container.
   * 
   * Alias method of Delete.
   *       
   * @param string $strName Identifier of the object/element to erase.
   * 
   * @return boolean True if object/element has been deleted from the container.
   * 
   **/
  public function OffsetUnset($strName)
  {
    return(self::Delete($strName));
  }    
  
  /**
   * Resource Manager Raw method checks if an specified element exists onto the container and returns it in raw mode.
   * No servie mode.   
   * 
   * @param string $strName Identifier of the object or element to obtain.
   * 
   * @return mixed Element itself, even if it´s a closure.r element itself or null if it does not exist.
   * 
   **/
  public function Raw($strName)
  {
    $ret = null;
    
    if(self::Exists($strName))
      $ret = $this->dataMap[strtolower($strName)];  
    
    return($ret);
  }
  
  /**
   * Resource Manaager Share method stores a new SHARED element onto the bucket based on its name.
   * 
   * The added element might be an instance of a known object and it will be shared on every instance obtention via Get
   * method.
   * 
   * @param string $strName Name of the object/element to store.
   * @param mixed  $data Closure to store.
   * 
   * @return nothing.
   * 
   * @todo Do I have to return the added object ???
   *       
   **/
  public function Share($strName, Closure $callable)
  {
    $obj = function ($c) use ($callable) 
           {  
              static $object = null;

              if (null === $object) { 
                $object = $callable($c); 
              }

              return $object;
           };
    
    self::Add($strName, $obj);                                         
  }    
  
  // ---------------------- GETTER ------------------------- //
  // ------------------------------------------------------- //
  
  /**
   * Resource Manager strClassName GETTER method.
   * 
   * @return string class name.      
   *  
   **/
  public function GetStrClassName()
  {
    return($this->strClassName);
  }
  
  /**
   * Resource Manager strClassVersion GETTER method.
   * 
   * @return string class version.      
   *  
   **/
  public function GetStrClassVersion()
  {
    return($this->strClassVersion);
  }
  
  /**
   * Resource Manager data GETTER method.
   * 
   * @return array Associative array of elements.      
   *  
   **/
  public function GetData()
  { 
    return($this->data);
  }

  /**
   * Resource Manager key GETTER method.
   * 
   * @return array List of element keys onto the container.      
   *  
   **/ 
  public function GetKeys()
  {
    return(array_keys($this->data));
  }
  
  
  
     
}

?>
