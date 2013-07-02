<?php


/**
 * Hydra parser interface.
 * 
 * This interface defines basic methods that all parse system classes has to implement.
 * Defined methods: Parse, GetData. 
 *    
 * @version 0.31.0.0.1 
 * @author  Apnow
 * 
 **/
 
interface IParser
{
  /**
   * Method that parses passed information to an associative array content.
   * 
   * @param string $strData Text to parse.
   * 
   * @return array|null Associative array with tagged comment information if parse success, false otherwise.
   * 
   **/                      
  public function Parse($strData=null);
  
  /**
   * Annotation Parser data GETTER method.
   * 
   * @return array|null Full parsed data in an associative array.      
   *  
   **/
  public function GetData();
  
}

?>
