<?php


// -------------------- SECURITY CHECK ------------------- //
// ------------------------------------------------------- //

defined('BASE') or die('Security Error: Cannot call class directly.');  


/**
 * Hydra Route system Factory class.
 *  
 * This class defines everything about route management:
 *  - Route management systems.
 *  - Route matching options.
 *  - Methods to Add,Get,Delete and Check route entries.  
 *     
 * Available route subsystems:
 * - StaticRouter.  
 * - ApiRouter.   
 *   
 *      
 * @version 0.31.0.0.1 
 * @author  Apnow
 * 
 * @todo Define matching priorities. 
 **/
 
class RouteManager
{
  // ---------------------- ATTRIBUTES --------------------- //
  // ------------------------------------------------------- //
  
  // Public class attributes:
  public $strClassName            = __CLASS__;
  public $strClassVersion         = '0.31.1';
  
  // Protected class attributes: 
  protected $arrMatchingPriority  = array('static', 'api');
   
  protected $data                 = array();
  
  protected $arrDefaultRoute      = array();
  
  // Private class attributes:
  
  // ---------------------- CONS & DES --------------------- //
  // ------------------------------------------------------- //
  
  /**
   * RouteManager class default constructor.
   * 
   * 
   **/ 
  public function __construct(array $arrDefaultRoute=null)
  {
    //echo(__METHOD__." => Class default constructor.<br />");
    self::Add('api', new ApiRoute(new AnnotationParser()));
    
    $this->arrDefaultRoute = isset($arrDefaultRoute) ? $arrDefaultRoute : null;
  }
  
  /**
   * RouteManager class default destructor.
   * 
   * 
   **/ 
  public function __destruct()
  {
    //echo(__METHOD__." => Class default destructor.<br />");
    
    // Public class attributes:
    unset($this->strClassName);
    unset($this->strClassVersion);
    
    // Protected class attributes:
    unset($this->arrMatchingPriority);
    unset($this->data);
    
    unset($this->arrDefaultRoute);
    
    // Private class attributes:
  }
  
  // ---------------------- METHOD ------------------------- //
  // ------------------------------------------------------- //
  
  /**
   * RouteManager Add Method.
   * 
   * Adds a new Routing system to the route manager. If it exists a routing system with the same name, we do not
   * add it to the management (What we do with the available routes on the old system ???).   
   * 
   * @param string $strName Routing system name.
   * @param object $router  New Routing object.
   *
   * @return boolean True if routing system has been added to the manager, false otherwise.
   *                           
   * 
   **/
  public function Add($strName, IRoute $route)
  {
    $ret = false;
    
    if(!self::Exists($strName))
    {
      $this->data[strtolower($strName)] = $route;
      $ret = true;  
    }
    
    return($ret);
  }
  
  /**
   * RouteManager Exists Method.
   * 
   * Checks if a specified route name exists onto the route manager system.
   * 
   * @param string $strName Routing system name.
   * @param object $router  New Routing object.
   *
   * @return boolean True if routing system has been added to the manager, false otherwise.
   *                           
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
   * RouteManager Gets Method.
   * 
   * Gets the route system instance from the route map.
   * 
   * @param string $strName Routing system name to get.
   *
   * @return object Route System instance if it exists, null otherwise.
   *                           
   * 
   **/
  public function Get($strName)
  {
    $ret = null;
    
    if(self::Exists($strName))
      $ret = $this->data[strtolower($strName)];
    
    return($ret);
  }
  
  /**
   * RouteManager Delete Method.
   * 
   * Deletes the route system instance from the route map and the list of routes for its instance.
   * 
   * @param string $strName Routing system name to delete.
   *
   * @return boolean True if instance has been deleted, false otherwise.
   *                           
   * 
   **/
  public function Delete($strName)
  {
    if(self::Exists($strName))
      unset($this->data[strtolower($strName)]);
    
    return(true);
  }
   
  /**
   * RouteManager match method.
   * 
   * Matches passed url/location to an available  route from the route list.
   * 
   * @param string $strHost Server name, or localhost as default.
   * @param string $strUrl Location or resource locator info.
   * @param string $strMethod Method to check resource locator info.
   * @param array  $arrData Additional data (agent, accept, language, encoding).
   * 
   * @return array Info of the controller/Action and params to execute.
   * 
   * @todo Do everything about match.                          
   * 
   **/
  public function Match($strHost='localhost', $strUrl=null, $strMethod='GET', array $arrData=null)
  {
    //echo(__METHOD__." => Route Server         : ".$strHost."<br />");
    //echo(__METHOD__." => Route Management for : ".$strUrl."<br />");
    //echo(__METHOD__." => Route Method         : ".$strMethod."<br />");
    //echo(__METHOD__." => Route Data           : <br />");
    //echo("<pre>");
    //  print_r($data);
    //echo("</pre>");  
    
    $arrRdo = null;
    
    echo("HOST:".$strHost."<br />");
    echo("<pre>");
      print_r($_SERVER);
    echo("</pre>");
    
    
    if(empty($strUrl) || $strUrl === PS || $strUrl === PS.INDEX)
    {
      // Get Default Route:
      $arrRdo = $this->arrDefaultRoute;
    }
    else
    {
      if(isset($this->arrDefaultRoute) && $this->arrDefaultRoute['pattern'] === $strUrl)
      {
        // Get Default Route:
        $arrRdo = $this->arrDefaultRoute;
      }
      else
      {
        foreach($this->arrMatchingPriority as $key => $strRouterName)
        {
          if($route= self::Get($strRouterName))
          {
            //echo(__METHOD__." => Route name: ".$strRouterName."<br />");
          
            if($arrRdo = $route->Match($strUrl, $strMethod))
            {
              //echo(__METHOD__." => MATCH!.<br />");
              break;
            }  
          }
        }
      }
      
    }
    
    if($arrRdo)
    {
      // Complete route info with: 
      $arrRdo['format']['agent']    = isset($arrData) && isset($arrData['agent'])    ? $arrData['agent']    : $_SERVER['HTTP_USER_AGENT'];
      $arrRdo['format']['accept']   = isset($arrData) && isset($arrData['accept'])   ? $arrData['accept']   : $_SERVER['HTTP_ACCEPT'];
      $arrRdo['format']['language'] = isset($arrData) && isset($arrData['language']) ? $arrData['language'] : $_SERVER['HTTP_ACCEPT_LANGUAGE'];
      $arrRdo['format']['encoding'] = isset($arrData) && isset($arrData['encoding']) ? $arrData['encoding'] : $_SERVER['HTTP_ACCEPT_ENCODING'];
    
      $arrRdo['id'] = md5(serialize($arrRdo));
    }
    
    
    return($arrRdo);
    
  }       
  
}



?>
