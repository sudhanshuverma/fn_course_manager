<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once($CFG->libdir.'/formslib.php');

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once ($CFG->dirroot.'/course/moodleform_mod.php');

class block_course_menu_edit_form extends moodleform {
    protected $_assignmentinstance = null;
    function definition() {
        global $CFG, $DB;
        $mform =& $this->_form;
//-------------------------------------------------------------------------------
        $mform->addElement('header', 'general', get_string('general', 'form'));

//        $mform->addElement('static', 'statictype', get_string('assignmenttype', 'assignment'), get_string('type'.$type,'assignment'));

        $mform->addElement('text', 'name', get_string('assignmentname', 'assignment'), array('size'=>'64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');

//        $mform->add_intro_editor(true, get_string('description', 'assignment'));
//        $definitionoptions = $this->_customdata['definitionoptions'];
//        $attachmentoptions = $this->_customdata['attachmentoptions'];
        // a bit further...
        
        $mform->addElement('editor', 'introeditor', get_string('definition', 'glossary'), null, array('maxfiles'=>EDITOR_UNLIMITED_FILES, 'noclean'=>true));
        $mform->setType('introeditor', PARAM_RAW);
        $mform->addRule('introeditor', get_string('required'), 'required', null, 'client');

        $this->add_action_buttons();
    }
}

   