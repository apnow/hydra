<?php


/**
 * Hydra logging interface definition.
 *  
 * This interface defines basic methods for all logger systems.
 * Defined basic methods are:
 *  - Add: Adds a new log entry to the logger.
 *  - Exists: Checks if a log entry with a known name is logged.
 *  - Get: Get a log entrye defined by a known identifier.
 *  - Delete: Erase a  log entry with a known identifier.     
 *    
 * @version 0.31.0.0.1 
 * @author  Apnow
 * 
 **/
 
interface ILog
{
  /**
   * Logger Add method stores a new entry identified by name onto the logger system.
   *       
   * @param string $strName Name of the entry to add onto the logger.
   * @param mixed  $data Log entry to add.
   * 
   * @return nothing.
   *       
   **/  
  public function Add($strName, $data);
  
  /**
   * Logger Get method checks if an specified entry exists onto the logger and returns it.
   * 
   * @param string $strName Identifier of the entry to obtain.
   * 
   * @return mixed Entry itself or null if it does not exist.
   * 
   **/
  public function Get($strName);  
  
  /**
   * Logger Exists method checks if an specified entry exists onto the system.
   * 
   * @param string $strName Identifier of the entry to check.
   * 
   * @return  boolean True if identifier exists onto the logger, false otherwise.
   * 
   **/
  public function Exists($strName);
  
  /**
   * Logger Delete method erases an entry from the system.
   * 
   * @param string $strName Identifier of the entry to erase.
   * 
   * @return boolean True if entry has been deleted from the container.
   * 
   **/
  public function Delete($strName);
  
  /**
   * Logger data GETTER method.
   * 
   * @return array Associative array of entries.      
   *  
   **/
  public function GetData(); 
}

?>
