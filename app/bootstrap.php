<?php


$arrConf = null;

// INCLUDE app CONF file & Hydra Kernel Initialisation:
if(is_readable(dirname(__FILE__).'/conf/conf.php'))
  include('conf/conf.php');

//if(is_file(dirname(__FILE__).'/conf/conf.php') && (include('conf/conf.php'))) 
//  Kernel::Init($arrConf);
//else
//  Kernel::Init();

Kernel::Init($arrConf);

// START: Define app requiered elements: //
// ------------------------------------- //

// END: Define app requiered elements:   //
// ------------------------------------- //

// Run Hydra Kernel:
Kernel::Run();

?>
