<?php

/**
 * Version details.
 * @package    mod
 * @subpackage rtvideo
 * @copyright  2015 Refined Data Solutions Inc.
 * @author     Elvis Li
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once ($CFG->dirroot.'/course/moodleform_mod.php');
//require_once($CFG->dirroot.'/mod/rtvideo/lib.php');

class mod_rtvideo_mod_form extends moodleform_mod {

    function definition() {
        global $COURSE, $CFG, $DB, $USER, $PAGE;
        $PAGE->requires->js('/mod/rtvideo/js/mod_rtvideo.js');
        
        $mform =& $this->_form;

        // General section
        $mform->addElement('header', 'general', get_string('general', 'form'));
        
        // Video location URL
        $mform->addElement('text', 'videourl', get_string('videourl', 'rtvideo'), array('size' => '255', 'style' => 'width: 450px; max-width: 450px;'));
        $mform->setType('videourl', (!empty($CFG->formatstringstriptags)) ? PARAM_TEXT : PARAM_CLEAN);
        $mform->addRule('videourl', null, 'required', null, 'client');
        $mform->addHelpButton('videourl', 'videourl', 'rtvideo');
        
        // CC location URL
        if (empty($CFG->rtvideo_enablefilepath)){
            $mform->addElement('text', 'ccurl', get_string('ccurl', 'rtvideo'), array('size' => '255', 'style' => 'width: 450px; max-width: 450px;'));
            $mform->setType('ccurl', (!empty($CFG->formatstringstriptags)) ? PARAM_TEXT : PARAM_CLEAN);
            $mform->addHelpButton('ccurl', 'ccurl', 'rtvideo');
        }
        // Activity name
        $mform->addElement('text', 'name', get_string('activityname', 'rtvideo'), array('size' => '255', 'style' => 'width: 450px; max-width: 450px;'));
        $mform->setType('name', (!empty($CFG->formatstringstriptags)) ? PARAM_TEXT : PARAM_CLEAN);
        $mform->addRule('name', null, 'required', null, 'client');
        
        // Summary
        $this->standard_intro_elements(false, get_string('summary', 'rtvideo'));
        
        // **** Video Display section
        $mform->addElement('header', 'videodisplayhdr', get_string('videodisplayhdr', 'rtvideo'));
        
        // Embed player on course page
        $mform->addElement('checkbox', 'displayoncourse', get_string('displayoncourse', 'rtvideo'));
        $mform->addHelpButton('displayoncourse', 'displayoncourse', 'rtvideo');
        $default = isset( $CFG->rtvideo_displayoncourse ) ? $CFG->rtvideo_displayoncourse : 1;
        $mform->setDefault('displayoncourse', $default);

        // Width of player
        $mform->addElement('text', 'width', get_string('width', 'rtvideo'), array('size' => '10'));
        $mform->setType('width', PARAM_TEXT);
        $mform->addRule('width', null, 'required', null, 'client');
        $default = isset( $CFG->rtvideo_width ) ? $CFG->rtvideo_width : '';
        $mform->setDefault('width', $default);
        $mform->addHelpButton('width', 'width', 'rtvideo');
        
        // Height of player
        $mform->addElement('text', 'height', get_string('height', 'rtvideo'), array('size' => '10'));
        $mform->setType('height', PARAM_TEXT);
        $default = isset( $CFG->rtvideo_height ) ? $CFG->rtvideo_height : '';
        $mform->setDefault('height', $default);
        $mform->addHelpButton('height', 'height', 'rtvideo');
        
        // URL of layover image
        $mform->addElement('text', 'layoverurl', get_string('layoverurl', 'rtvideo'), array('size' => '255', 'style' => 'width: 450px; max-width: 450px;'));
        $mform->setType('layoverurl', (!empty($CFG->formatstringstriptags)) ? PARAM_TEXT : PARAM_CLEAN);
        $default = isset( $CFG->rtvideo_layoverurl ) ? $CFG->rtvideo_layoverurl : '';
        $mform->setDefault('layoverurl', $default);
        $mform->addHelpButton('layoverurl', 'layoverurl', 'rtvideo');
        $mform->setAdvanced('layoverurl', 'videodisplayhdr');
        
        // **** JWPlayer Settings        
        $mform->addElement('header', 'jwplayersettingshdr', get_string('jwplayersettingshdr', 'rtvideo'));
                
        // Aspect ratio (new)
        $mform->addElement('text', 'aspectratio', get_string('aspectratio', 'rtvideo'), array('size' => '10'));
        $mform->setType('aspectratio', PARAM_TEXT);
        $default = isset( $CFG->rtvideo_aspectratio ) ? $CFG->rtvideo_aspectratio : '';
        $mform->setDefault('aspectratio', $default);
        $mform->addHelpButton('aspectratio', 'aspectratio', 'rtvideo');        
                
        // Enable display controls (new)
        $mform->addElement('checkbox', 'displaycontrols', get_string('displaycontrols', 'rtvideo'));
        $mform->setType('displaycontrols', PARAM_INT);
        $mform->addHelpButton('displaycontrols', 'displaycontrols', 'rtvideo');
        $default = isset( $CFG->rtvideo_displaycontrols ) ? $CFG->rtvideo_displaycontrols : 0;
        $mform->setDefault('displaycontrols', $default);
        $mform->setAdvanced('displaycontrols', 'jwplayersettingshdr');
                
        // Enable auto start (new)
        $mform->addElement('checkbox', 'autostart', get_string('autostart', 'rtvideo'));
        $mform->setType('autostart', PARAM_INT);
        $mform->addHelpButton('autostart', 'autostart', 'rtvideo');
        $default = isset( $CFG->rtvideo_autostart ) ? $CFG->rtvideo_autostart : 0;
        $mform->setDefault('autostart', $default);
        $mform->setAdvanced('autostart', 'jwplayersettingshdr');        
                
        // Enable mute option (new)
        $mform->addElement('checkbox', 'muteoption', get_string('muteoption', 'rtvideo'));
        $mform->setType('muteoption', PARAM_INT);
        $mform->addHelpButton('muteoption', 'muteoption', 'rtvideo');
        $default = isset( $CFG->rtvideo_muteoption ) ? $CFG->rtvideo_muteoption : 0;
        $mform->setDefault('muteoption', $default);
        $mform->setAdvanced('muteoption', 'jwplayersettingshdr');      
        
        // Enable rendering mode (new)
        $modeoptions = array(            
            1 => 'HTML5',
            2 => 'FLASH'
        );    
        $mform->addElement('select', 'renderingmode', get_string("renderingmode", 'rtvideo'), $modeoptions);
        $mform->addHelpButton('renderingmode', "renderingmode", 'rtvideo');
        $mform->setType('renderingmode', PARAM_INT);
        $default = isset( $CFG->rtvideo_renderingmode ) ? $CFG->rtvideo_renderingmode : 1;
        $mform->setDefault('renderingmode', $default);
        
        // Enable androidhls (new)
        $mform->addElement('checkbox', 'androidhls', get_string('androidhls', 'rtvideo'));
        $mform->setType('androidhls', PARAM_INT);
        $mform->addHelpButton('androidhls', 'androidhls', 'rtvideo');
        $default = isset( $CFG->rtvideo_androidhls ) ? $CFG->rtvideo_androidhls : 0;
        $mform->setDefault('androidhls', $default);
        $mform->setAdvanced('androidhls', 'jwplayersettingshdr');    
        
        // **** Grading Options section
        $mform->addElement('header', 'grading', get_string('gradinghdr', 'rtvideo'));
        
        // Grading Options
        $dgoptions = array(
            0 => get_string('off', 'rtvideo'),
            1 => get_string('interaction_grading', 'rtvideo')
        );
        $mform->addElement('select', 'detailgrading', get_string("detailgrading", 'rtvideo'), $dgoptions);
        $mform->addHelpButton('detailgrading', "detailgrading", 'rtvideo');
        $default = isset( $CFG->rtvideo_detailgrading ) ? $CFG->rtvideo_detailgrading : 0;
        $mform->setDefault('detailgrading', $default);
        
        // Thresholds (1-3)
        $options = array();
        $options[0] = get_string('none');
        $options[1] = '10%';
        $options[2] = '20%';
        $options[3] = '30%';
        $options[4] = '40%';
        $options[5] = '50%';
        $options[6] = '60%';
        $options[7] = '70%';
        $options[8] = '80%';
        $options[9] = '90%';
        $options[10] = '100%';
                
        $goptions = array();
        for ($i = 100; $i >= 1; $i--) {
            $goptions[$i] = $i . '%';
        }
        
        $formgroup = array();
        $formgroup[] =& $mform->createElement('select', 'threshold[1]', '', $options);
        $mform->setDefault('threshold[1]', 0);
        $mform->disabledIf('threshold[1]', 'detailgrading', 'eq', 0);
        $formgroup[] =& $mform->createElement('select', 'grade[1]', '', $goptions);
        $mform->setDefault('grade[1]', 0);
        $mform->disabledIf('grade[1]', 'detailgrading', 'eq', 0);
        $mform->addElement('group', 'tg1', get_string("tg", 'rtvideo') . ' 1', $formgroup, array(' '), false);
        $mform->addHelpButton('tg1', "tg", 'rtvideo');

        $formgroup = array();
        $formgroup[] =& $mform->createElement('select', 'threshold[2]', '', $options);
        $mform->setDefault('threshold[2]', 0);
        $mform->disabledIf('threshold[2]', 'detailgrading', 'eq', 0);
        $formgroup[] =& $mform->createElement('select', 'grade[2]', '', $goptions);
        $mform->setDefault('grade[2]', 0);
        $mform->disabledIf('grade[2]', 'detailgrading', 'eq', 0);
        $mform->addElement('group', 'tg2', get_string("tg", 'rtvideo') . ' 2', $formgroup, array(' '), false);
        $mform->addHelpButton('tg2', "tg", 'rtvideo');

        $formgroup = array();
        $formgroup[] =& $mform->createElement('select', 'threshold[3]', '', $options);
        $mform->setDefault('threshold[3]', 0);
        $mform->disabledIf('threshold[3]', 'detailgrading', 'eq', 0);
        $formgroup[] =& $mform->createElement('select', 'grade[3]', '', $goptions);
        $mform->setDefault('grade[3]', 0);
        $mform->disabledIf('grade[3]', 'detailgrading', 'eq', 0);
        $mform->addElement('group', 'tg3', get_string("tg", 'rtvideo') . ' 3', $formgroup, array(' '), false);
        $mform->addHelpButton('tg3', "tg", 'rtvideo');
        
        $this->standard_coursemodule_elements();

        $this->add_action_buttons();
    }

    function data_preprocessing(&$data) {
        global $DB;

        parent::data_preprocessing($data);

        if (isset($data['id']) && is_numeric($data['id'])) {
            if ($gradings = $DB->get_records('rtvideo_grading', array('rtvideoid' => $data['id']), 'threshold desc')) {
                $key = 1;
                foreach ($gradings as $grading) {
                    $data['threshold[' . $key . ']'] = $grading->threshold;
                    $data['grade[' . $key . ']'] = $grading->grade;
                    $key++;
                }
            }
        }
    }

    function validation($data, $files) {
        $errors = parent::validation($data, $files);

        if (count($errors) == 0) {
            return true;
        } else {
            return $errors;
        }
    }
}
