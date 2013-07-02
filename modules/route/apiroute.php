<?php


/**
 * Hydra apiroute class.
 *  
 * This class manages route entries obtained from class annotations.
 * Implementation of IRoute. 
 *    
 * @version 0.31.0.0.1 
 * @author  Apnow
 * 
 **/
class ApiRoute implements IRoute
{
  // ---------------------- ATTRIBUTES --------------------- //
  // ------------------------------------------------------- //
  
  // Public class attributes:
  public $strClassName     = __CLASS__;
  public $strClassVersion  = '0.31.1';
  
  // Protected class attributes:
  protected $data          = null;
  
  // Private class attributes:
  private $hndParser        = null;
  
  // ---------------------- CONS & DES --------------------- //
  // ------------------------------------------------------- //
  
  /**
   * ApiRoute router class default constructor.
   * 
   * 
   **/ 
  public function __construct(Iparser $annParser, ICollection $collection=null)
  {
    //echo(__METHOD__." => Class default constructor.<br />");
    
    $this->hndParser = $annParser;
    $this->data = isset($collection) ? $collection : array(); 
  }
  
  /**
   * ApiRoute router class default destructor.
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
    unset($this->annParser);
  }
  
  // ---------------------- METHOD ------------------------- //
  // ------------------------------------------------------- //

  /**
   * Router Add method stores a new route entry identified by name onto the api based routing system.
   *       
   * @param string $strMethod Method type of the route to add onto the Route System.
   * @param string $strName Name of the route entry to add onto the Route System.
   * @param mixed  $data Route entry info to add.
   * 
   * @return boolean True if route information has been added. false otherwise.
   *    
   * @todo Do we have to check and construct $this->data as a concrete structure definition???.
   *          
   **/  
  public function Add($strMethod, $strName, $data)
  {
    $bRet = false;
    
    if($strMethod && $strName)
    {
      $this->data[strtoupper($strMethod)][strtolower($strName)][] = $data;
      $bRet = true;
    }
    
    return($bRet);
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
      $ret = isset($this->data[$strName]) ? true : false;
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
   * Router Match method matches received resource locator parameter with all available routes onto the api router.
   * 
   * @param string $strUrl Resource locator. Should be an string.
   * @param string $strMethod Method to get 
   * 
   * @return array Info of the controller/Action and params to execute.
   * 
   * @todo Permit array structured url???    
   **/
  public function Match($strUrl, $strMethod='GET')
  {
    $arrData  = null;
    $bMatch   = false;
    
    //echo(__METHOD__." => Searching match for ".$strUrl."<br />");
      
    switch($strMethod)
    {
      // POST
      case 'POST':
      break;
        
      // PUT
      case 'PUT':
      break;
        
      // DELETE
      case 'DELETE':
      break;
        
      // GET
      case 'GET':
      default:
      
        if(isset($this->data['GET']))
        {
          foreach($this->data['GET'] as $strRouteName => $routeData)
          { 
            if(self::Check($strUrl, $routeData))
            {
              $arrData  = $routeData;
              $bMatch   = true;
            }
          }
        }
       
      break;
    }
    
    
    if(!$bMatch)
    {
      if($arrData = self::ReflectMatch($strUrl, $strMethod))
        $bMatch = true;
      else
      {
        trigger_error("No route availabe for ".$strUrl);
      }
    
    }
    
    return($arrData);
  }
  
  private function ReflectMatch($url, $strMethod)
  {
    //echo(__METHOD__." => Reflection match  for ".$url."<br />");
    //echo(__METHOD__." => Reflection method for ".$strMethod."<br />");
    
    $arrData = null;
    
    $arrUrl = explode('/', trim($url, '/'));
    
    if($arrUrl[0])
    {
      //echo(__METHOD__." => Checking if ".$arrUrl[0]." exists.<br/>");

        
      if(kernel::GetDIC()['library']->Exists($arrUrl[0]))
      {
        // Reflect class:
        $objRefl = new ReflectionClass ($arrUrl[0]);
        
        if(is_object($objRefl))
        {
          $arrMethods = $objRefl->GetMethods();
          
          foreach($arrMethods as $key => $method)
          {
            // check if it is a callable method && !constructor && !destructor:
            if($method->isPublic() && !$method->isConstructor() && !$method->isDestructor())
            {
              $arrRouteData = array();
              
              $arrParseDoc = $this->hndParser->Parse($method->getDocComment());
              
              if(isset($arrParseDoc['route']))
              {
                
                foreach($arrParseDoc['route'] as $routeKey => $routeData)
                {
                  $arrRouteData['uri']        = $routeData['pattern'];
                  $arrRouteData['params']     = self::BuildParams($method->getParameters(), $arrParseDoc['params']); 
                  $arrRouteData['regexp']     = self::BuildRegExp($routeData['pattern'], $arrParseDoc['params']);                    
                  $arrRouteData['controller'] = $objRefl->getName(); //$arrUrl[0];
                  $arrRouteData['action']     = $method->getName();  
                   
                  self::Add($routeData['method'], $arrUrl[0].'_'.strtolower($method->getName()), $arrRouteData);
                  
                  if(($routeData['method'] == $strMethod) && self::Check($url, $arrRouteData))
                  {
                    self::BuildParamValue($url, $strMethod, $arrRouteData);
                    $arrData = $arrRouteData;
                    break;
                  }
                  
                }
              }
            }
          }
          
        }  
      }
          
        
    }
    
    return($arrData);
  }
  
  private function Check($url, array $arrRouteData)
  {
    $bRet = false;
    
    if(isset($arrRouteData['regexp']))
    {
      if(preg_match($arrRouteData['regexp'], $url))
        $bRet = true;
    }
    
    return($bRet);
  }
  
  private function BuildRegExp($strPattern=null, array &$arrParam=null)
  {
    //echo(__METHOD__." => Pattern: ".$strPattern."<br />");
    
    $strRegExp = $strPattern;
    
    if(isset($strPattern))
    {
      foreach($arrParam as $paramId => $paramData)
      {
        $strFilter = '{'.$paramId.'}';
        
        if(strpos($strRegExp, $strFilter))
        {
          $strReplace = null;
          switch(strtolower($paramData['type']))
          {
            case 'integer':
            case 'int':
              $strReplace = 'd+';
            break;
              
            case 'string':
            case 'str':
            default:
              $strReplace = 'w+';
            break;
          }
          
          $strRegExp = str_replace($strFilter, '\\'.$strReplace, $strRegExp);
        }
      }
      
      $strRegExp = str_replace('/', '\/', $strRegExp);
      $strRegExp = '/^'.$strRegExp.'$/';
      
    }
    
    return($strRegExp);
    
  }
  
  private function BuildParams(array $arrParamObj=null, array &$arrParamDoc=null)
  {
    $arrParams = null;
    
    if(isset($arrParamObj))
    {
      foreach($arrParamObj as $paramKey => $paramObj)
      {
        
        $strName  = strtolower($paramObj->getName());
        
        if($paramObj->isDefaultValueAvailable())
          $arrParams[$strName]  = $paramObj->getDefaultValue();
        else
          $arrParams[$strName]  = null;
      }    
    }
    
    return($arrParams);
  }
  
  private function BuildParamValue($strUrl, $strMethod=null, array &$arrRouteData=null)
  {
    //echo(__METHOD__." => Build Param value for: ".$strUrl."<br />");
   
        
    switch($strMethod)
    {
      case 'GET':
      default:
        
        // 1 - Check from url:
        $strUrlPath = parse_url($strUrl, PHP_URL_PATH);
        $arrUrlPath = explode('/', $strUrlPath);
        $arrPatternPath =  explode('/', $arrRouteData['uri']);
        
        foreach($arrPatternPath as $iPatternPos => $strVarName)
        {
          $strVarName = strtolower(trim($strVarName, '{}'));
          
          if(isset($arrRouteData['params'][$strVarName]) || array_key_exists($strVarName, $arrRouteData['params']))
          {
            $arrRouteData['params'][$strVarName] = $arrUrlPath[$iPatternPos];
            break;
          }
          
        }
          
        // 2 - Check from $_GET superglobal:
        if(isset($_GET) && !empty($_GET))
        {
          foreach($_GET as $strParamName => $strParamValue)
          {
            $bMatch = false;
            
            $strParamName = strtolower($strParamName);
            
            if(isset($arrRouteData['params'][$strParamName]) || array_key_exists($strParamName, $arrRouteData['params']))
            {
                $arrRouteData['params'][$strParamName] = $strParamValue;
                $bMatch = true;
                break;
            }
            
            if(!$bMatch)
              $arrRouteData['params'][$strParamName]['value'] = $strParamValue;
            
          }
        }
        
      break;
      
      case 'POST':
      
        // 2 - Check from $_POST superglobal:
        if(isset($_POST) && !empty($_POST))
        {
          foreach($_POST as $strParamName => $strParamValue)
          {
            $bMatch = false;
            
            $strParamName = strtolower($strParamName);
            
            if(isset($arrRouteData['params'][$strParamName]) || array_key_exists($strParamName, $arrRouteData['params']))
            {
                $arrRouteData['params'][$strParamName] = $strParamValue;
                $bMatch = true;
                break;
            }
            
            if(!$bMatch)
              $arrRouteData['params'][$strParamName] = $strParamValue;
            
          }
        }
        
      break;
      
      case 'PUT':
      break;
      
      case 'DELETE':
      break;
    }
    
  }
  
  /* PARAMS WITH ALL INFO, INCLUDING POSITION:
  private function BuildParams(array $arrParamObj=null, array &$arrParamDoc=null)
  {
    $arrParams = null;
    
    if(isset($arrParamObj))
    {
      foreach($arrParamObj as $paramKey => $paramObj)
      {
        $iPos     = $paramObj->getPosition();
        $strName  = strtolower($paramObj->getName());
        
        $arrParams[$iPos]['name']       = $strName;
        $arrParams[$iPos]['type']       = isset($arrParamDoc[$strName]) ? $arrParamDoc[$strName]['type'] : 'undefined';
        $arrParams[$iPos]['desc']       = isset($arrParamDoc[$strName]) ? $arrParamDoc[$strName]['desc'] : null;
        $arrParams[$iPos]['optional']   = $paramObj->isOptional();
        
        if($paramObj->isDefaultValueAvailable())
          $arrParams[$iPos]['default']  = $paramObj->getDefaultValue();
        
      }    
    }
    
    return($arrParams);
  }
  */
 
  /*
  private function BuildParamValue($strUrl, $strMethod=null, array &$arrRouteData=null)
  {
    //echo(__METHOD__." => Build Param value for: ".$strUrl."<br />");
   
        
    switch($strMethod)
    {
      case 'GET':
      default:
        
        // 1 - Check from url:
        $strUrlPath = parse_url($strUrl, PHP_URL_PATH);
        $arrUrlPath = explode('/', $strUrlPath);
        $arrPatternPath =  explode('/', $arrRouteData['uri']);
        
        foreach($arrPatternPath as $iPatternPos => $strVarName)
        {
          $strVarName = strtolower(trim($strVarName, '{}'));
          foreach($arrRouteData['params'] as $iPos => $arrParamData)
          {
            
            if($arrParamData['name'] === $strVarName)
            {
              $arrRouteData['params'][$iPos]['value'] = $arrUrlPath[$iPatternPos];
              break;
            }
            
          }
          
        }
          
        // 2 - Check from $_GET superglobal:
        if(isset($_GET) && !empty($_GET))
        {
          foreach($_GET as $strParamName => $strParamValue)
          {
            $bMatch = false;
            
            foreach($arrRouteData['params'] as $iPos => $arrParamData)
            {
              if($arrParamData['name'] === strtolower($strParamName))
              {
                $arrRouteData['params'][$iPos]['value'] = $strParamValue;
                $bMatch = true;
                break;
              }
            }
            
            if(!$bMatch)
              $arrRouteData['params'][] = array('name' => strtolower($strParamName), 'value' => $strParamValue);
            
          }
        }
        
      break;
      
      case 'POST':
      
        // 2 - Check from $_POST superglobal:
        if(isset($_POST) && !empty($_POST))
        {
          foreach($_POST as $strParamName => $strParamValue)
          {
            $bMatch = false;
            
            foreach($arrRouteData['params'] as $iPos => $arrParamData)
            {
              if($arrParamData['name'] === strtolower($strParamName))
              {
                $arrRouteData['params'][$iPos]['value'] = $strParamValue;
                $bMatch = true;
                break;
              }
            }
            
            if(!$bMatch)
              $arrRouteData['params'][] = array('name' => strtolower($strParamName), 'value' => $strParamValue);
            
          }
        }
        
      break;
      
      case 'PUT':
      break;
      
      case 'DELETE':
      break;
    }
    
  }
  */
  
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
