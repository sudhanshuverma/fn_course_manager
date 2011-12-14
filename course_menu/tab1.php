<?php
$jsdata  = array (            
                'update'    => $update,
                'modid'     => $modid, 
                'extra'     => $extra, 
                'add'       => $add,
                'type'      => $type,
                'pagetype'  => $pagetype,
                'modarray'  => $modnamearr
                
    
        );
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

$tabs = $row = array();

if (!empty($update) && ($patharray['filename']==='modedit')){
    $cm = get_coursemodule_from_id('', $update, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    $haseditingcapability = has_capability('moodle/course:manageactivities', $context);
    require_login($course, false, $cm); // needed to setup proper $COURSE
} elseif(!empty($modid) && ($patharray['filename']==='view') && in_array($basename, $modnamearr)){    
    $cm = get_coursemodule_from_id('', $modid, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    $haseditingcapability = has_capability('moodle/course:manageactivities', $context);
} else{
    $haseditingcapability = false;
}


$pixurl="$CFG->wwwroot/pix/i";
$editcontenttabpix="<img src='$pixurl/lock.gif' alt='' />";
$settingtabpix="<img src='$pixurl/unlock.gif' alt='' />";



if(($patharray['filename']==='modedit') && empty($type) && empty($add) && !empty($update) && $haseditingcapability ){
    $selectedtab ='setting';
    $modid=$update;
    $cmobject=$DB->get_record('course_modules',array('id'=>$update));
    $modname=$DB->get_field('modules','name', array('id'=>$cmobject->module));
    $row[] = new tabobject('overview', "{$CFG->wwwroot}/mod/{$modname}/view.php?id=$modid", get_string('preview'), get_string('previewthis', 'block_course_menu'));
    $row[] = new tabobject('editcontent', "{$CFG->wwwroot}/blocks/course_menu/edit.php?update={$modid}&return=0", get_string('editcontent', 'block_course_menu').$editcontenttabpix, get_string('editcontent','block_course_menu'));
    $row[] = new tabobject('setting', "{$CFG->wwwroot}/course/modedit.php?update={$modid}&return=0", get_string('setting','block_course_menu').$settingtabpix, get_string('setting','block_course_menu'));
    $tabs[] = $row;
    $tabss=print_tabs($tabs,$selectedtab,'','',TRUE);
} elseif(($patharray['filename']==='view') && in_array($basename, $modnamearr) && $haseditingcapability){
    $selectedtab = 'overview';
    $modid=$modid;
    $cmobject=$DB->get_record('course_modules',array('id'=>$modid));    
    $modname=$DB->get_field('modules','name', array('id'=>$cmobject->module));  
    $row[] = new tabobject('overview', "{$CFG->wwwroot}/mod/{$modname}/view.php?id=$modid", get_string('preview'), get_string('previewthis', 'block_course_menu'));
    $row[] = new tabobject('editcontent', "{$CFG->wwwroot}/blocks/course_menu/edit.php?update={$modid}&course={$COURSE->id}&return=0", get_string('editcontent', 'block_course_menu').$editcontenttabpix, get_string('editcontent','block_course_menu'));
    $row[] = new tabobject('setting', "{$CFG->wwwroot}/course/modedit.php?update={$modid}&return=0", get_string('setting','block_course_menu').$settingtabpix, get_string('setting','block_course_menu'));
    $tabs[] = $row;
    $tabss=print_tabs($tabs,$selectedtab,'','',TRUE);    
} else if(($patharray['filename']==='edit') && in_array($basename, $modnamearr) && $haseditingcapability){
    $selectedtab = 'editcontent';
    $modid = $update;
    $cmobject = $DB->get_record('course_modules',array('id'=>$update));
    $modname = $DB->get_field('modules','name', array('id'=>$cmobject->module));    
    $row[] = new tabobject('overview', "{$CFG->wwwroot}/mod/{$modname}/view.php?id=$modid", get_string('preview'), get_string('previewthis', 'block_course_menu'));
    $row[] = new tabobject('editcontent', "{$CFG->wwwroot}/blocks/course_menu/edit.php?update={$modid}&course={$COURSE->id}&return=0", get_string('editcontent', 'block_course_menu').$editcontenttabpix, get_string('editcontent','block_course_menu'));
    $row[] = new tabobject('setting', "{$CFG->wwwroot}/course/modedit.php?update={$modid}&return=0", get_string('setting','block_course_menu').$settingtabpix, get_string('setting','block_course_menu'));
    $tabs[] = $row;
    $tabss = print_tabs($tabs,$selectedtab,'','',TRUE);    
} else{
    $tabss = "";
}
$PAGE->set_pagelayout('admin');
//print_object($patharray);
//if(!$update){
//    $update=$modid;    
//}
//if(!$modid){
//    $cmobject=$DB->get_record('course_modules',array('id'=>$update));   
//    $modname=$DB->get_field('modules','name', array('id'=>$cmobject->module));
//}


//$PAGE->set_pagelayout('mod');

//$PAGE->set_url('/blocks/fn_coursemanager/index.php', array(
//    'id' => $modid,
//    'modname' => $modname,
//    'course' => $courseid));
//
//if (!$course = $DB->get_record("course", array("id" => $courseid))) {
//    print_error("Course ID was incorrect");
//}
//require_login($course);
//$context = get_context_instance(CONTEXT_COURSE, $course->id);
//$PAGE->set_title($course->fullname);
//$PAGE->set_heading($course->fullname);
//$PAGE->set_pagelayout('incourse');
//$PAGE->set_pagetype('course-view-' . $course->format);
//echo $OUTPUT->header();
//echo '<div>';
//require_once("tab.php");
//
//echo '</div>';  // userlist
//
//echo $OUTPUT->footer();

    