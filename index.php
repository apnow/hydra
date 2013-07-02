<?php


// ---------------------- DEFINES ------------------------ //
// ------------------------------------------------------- //

define('PS', '/');
define('INDEX', 'index.php');
define('BASE', substr($_SERVER['PHP_SELF'], 1 , strpos($_SERVER['PHP_SELF'], INDEX)-1));

// TODO: Delete this part.
error_reporting(E_ALL);
ini_set("display_errors", 1); 

// ----------------------- INDEX ------------------------- //
// ------------------------------------------------------- //

        
if(!empty($_SERVER['DOCUMENT_ROOT']))
  define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT'].PS);
else
  define('DOC_ROOT', BASE);

if(is_file('fr/kernel.php') && include_once('fr/kernel.php'))
{
  Kernel::Factory();
  
  if(is_file('app/bootstrap.php'))
    include_once('app/bootstrap.php');  
  else
    die("Error: Cannot launch Hydra application.");
}
else
  die("Error: Cannot launch Hydra Kernel system.");


  


?>
