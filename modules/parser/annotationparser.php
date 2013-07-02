<?php


class AnnotationParser implements IParser
{
  // Public class attributes:
  public $strClassName      = __CLASS__;
  public $strClassVersion   = '0.31.1';
  
  // Protected class attributes:
  protected $arrData        = null;
  
  // Private class attributes:
  
  /**
   * AnnotationParser class default constructor.
   * 
   * 
   **/
  public function __construct()
  {
    //echo(__METHOD__.'() => Class default constructor.<br />');
    
  }
  
  /**
   * AnnotationParser class default destructor.
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
    unset($this->arrData);
    
    // Private attributes:
    
  }
  
  // ------------------ ANNOTATION PARSER METHODS ------------------- //
  
  /**
   * Method that parses phpdoc structure comments to associative array content.
   * 
   * @param string $strComment Method annotation to parse.
   * 
   * @return array|null Associative array with tagged comment information if parse success, false otherwise.
   * 
   **/                      
  public function Parse($strComment=null)
  {
    //echo(__METHOD__." => Comment to parse: ".$strComment."<br />");
    
    $iLineIndex = 0;
    $arrLines = explode("\n", $strComment);
    
    $this->arrData = null;
    
    foreach ($arrLines as $line) 
    {
      $text = null;
      $annotation = null;
      
      list($text, $annotation) = sscanf($line, '%[^@]%[ -~]');
      
      // Get Method Annotation Comments:
      if(isset($text))
      {
        list($asterisks, $comment) = sscanf(trim($text), '%s %[ -~]');
         
        if(!isset($this->arrData['desc']))
        {
          $this->arrData['desc']     = $comment;
          $this->arrData['fulldesc'] = '';
        }    
        else
          $this->arrData['fulldesc'].= $comment;
          
      }
      
      // Get Method annotation Tags:
      if(isset($annotation))
      {
        sscanf($annotation, '@%s %[ -~]', $annotation_name, $annotationDesc);
         
        $annotation_name = strtolower($annotation_name);
        
        switch($annotation_name)
        {
          case 'param':
            self::ParseParamTag($annotationDesc);
          break;
          
          case 'return':
            self::ParseReturnTag($annotationDesc);
          break;
          
          case 'route':
            self::ParseRouteTag($annotationDesc);
          break;
          
          case 'author':
            $this->arrData['author'] = $annotationDesc;
          break;
          
          
          default:
          break;
        }
      } 
       
       ++$iLineIndex;
    }
    
    
    //echo(__METHOD__." => Obtained methods: <br />");
    //echo("<pre>");
    //  print_r($this->arrData);
    //echo("</pre>");
     
    return($this->arrData);
  }
  
  
  /**
   * Method that parses a param tag estructured as in phpdoc.
   * 
   * The structure that parses has to defined as: paramType $paramName paramDesc.
   *       
   * @param string $strParamDesc Method param tag to parse.
   * 
   * @return boolean True if param tag has been parsed, false otherwise.
   * 
   **/       
  private function ParseParamTag($strParamDesc=null)
  {
    $bRet = false;
    
    if(isset($strParamDesc))
    {
      list($strType, $strName, $strDesc) = sscanf(trim($strParamDesc), '%s $%s %[ -~]');
      
      if(isset($strName) && isset($strType))
      {
        //echo("Nombre de parametro: ".$strName."<br />");
        //echo("Tipo de parametro: ".$strType."<br />");
        //echo("Descripcion del parametro: ".$strDesc."<br />");
      
        $strName = strtolower($strName);
        
        $this->arrData['params'][$strName]['type'] = $strType;
        $this->arrData['params'][$strName]['desc'] = $strDesc;
      
        $bRet = true;
      }   
      
    }
    
    return($bRet);
  }
  
