<?php


/**
 * Hydra request interface definition.
 *  
 * This interface defines basic methods for all request subsystems.
 * Defined basic methods are:
 *  - Run: Execute defined request.
 *  - GetData:Return obtained request data.
 *    
 * @version 0.31.0.0.1 
 * @author  Apnow
 * 
 **/
 
interface IRequest
{
  /**
   * Request Run method executes the request.
   *       
   * 
   * @return nothing.
   *       
   **/  
  //private function Run();
  
  
  /**
   * Router data GETTER method.
   * 
   * @return array Associative array of entries.      
   *  
   **/
  public function GetData(); 
  
}
 
?> 
