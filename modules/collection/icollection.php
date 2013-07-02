<?php


/**
 * Hydra collection interface definition.
 *  
 * This interface defines basic methods for all store systems.
 * Defined basic methods are:
 *  - Add: Adds a new element to the collection.
 *  - Exists: Checks if a element with a known name is on the collection.
 *  - Get: Get an element with a known identifier.
 *  - Delete: Erase an element with a known identifier.     
 *    
 * @version 0.31.0.0.1 
 * @author  Apnow
 * 
 **/
 
interface ICollection
{
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
  public function Add($strName, $data);
  
  /**
   * Collection Get method checks if an specified element exists onto the container and returns it.
   * 
   * @param string $strName Identifier of the element to obtain.
   * 
   * @return mixed Element itself or null if it does not exist.
   * 
   **/
  public function Get($strName);
  
  /**
   * Collection Exists method checks if an specified element exists onto the container.
   * 
   * @param string $strName Identifier of the element to check.
   * 
   * @return  boolean True if identifier exists onto the container, false otherwise.
   * 
   **/
  public function Exists($strName);
  
  /**
   * Container Delete method erases an element from the container.
   * 
   * @param string $strName Identifier of the element to erase.
   * 
   * @return boolean True if element has been deleted from the container.
   * 
   **/
  public function Delete($strName);
  
  /**
   * Container data GETTER method.
   * 
   * @return array Associative array of elements.      
   *  
   **/
  public function GetData();
}

?>