  /**
   * Method that parses a return tag estructured as in phpdoc.
   * 
   * The structure that parses has to defined as: returnType returnDesc.
   *       
   * @param string $strReturnDesc Method param tag to parse.
   * 
   * @return boolean True if return tag has been parsed, false otherwise.
   * 
   **/       
  private function ParseReturnTag($strReturnDesc=null)
  {
    $bRet = false;
    
    if(isset($strReturnDesc))
    {
      list($strType, $strDesc) = sscanf(trim($strReturnDesc), '%s %[ -~]');
      
      if(isset($strType))
      {
        //echo("Tipo de parametro: ".$strType."<br />");
        //echo("Descripcion del parametro: ".$strDesc."<br />");
      
        $this->arrData['return']['type'] = $strType;
        $this->arrData['return']['desc'] = $strDesc;
      
        $bRet = true;
      }   
      
    }
    
    return($bRet);
  }
  
  /**
   * Method that parses a route tag estructured as defined in Hydra.
   * 
   * The structure that parses has to defined as: routeMethod routePattern routeFilter routeDefaults.
   *       
   * @param string $strRouteesc Method route tag to parse.
   * 
   * @return boolean True if route tag has been parsed, false otherwise.
   * 
   **/       
  private function ParseRouteTag($strRouteDesc=null)
  {
    $bRet = false;
    
    //echo(__METHOD__." => Route Desc: ".$strRouteDesc."<br />");
    
    if(isset($strRouteDesc))
    {
      $strMethod    = 'GET';
      $strPattern   = null;
      $arrFilter    = null;
      $arrDefaults  = null;
           
      list($strMethod, $strPattern) = sscanf(trim($strRouteDesc), '%s %s' );
      
      // Check for filter information:
      if($iPos = stripos($strRouteDesc, 'filter={'))
      {
        $iFilterLen = strlen('filter={');
        
        $strFilterData = substr($strRouteDesc, $iPos+$iFilterLen, strpos($strRouteDesc, '}', $iPos+$iFilterLen) - ($iPos+$iFilterLen));
        
        foreach(explode(',', $strFilterData) as $key => $strFilterPair)
        {
          $arrData = explode('=', $strFilterPair);
          $arrFilter[strtolower($arrData[0])] = $arrData[1];
        }
      }
      
      // Check for default information:
      if($iPos = stripos($strRouteDesc, 'defaults={'))
      {
        $iDefaultsLen = strlen('defaults={');
        
        $strDefaultsData = substr($strRouteDesc, $iPos+$iDefaultsLen, strpos($strRouteDesc, '}', $iPos+$iDefaultsLen) - ($iPos+$iDefaultsLen));
        
         foreach(explode(',', $strDefaultsData) as $key => $strDefaultPair)
        {
          $arrData = explode('=', $strDefaultPair);
          $arrDefaults[strtolower($arrData[0])] = $arrData[1];
        }
        
      }
      
      
      if(isset($strMethod) && isset($strPattern))
      {
        $iRoute = isset($this->arrData['route'])? count($this->arrData['route']) : 0;
        
        $this->arrData['route'][$iRoute]['method']       = $strMethod;
        $this->arrData['route'][$iRoute]['pattern']      = strtolower($strPattern);
        
        if(!empty($arrFilter))
          $this->arrData['route'][$iRoute]['filter']       = $arrFilter;
        
        if(!empty($arrDefaults))
          $this->arrData['route'][$iRoute]['def_values']   = $arrDefaults;
        
        $bRet = true;
      }
    }
    
    return($bRet);
  }
  
  // --------------------- GETTER AREA --------------------- //
  
  /**
   * Annotation Parser data GETTER method.
   * 
   * @return mixed Full parsed data.      
   *  
   **/
  public function GetData()
  {
    return($this->arrData);
  }
   
  /**
   * Annotation Parser strClassName GETTER method.
   * 
   * @return string class name.      
   *  
   **/
  public function GetStrClassName()
  {
    return($this->strClassName);
  }
  
  /**
   * Annotation Parser strClassVersion GETTER method.
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
