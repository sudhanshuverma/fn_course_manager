<?php

require_once("$CFG->dirroot/config.php");
global $CFG, $OUTPUT, $FULLME,$PAGE, $COURSE, $DB;

$update = optional_param('update', 0, PARAM_INT);
$modid = optional_param('id', 0, PARAM_INT);
$extra = optional_param('extra', null, PARAM_TEXT);
$type  = optional_param('type', '', PARAM_ALPHANUM);
$add   = optional_param('add', '', PARAM_ALPHA); 
$pagetype = optional_param('pagetype', null, PARAM_TEXT);

$patharray = pathinfo($FULLME);

if (isset($patharray['dirname'])){
    $basename = basename($patharray['dirname']);
    
}

if (isset($patharray['filename'])){
    $filname = $patharray['filename'];
    
}

$modnamearray=$DB->get_records('modules', null);
$modnamearr = array();

foreach($modnamearray as $modnamevalue){
    // if($modnamevalue->name==='assignment')continue;
    $modnamearr[]=$modnamevalue->name;    
}

if (!empty($update) && ($patharray['filename']==='modedit')){
    
}

?>
