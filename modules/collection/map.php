<?php


/**
 * Hydra map class.
 *  
 * This class stores all type of elements (objects, arrays, strings, etc) to reuse them accross all the web.
 * Implementation of ICollection. 
 *    
 * @version 0.31.0.0.1 
 * @author  Apnow
 * 
 **/
 
class Map implements ICollection, ArrayAccess
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
   * Map collection class default constructor.
   * 
   * 
   **/ 
  public function __construct()
  {
    //echo(__METHOD__." => Class default constructor.<br />");
    
    $this->data = array();
    
  }
  
  /**
   * Map collection class default destructor.
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
   * Collection Add method stores a new element identified by name onto the container.
   * 
   * Element can be: arrays, strings, objects, etc.
   *       
   * @param string $strName Name of the element to store.
   * @param mixed  $data Object/Element to the store.
   * 
   * @return nothing.
   *       
   **/
  public function Add($strName, $data)
  {
    if($strName)
      $this->data[strtolower($strName)] = $data;
  }
  
  /**
   * Collection Add method stores a new element identified by name onto the container.
   * 
   * Element can be: arrays, strings, objects, etc.
   * Alias method of Add.   
   *       
   * @param string $strName Name of the element to store.
   * @param mixed  $data Object/Element to the store.
   * 
   * @return nothing.
   *       
   **/
  public function OffsetSet($strName, $data)
  {
    self::Add($strName, $data);
  }
  
  /**
   * Collection Get method checks if an specified element exists onto the container and returns it.
   * 
   * @param string $strName Identifier of the element to obtain.
   * 
   * @return mixed Elemrnt itself or null if it does not exist.
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
   * Collection Get method checks if an specified element exists onto the container and returns it.
   * 
   * Alias method of Get.
   *        
   * @param string $strName Identifier of the element to obtain.
   * 
   * @return mixed Elemrnt itself or null if it does not exist.
   * 
   **/
  public function OffsetGet($strName)
  {
    return(self::Get($strName));
  }
  
  /**
   * Collection Exists method checks if an specified element exists onto the container.
   * 
   * @param string $strName Identifier of the element to check.
   * 
   * @return  boolean True if identifier exists onto the container, false otherwise.
   * 
   **/
  public function Exists($strName)
  {
    $ret = null;
    
    if($strName)
    {
      $strName = strtolower($strName);
      $ret = ($this->data[$strName]) ? $this->data[$strName] : null;
    }
      
    return($ret);
  }
  
  /**
   * Collection Exists method checks if an specified element exists onto the container.
   * 
   *  Alias method of Exists.
   *        
   * @param string $strName Identifier of the element to check.
   * 
   * @return  boolean True if identifier exists onto the container, false otherwise.
   * 
   **/
  public function OffsetExists($strName)
  {
    return(self::Exists($strName));
  }
  
  /**
   * Container Delete method erases an element from the container.
   * 
   * @param string $strName Identifier of the element to erase.
   * 
   * @return boolean True if element has been deleted from the container.
   * 
   **/
  public function Delete($strName)
  {
    if(self::Exists($strName))
      unset($this->data[strtolower($strName)]);
    
    return(true);
  }
  
  /**
   * Container Delete method erases an element from the container.
   * 
   *  Alias method of Delete.
   *        
   * @param string $strName Identifier of the element to erase.
   * 
   * @return boolean True if element has been deleted from the container.
   * 
   **/
  public function OffsetUnset($strName)
  {
    return(self::Delete($strName));
  } 
     
  // ---------------------- GETTER ------------------------- //
  // ------------------------------------------------------- //
  
  /**
   * Map data GETTER method.
   * 
   * @return array Associative array of stored elements.      
   *  
   **/
  public function GetData()
  {
    return($this->data);
  }
  
  /**
   * Map arrData key GETTER method.
   * 
   * @return array List of defined element identifiers on the collection.      
   *  
   **/
  public function GetKeys()
  {
    return(array_keys($this->data));
  }

  /**
   * Map strClassName GETTER method.
   * 
   * @return string class name.      
   *  
   **/
  public function GetStrClassName()
  {
    return($this->strClassName);
  }
  
  /**
   * Map strClassVersion GETTER method.
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
