<?php


/**
 * Hydra memlog class.
 *  
 * This class manages log entries into system memory.
 * Implementation of ILog. 
 *    
 * @version 0.31.0.0.1 
 * @author  Apnow
 * 
 **/
class MemLog implements ILog
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
   * MemLog logger class default constructor.
   * 
   * 
   **/ 
  public function __construct(ICollection $collection=null)
  {
    echo(__METHOD__." => Class default constructor.<br />");
    
    $this->data = isset($collection) ? $collection : array(); 
  }
  
  /**
   * MemLog logger class default destructor.
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
   * Logger Add method stores a new entry identified by name onto the logger system.
   *       
   * @param string $strName Name of the entry to add onto the logger.
   * @param mixed  $data Log entry to add.
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
   * Logger Get method checks if an specified entry exists onto the logger and returns it.
   * 
   * @param string $strName Identifier of the entry to obtain.
   * 
   * @return mixed Entry itself or null if it does not exist.
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
   * Logger Exists method checks if an specified entry exists onto the system.
   * 
   * @param string $strName Identifier of the entry to check.
   * 
   * @return  boolean True if identifier exists onto the logger, false otherwise.
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
   * Logger Delete method erases an entry from the system.
   * 
   * @param string $strName Identifier of the entry to erase.
   * 
   * @return boolean True if entry has been deleted from the container.
   * 
   **/
  public function Delete($strName)
  {
    if(self::Exists($strName))
      unset($this->data[strtolower($strName)]);
    
    return(true);
  } 
     
  // ---------------------- GETTER ------------------------- //
  // ------------------------------------------------------- //
  
  /**
   * Logger data GETTER method.
   * 
   * @return array Associative array of log entries.      
   *  
   **/
  public function GetData()
  {
    return($this->data);
  }
  
  /**
   * Logger data key GETTER method.
   * 
   * @return array List of defined entry identifiers on the logger system.      
   *  
   **/
  public function GetKeys()
  {
    return(array_keys($this->data));
  }

  /**
   * Logger strClassName GETTER method.
   * 
   * @return string class name.      
   *  
   **/
  public function GetStrClassName()
  {
    return($this->strClassName);
  }
  
  /**
   * Logger strClassVersion GETTER method.
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
