<?php


/* Security check */
defined('BASE') or die('Security Error: Cannot call class directly.');

/**
 * Hydra library class.
 * 
 * This class manages the inclusion and existence of requiered classes and files.
 * It implements multiple methods to find and include a file: Path Based, Recursive autoloading.
 *    
 * @version 0.31.1 
 * @author  Apnow
 * 
 **/
 
class Library
{
  // Constants:
  const   CLASS_EXTENSION     = '.php';
  
  // Public class attributes:
  public $strClassName     = __CLASS__;
  public $strClassVersion  = '0.31.1';
  
  // Protected class attributes:
 
  
  protected $arrPathMap       = null;
  protected $arrClassMap      = null;
  protected $arrIncludedMap   = null;
  
  // private class attributes:
  private   $arrAutoloadMap   = null;
  
  // ---------------------- CONS & DES --------------------- //
  // ------------------------------------------------------- //
  
  /**
   * Hydra Library class constructor.
   *
   * @param array $arrPathData Array with paths to add to the library.
   *         
   * @version 0.31.0.0.1 
   * @author  Apnow
   * 
   **/
  public function __construct(array $arrPathData=null)
  {
    //echo(__METHOD__.'() => Class default constructor.<br />');  
      
    // Load autoload systems:   
    
    $this->arrPathMap[]   = BASE;           // Start with BASE path:
    $this->arrClassMap    = array();
    $this->arrAutoloadMap = array();
    $this->arrIncludedMap = array();
    
    // Add parameter passed to the library (Path info):
    self::AddPath($arrPathData);
    
    self::RegisterAutoload(array(__CLASS__, 'Recursive' ));
    self::RegisterAutoload(array(__CLASS__, 'Path'      ));
    self::RegisterAutoload(array(__CLASS__, 'Map'       ));
  }
  
  /**
   * Hydra Library class default destructor.
   * 
   * 
   **/
  public function __destruct()
  {
    //echo(__METHOD__.'() => Class default destructor.<br />');
    
    // Public attributes:
    unset($this->strClassName);
    unset($this->strClassVersion);
    
    // Protected attributes: 
    unset($this->arrPathMap);
    unset($this->arrClassMap);
    unset($this->arrIncludedMap);
    
    // Private attributes:
    unset($this->arrAutoloadMap);
  }
  
  // ---------------------- METHOD ------------------------- //
  // ------------------------------------------------------- //
 
 /**
   * Library Load class or interface method.
   * 
   * @param string $strClasName Class or Interface name to load. 
   * @param string $strPath Path where to find passed class or interface file.   
   * @param booleaa $bInclude True if class interface has to be included, false otherwise.
   * 
   * @return boolean True if class or interface has been loaded, false otherwise.       
   **/
  public function Load($strClassName, $strPath=null, $bInclude=true)
  {
    $bRet = false;
    
    if($strClassName && $strPath)
    {
      $strClassName = strtolower($strClassName);
      
      $strFullName = $strPath.$strClassName.self::CLASS_EXTENSION;
      
      if(is_readable(DOC_ROOT.$strFullName))
      {
        if($bInclude && include(DOC_ROOT.$strFullName))
        {
          
          echo(__METHOD__." => Including ".$strFullName."<br />");
          
          $strClassName = strtolower($strClassName);
          $this->arrIncludedMap[$strClassName] = true;
        }
        
        $bRet = true;
      }
    }
    
    return($bRet);
  }
 
 /**
   * Library Exists class or interface method.
   * 
   * @param string $strClasName Class or Interface name to check if exists. 
   * @param string $strPath Path where to find passed class or interface file.   
   * @param booleaa $bInclude True if class interface has to be included, false otherwise.
   * 
   * @return boolean True if class or interface has been loaded, false otherwise.       
   **/
  public function Exists($strClassName, $strPath=null)
  {
      
      if(self::Map($strClassName, false))
        return(true);
      
      if(self::Path($strClassName, $strPath, false))
        return(true);
      
      if(self::Recursive($strClassName, $strPath, false))
        return(true);
      
      return(false);
  }
 /**
   * Library AddPath method adds a new path into the array of paths.
   * 
   * @param string|array $path Path string or an array with multiple paths.         
   * 
   * @return boolean True if path has been added into the path map, false otherwise.       
   **/
  public function AddPath($path=null)
  {
    $bRet = false;
    
    if($path)
    {
      if(is_string($path))
      {
        $path = BASE.$path;

        if(!array_search($path, $this->arrPathMap))
          $this->arrPathMap[] = $path;
      }
      
      if(is_array($path))
      {
        foreach($path as $pathName => $pathData)
        { 
          $pathData = BASE.$pathData;
          
          if(!array_search($pathData, $this->arrPathMap))
            $this->arrPathMap[] = $pathData;
          
        }
        
      }
      
      $bRet = true;
    }
    
    return($bRet);
  }
   
