<?php


/**
 * Hydra routing interface definition.
 *  
 * This interface defines basic methods for all routing systems.
 * Defined basic methods are:
 *  - Add: Adds a new route entry to the routing system.
 *  - Exists: Checks if an entry exists onto the routing system.
 *  - Get: Get a route entry defined onto the routing system.
 *  - Delete: Erase an entry from the routing system.     
 *    
 * @version 0.31.0.0.1 
 * @author  Apnow
 * 
 **/
 
interface IRoute
{
  /**
   * Router Add method stores a new route entry identified by name onto the logger system.
   *       
   * @param string $strMethod Method type of the route to add onto the Route System.
   * @param string $strName Name of the route entry to add onto the Route System.
   * @param mixed  $data Route entry info to add.
   * 
   * @return nothing.
   *       
   **/  
  public function Add($strMethod, $strName, $data);
  
  
  /**
   * Router Get method checks if an specified route entry exists onto the routing system and returns it.
   * 
   * @param string $strName Identifier of the route entry to obtain.
   * 
   * @return mixed Route entry itself or null if it does not exist.
   * 
   **/
  public function Get($strName);
  
  /**
   * Router Exists method checks if an specified route entry exists onto the system.
   * 
   * @param string $strName Identifier of the route entry to check.
   * 
   * @return  boolean True if identifier exists onto the router, false otherwise.
   * 
   **/
  public function Exists($strName);
  
  /**
   * Router Delete method erases a route entry from the system.
   * 
   * @param string $strName Identifier of the route entry to erase.
   * 
   * @return boolean True if route entry has been deleted from the container.
   * 
   **/
  public function Delete($strName);
  
  /**
   * Router Match method matches received resource locator parameter with all available routes onto the annotation router.
   * 
   * @param mixed $url Resource locator. Shiuld be an string or an array with parameters.
   * 
   * @return array Info of the controller/Action and params to execute.
   * 
   **/
  public function Match($url);
  
  /**
   * Router data GETTER method.
   * 
   * @return array Associative array of entries.      
   *  
   **/
  public function GetData(); 
  
}
 
?>
