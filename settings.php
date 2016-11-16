<?php /**
 * Version details.
 * @package    mod
 * @subpackage rtvideo
 * @copyright  2015 Refined Data Solutions Inc.
 * @author     Elvis Li
 */
defined('MOODLE_INTERNAL') || die;

if ( $hassiteconfig ) {

    // Video 
    $settings->add(new admin_setting_heading('videohdr', get_string('videohdr', 'rtvideo'),'' ));
    
    $settings->add(new admin_setting_configtext( 'rtvideo_videoserver', get_string( 'rtvideoserver', 'rtvideo' ), get_string( 'rtvideoserver_hint', 'rtvideo'), '', PARAM_TEXT, 60 ) );
    
    
    // Closed Caption
    $settings->add(new admin_setting_heading('cchdr', get_string('cchdr', 'rtvideo'),'' ));
    
    $settings->add(new admin_setting_configcheckbox('rtvideo_enablefilepath', get_string('enablefilepath', 'rtvideo'),
		get_string('enablefilepath_help', 'rtvideo'), 1));
    
    $settings->add(new admin_setting_configcheckbox('rtvideo_enablemultilang', get_string('enablemultilang', 'rtvideo'),
		get_string('enablemultilang_help', 'rtvideo'), 0));
    
    $settings->add(new admin_setting_configtext( 'rtvideo_ccext', get_string( 'ccext', 'rtvideo' ), get_string( 'ccext_help', 'rtvideo'), '' ) );
    
    // Video Display
    $settings->add(new admin_setting_heading('videodisplayhdr', get_string('videodisplayhdr', 'rtvideo'),'' ));
    
    $settings->add(new admin_setting_configcheckbox('rtvideo_displayoncourse', get_string('displayoncourse', 'rtvideo'),
		get_string('displayoncourse_help', 'rtvideo'), 1));
    
    $settings->add(new admin_setting_configtext( 'rtvideo_width', get_string( 'width', 'rtvideo' ), get_string( 'width_help', 'rtvideo'), '50%' ) );
    
    $settings->add(new admin_setting_configtext( 'rtvideo_height', get_string( 'height', 'rtvideo' ), get_string( 'height_help', 'rtvideo'), '' ) );
    
    $settings->add(new admin_setting_configtext( 'rtvideo_layoverurl', get_string( 'layoverurl', 'rtvideo' ), get_string( 'layoverurl_help', 'rtvideo'), '', PARAM_TEXT, 60 ) );
    
    
    // JWPlayer Settings    
    $settings->add(new admin_setting_heading('jwplayersettingshdr', get_string('jwplayersettingshdr', 'rtvideo'),'' ));
    
     
    $settings->add(new admin_setting_configtext( 'rtvideo_aspectratio', get_string( 'aspectratio', 'rtvideo' ), get_string( 'aspectratiosetting_help', 'rtvideo'), '16:9' ) );
            

    $settings->add(new admin_setting_configcheckbox('rtvideo_displaycontrols', get_string('displaycontrols', 'rtvideo'),
		get_string('displaycontrols_help', 'rtvideo'), 1));
    
    $settings->add(new admin_setting_configcheckbox('rtvideo_autostart', get_string('autostart', 'rtvideo'),
		get_string('autostart_help', 'rtvideo'), 0));
    
    $settings->add(new admin_setting_configcheckbox('rtvideo_muteoption', get_string('muteoption', 'rtvideo'),
		get_string('muteoption_help', 'rtvideo'), 0));
    
    $options = array(
        1 => 'HTML5',
        2 => 'FLASH'
    );    

    $settings->add(new admin_setting_configselect('rtvideo_renderingmode', new lang_string('renderingmode','rtvideo'), 
            new lang_string('renderingmode_help','rtvideo'), 1, $options));
        
    $settings->add(new admin_setting_configcheckbox('rtvideo_androidhls', get_string('androidhls', 'rtvideo'),
		get_string('androidhls_help', 'rtvideo'), 0));
    
    // Grading Options
    $settings->add(new admin_setting_heading('gradinghdr', get_string( 'gradinghdr', 'rtvideo' ),'' ));
    $dgoptions = array(
        0 => get_string('off', 'rtvideo'),
        1 => get_string('interaction_grading', 'rtvideo')
    );
    $settings->add(new admin_setting_configselect('rtvideo_detailgrading', new lang_string('detailgrading','rtvideo'), 
            new lang_string('detailgrading_help','rtvideo'), 1, $dgoptions));
    
    $settings->add(new admin_setting_heading('legacyconnectvideosettingshdr', get_string( 'legacyconnectvideosettingshdr', 'rtvideo' ),'' ));
    
    $settings->add(new admin_setting_configcheckbox( 'connect_oldplayer', get_string( 'oldplayer', 'rtvideo' ), get_string( 'oldplayer_hint', 'rtvideo' ), '0' ) );

    $settings->add(new admin_setting_configtext( 'connect_videoserver', get_string( 'videoserver', 'rtvideo' ), get_string( 'videoserver_hint', 'rtvideo'), '', PARAM_TEXT, 60 ) );
    $settings->add(new admin_setting_configtext( 'connect_videophoto', get_string( 'videophoto', 'rtvideo' ), get_string( 'videophoto_hint', 'rtvideo'), '', PARAM_TEXT, 60 ) );
    $settings->add(new admin_setting_configtext( 'connect_videophotopath', get_string( 'videophotopath', 'rtvideo' ), get_string( 'videophotopath_hint', 'rtvideo'), '', PARAM_TEXT, 60 ) );

}