 /**
   * Library autoload algorithm registration method.
   * 
   * @param array $arrCallback Array with the metod to use to autoload files.
   * 
   * @return boolean True if method has been registered for autoloading, false otherwise.       
   **/
   
  public function RegisterAutoload(array $arrCallBack=null)
  {
    $bRet = false;
    
    // Register method for autoload:
    if($arrCallBack && spl_autoload_register($arrCallBack, true, true))
    {
      array_unshift($this->arrAutoloadMap, $arrCallBack);
      $bRet = true;  
    }
    
    return($bRet);
  }
  
  private function Map($strClassName, $bInclude=true)
  {
    //echo(__METHOD__." => Trying to load ".$strClassName."<br />");
    
    $bRet = false;
    
    $strClassName = strtolower($strClassName);
    
    if(isset($this->arrClassMap[$strClassName]))
    {
        $bRet = self::Load($strClassName, $this->arrClassMap[$strClasName], $bInclude);
    }
    
    return($bRet);
  }
  
  private function Path($strClassName, $strPath=null, $bInclude=true)
  {
    //echo(__METHOD__." => Trying to load ".$strClassName."<br />");
    
    $bRet = false;
    
    if($strPath)
    {
      if(self::Load($strClassName, $strPath, $bInclude))
        $bRet = true;
    }
    else
    {
      foreach($this->arrPathMap as $key => $strPath)
      {
        if($bRet = self::Load($strClassName, $strPath, $bInclude))
          break;
      }
    }
    
    return($bRet);
    
  }
  
  private function Recursive($strClassName, $bInclude=true)
  {
    //echo(__METHOD__." => Trying to load ".$strClassName."<br />");
    
    $bRet = false;
	
	  $index = 0;
    
      while(count($this->arrPathMap) > $index && $bRet == false)
      {
        $strPath = $this->arrPathMap[$index];
        
        foreach(glob(DOC_ROOT.$strPath.'*') as $item)
        {
      
          if (is_dir($item) && ($item !== '.') && ($item !== '..'))
          {
            self::AddPath(str_replace(DOC_ROOT.BASE, '', $item).PS);
          }
          else
          {
            // Get file name and check its extension:
            $strFileNameExt = str_replace(DOC_ROOT.$strPath, '', $item);
            
            if(substr($strFileNameExt, -strlen(self::CLASS_EXTENSION)) === self::CLASS_EXTENSION)
            {
                $strFileName = substr($strFileNameExt, 0, strlen($strFileNameExt) - strlen(self::CLASS_EXTENSION));
                $this->addClassMap[strtolower($strFileName)] = $strPath;
            }
              
          }
        }
        
        $bRet = self::Load($strClassName, $strPath, $bInclude);
        
        ++$index;
      }
      
      return($bRet);
    
  }
  
  
  // ---------------------- GETTER ------------------------- //
  // ------------------------------------------------------- //
  
  /**
   * Library strClassName GETTER method.
   * 
   * @return string class name.      
   *  
   **/
  public function GetStrClassName()
  {
    return($this->strClassName);
  }
  
  /**
   * Library strClassVersion GETTER method.
   * 
   * @return string class version.      
   *  
   **/
  public function GetStrClassVersion()
  {
    return($this->strClassVersion);
  }
  
  /**
   * Library arrClassMap GETTER method.
   * 
   * @return array Associative array with included class info.      
   *  
   **/
  public function GetArrClassMap()
  {
    return($this->arrClassMap);
  }
  
  /**
   * Library arrClassMapKeys GETTER method.
   * 
   * @return array List of keys defined on the class array.      
   *  
   **/
  public function GetArrClassKeys()
  {
    return(array_keys($this->arrClassMap));
  }
  
  /**
   * Library arrPathMap GETTER method.
   * 
   * @return array Associative array with available paths for class/interface inclusion.      
   *  
   **/
  public function GetArrPathMap()
  {
    return($this->arrPathMap);
  }
  
  
}

?>
