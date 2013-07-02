<?php


/**
 * Application Configuration file
 * 
 * Web application Main Config file. Load Information about:
 *  - App Info. 
 *  - Paths Info.
 *  - Database access Info.
 *  - Log conf Info.
 *  - Route Info.
 *  
 */



/**
 * Application Main Information.
 * 
 * Possible values:
 *   - name       : Name of the application.
 *   - version    : Version of the application. Format: <<Major version.Minor version.Build number>> ex: 0.1.0
 *   - environment: Possible values:  installation, development, production.     
 *   
 **/  

  $arrConf['app']['name']         = 'Hydra Framework';
  $arrConf['app']['version']      = '0.31.1';
  $arrConf['app']['environment']  = 'development'; 


/**
 * Application Path Information.
 * 
 * Define application path so the library module may find requiered files.     
 *   
 **/  

  $arrConf['path']['app']         = 'app/'; 
  $arrConf['path']['modules']     = 'modules/';

/** Log Information.
 *
 * Define application logging configuration.
 * 
 **/

  $arrConf['log']['enable']       = true;
  $arrConf['log']['display']      = true;
  $arrConf['log']['level']        = E_ALL;  

/** Route Information
 * 
 * Define application route information.
 * 
 **/

  $arrConf['route']['default']['uri']         = '/main/home';
  $arrConf['route']['default']['pattern']     = '/main/home';
  $arrConf['route']['default']['controller']  = 'main';
  $arrConf['route']['default']['action']      = 'home'; 
 

 
?>
