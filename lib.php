<?php 
/**
 * Version details.
 * @package    mod
 * @subpackage rtvideo
 * @copyright  2015 Refined Data Solutions Inc.
 * @author     Elvis Li
 */

require_once($CFG->dirroot . '/lib/completionlib.php');
/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod.html) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $instance An object from the form in mod.html
 * @return int The id of the newly inserted rtvideo record
 **/
function rtvideo_add_instance($rtvideo) {
    global $CFG, $USER, $COURSE, $DB;
    require_once($CFG->libdir . '/gdlib.php');

    $rtvideo->timecreated = time();
    $rtvideo->timemodified = time();
    if( !isset( $rtvideo->displayoncourse ) ) $rtvideo->displayoncourse = 0;
    if( !isset( $rtvideo->displaycontrols ) ) $rtvideo->displaycontrols = 0;
    if( !isset( $rtvideo->autostart ) ) $rtvideo->autostart = 0;
    if( !isset( $rtvideo->muteoption ) ) $rtvideo->muteoption = 0;
    if( !isset( $rtvideo->androidhls ) ) $rtvideo->androidhls = 0;
    
    if (substr(strtolower($rtvideo->videourl), 0, 4) != "http") {
        if (substr($rtvideo->videourl, 0, 1) == "/") {
            $rtvideo->videourl = $CFG->rtvideo_videoserver . substr($rtvideo->videourl, 1);
        } else {
            $rtvideo->videourl = $CFG->rtvideo_videoserver . $rtvideo->videourl;
        }
    }
    //insert instance
    if ($rtvideo->id = $DB->insert_record("rtvideo", $rtvideo)) {
        
        // Save the grading
        $DB->delete_records('rtvideo_grading', array('rtvideoid' => $rtvideo->id));
        if (isset($rtvideo->detailgrading) && $rtvideo->detailgrading) {
            for ($i = 1; $i < 4; $i++) {
                $grading = new stdClass;
                $grading->rtvideoid = $rtvideo->id;
                $grading->threshold = $rtvideo->threshold[$i];
                $grading->grade = $rtvideo->grade[$i];
                if (!$DB->insert_record('rtvideo_grading', $grading, false)) {
                    return "Could not save rtvideo grading.";
                }
            }
        }
    }

    //create grade item for locking
    $entry = new stdClass;
    $entry->grade = 0;
    $entry->userid = $USER->id;
    rtvideo_gradebook_update($rtvideo, $entry);

    return $rtvideo->id;
}

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod.html) this function
 * will update an existing instance with new data.
 *
 * @param object $instance An object from the form in mod.html
 * @return boolean Success/Fail
 **/
function rtvideo_update_instance($rtvideo) {
    global $CFG, $DB;

    $rtvideo->timemodified = time();
    $rtvideo->id = $rtvideo->instance;
    
    if( !isset( $rtvideo->displayoncourse ) ) $rtvideo->displayoncourse = 0;
    if( !isset( $rtvideo->displaycontrols ) ) $rtvideo->displaycontrols = 0;
    if( !isset( $rtvideo->autostart ) ) $rtvideo->autostart = 0;
    if( !isset( $rtvideo->muteoption ) ) $rtvideo->muteoption = 0;
    if( !isset( $rtvideo->androidhls ) ) $rtvideo->androidhls = 0;
    
    if (substr(strtolower($rtvideo->videourl), 0, 4) != "http") {
        if (substr($rtvideo->videourl, 0, 1) == "/") {
            $rtvideo->videourl = $CFG->rtvideo_videoserver . substr($rtvideo->videourl, 1);
        } else {
            $rtvideo->videourl = $CFG->rtvideo_videoserver . $rtvideo->videourl;
        }
    }
    $rtvideo->videourl = addslashes_js($rtvideo->videourl);
    //update instance
    if (!$DB->update_record("rtvideo", $rtvideo)) {
        return false;
    }

    // Save the grading
    $DB->delete_records('rtvideo_grading', array('rtvideoid' => $rtvideo->id));
    if (isset($rtvideo->detailgrading) && $rtvideo->detailgrading) {
        for ($i = 1; $i < 4; $i++) {
            $grading = new stdClass;
            $grading->rtvideoid = $rtvideo->id;
            $grading->threshold = $rtvideo->threshold[$i];
            $grading->grade = $rtvideo->grade[$i];
            $grading->timemodified = time();
            if (!$DB->insert_record('rtvideo_grading', $grading, false)) {
                return false;
            }
        }
    }

    //create grade item for locking
    global $USER;
    $entry = new stdClass;
    $entry->grade = 0; 
    $entry->userid = $USER->id;
    rtvideo_gradebook_update($rtvideo, $entry);

    return true;
}

/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 **/
function rtvideo_delete_instance($id) {
    global $DB;

    if (!$rtvideo = $DB->get_record('rtvideo', array('id' => $id))) {
        return false;
    }

    // Delete area files (must be done before deleting the instance)
    $cm = get_coursemodule_from_instance('rtvideo', $id);
    $context = context_module::instance($cm->id);
    $fs = get_file_storage();
    $fs->delete_area_files($context->id, 'mod_rtvideo');
    
    // Delete rtvideo records
    $DB->delete_records("rtvideo_grading", array("rtvideoid" => $id));
    $DB->delete_records("rtvideo_entries", array("rtvideoid" => $id));
    $DB->delete_records("rtvideo", array('id' => $id));

    return true;
}

/**
 * Return a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return null
 **/
function rtvideo_user_outline($course, $user, $mod, $rtvideo) {
    global $DB;

    if ($grade = $DB->get_record('rtvideo_entries', array('userid' => $user->id, 'rtvideoid' => $rtvideo->id))) {

        $result = new stdClass;
        if ((float)$grade->grade) {
            $result->info = get_string('grade') . ':&nbsp;' . $grade->grade;
        }
        $result->time = $grade->timemodified;
        return $result;
    }
    return NULL;
}

/**
 * Print a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @return boolean
 **/
function rtvideo_user_complete($course, $user, $mod, $rtvideo) {
    global $DB;

    if ($grade = $DB->get_record('rtvideo_entries', array('userid' => $user->id, 'rtvideoid' => $rtvideo->id))) {
        echo get_string('grade') . ': ' . $grade->grade;
        echo ' - ' . userdate($grade->timemodified) . '<br />';
    } else {
        print_string('nogrades', 'rtvideo');
    }

    return true;
}



function rtvideo_process_options(&$rtvideo) {
    return true;
}

function rtvideo_install() {
    return true;
}

function rtvideo_get_view_actions() {
    return array('launch', 'view all');
}

function rtvideo_get_post_actions() {
    return array('');
}

function rtvideo_supports($feature) {
    switch ($feature) {
        case FEATURE_GROUPS:
            return true;
        case FEATURE_GROUPINGS:
            return true;
        case FEATURE_GROUPMEMBERSONLY:
            return true;
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return true;
        case FEATURE_COMPLETION_HAS_RULES:
            return true;
        case FEATURE_GRADE_HAS_GRADE:
            return true;
        case FEATURE_GRADE_OUTCOMES:
            return false;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return false;

        default:
            return null;
    }
}

function rtvideo_get_completion_state($course, $cm, $userid, $type) {
    global $CFG, $DB;

    return $DB->record_exists('rtvideo_entries', array('rtvideoid' => $cm->instance, 'userid' => $userid));
}


function rtvideo_cm_info_dynamic($mod) {
    global $DB, $USER, $PAGE;

    if (!$mod->available) return;
    //$mod->set_no_view_link();
    $rtvideo = $DB->get_record('rtvideo', array('id' => $mod->instance));
    if ($rtvideo->displayoncourse && ($PAGE->pagetype == 'site-index' || substr($PAGE->pagetype, 0,11) == 'course-view' )) {
        $content = rtvideo_get_player( $rtvideo );        
        $mod->set_content($content);
        $mod->set_no_view_link();
    }
    
    
    return;

    
}

/**
 * returns string containing flashvars for movie callbacks (or blank)
 *
 * @param int $courseid Identifier of current course
 * @param char $url Url of movie resource
 **/
function rtvideo_get_movie_flashvars($courseid, $url) {
    global $CFG, $USER, $DB;

    $track_url = $CFG->wwwroot . "/mod/rtvideo/grade_movie.php";

    if ($rtvideo = $DB->get_record('rtvideo', array('course' => $courseid, 'url' => $url))) {
        //add_to_log($rtvideo->course, "connect", "movie", "filter.php?id={$rtvideo->id}", $rtvideo->id, $rtvideo->id);
        if (isset($CFG->connect_oldplayer) AND $CFG->connect_oldplayer) {
            return "&track_url={$track_url}&student_id={$USER->id}&activity_id={$rtvideo->id}";
        } else {
            return "monitor_url: '{$CFG->wwwroot}/mod/rtvideo/ajax/grade_movie.php', student_id: '{$USER->id}', course_id: '{$rtvideo->course}', activity_id: '{$rtvideo->id}'";
        }
    }

    return '';
}

/**
 * returns nothing
 *
 * @param int $courseid Identifier of current course
 * @param char $url Url of movie resource
 * @param int $userid Identifier of current student
 * $param int    $position   Position # in movie
 **/
function rtvideo_movie_launch($rtvideoid, $userid, $position) {
    global $CFG, $USER, $DB;

    if (!$rtvideo = $DB->get_record('rtvideo', array('id' => $rtvideoid))) return;

    if (!$entry = $DB->get_record('rtvideo_entries', array('rtvideoid' => $rtvideo->id, 'userid' => $userid))) {
        $entry = new stdClass;
        $entry->rtvideoid = $rtvideo->id;
        $entry->userid = $USER->id;
    } 

    $entry->timemodified = time();
    $entry->views = isset($entry->views) ? $entry->views + 1 : 1;
     
    if ($cm = get_coursemodule_from_instance('rtvideo', $rtvideo->id, $rtvideo->course)) {
        $course = $DB->get_record('course', array('id' => $rtvideo->course));        
        $completion = new completion_info($course);
        if ($completion->is_enabled($cm)) {
            if ( $cm->completiongradeitemnumber == null && $cm->completionview == 1){
                $completion->set_module_viewed($cm);
                $data = $DB->get_record('course_modules_completion', array('coursemoduleid'=>$cm->id, 'userid'=>$userid));
                if (!empty($data) && ($data->completionstate != COMPLETION_COMPLETE)){
                    $data->completionstate = COMPLETION_COMPLETE;
                    $data->timemodified    = time();
                    $DB->update_record('course_modules_completion',$data);
                }
            }
        }
    }     

    // Without detail grading, just set the grade to 100 and return
    if (!$rtvideo->detailgrading) {
        $grade = 100;
    } elseif (!isset($entry->grade) OR $entry->grade < 100) {        
        if (!isset($entry->positions)) {
            $entry->positions = chr($position + 48);
        } else {
            if (!strstr($entry->positions, chr($position + 48) )) {
                $entry->positions = $entry->positions . chr($position + 48);
            }
        }
        
        $maxpos = 1;
        while (strstr($entry->positions, chr(++$maxpos + 48))) {
        }
        $maxpos--;
       
        $grade = $DB->get_record_sql("SELECT grade FROM {$CFG->prefix}rtvideo_grading WHERE rtvideoid = $rtvideo->id AND threshold <= $maxpos AND threshold != 0 ORDER BY grade DESC LIMIT 1");
        if (isset($grade) && $grade) {
            $grade = $grade->grade;
        } else {
            $grade = intval($maxpos * 10);
        }
    } else {
        $grade = 0;
    }
    if (!isset($entry->grade) OR $entry->grade < $grade) {
        $entry->grade = $grade;
        rtvideo_gradebook_update($rtvideo, $entry);
    }

    if (!isset($entry->id)) $DB->insert_record('rtvideo_entries', $entry);
    else $DB->update_record('rtvideo_entries', $entry);

    $cm = get_coursemodule_from_instance('rtvideo', $rtvideo->id);
    $event = \mod_rtvideo\event\rtvideo_movieposition::create(array(
        'objectid' => $rtvideoid,
   		'context' => context_module::instance($cm->id),
   		'other' => array( 'videourl' => $rtvideo->videourl, 'description' => "Position: $position")
    ));
    $course = $DB->get_record( 'course', array( 'id' => $rtvideo->course ) );
    if( $course ) $event->add_record_snapshot('course', $course);
    $event->trigger();

    return;
}


/**
 * Update gradebook
 *
 * @param object $entry connect instance
 */
function rtvideo_gradebook_update($rtvideo, $entry) {
    global $CFG, $DB;
    require_once($CFG->libdir . '/gradelib.php');

    if (isset($entry->grade) AND $entry->grade AND isset($entry->userid) AND $entry->userid) {
        $grades = new stdClass();
        $grades->id = $entry->userid;
        $grades->userid = $entry->userid;
        $grades->rawgrade = $entry->grade;
        $grades->dategraded = time();
        $grades->datesubmitted = time();
    } else $grades = null;

    $params = array();
    $params['itemname'] = $rtvideo->name;
    $params['gradetype'] = GRADE_TYPE_VALUE;
    $params['grademax'] = 100;
    $params['grademin'] = 0;

    $sts = grade_update('mod/rtvideo', $rtvideo->course, 'mod', 'rtvideo', $rtvideo->id, 0, $grades, $params);
    $sts = $sts == GRADE_UPDATE_OK ? 1:0; // GRADE_UPDATE_OK value is actually zero, so we have to do this to turn it into true value
 
    if ($sts AND $entry->grade == 100 AND $cm = get_coursemodule_from_instance('rtvideo', $rtvideo->id)) {
        // Mark Users Complete
        if ($cmcomp = $DB->get_record('course_modules_completion', array('coursemoduleid' => $cm->id, 'userid' => $entry->userid))) {
            $cmcomp->completionstate = 1;
            $cmcomp->viewed = 1;
            $cmcomp->timemodified = time();
            $DB->update_record('course_modules_completion', $cmcomp);
        } else {
            $cmcomp = new stdClass;
            $cmcomp->coursemoduleid = $cm->id;
            $cmcomp->userid = $entry->userid;
            $cmcomp->completionstate = 1;
            $cmcomp->viewed = 1;
            $cmcomp->timemodified = time();
            $DB->insert_record('course_modules_completion', $cmcomp);
        }
    }
    return $sts;
}


/**
 * Serves the resource files.
 * @param object $course
 * @param object $cm
 * @param object $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @return bool false if file not found, does not return if found - just send the file
 */
function rtvideo_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    global $CFG, $DB;

    if ($filearea === 'content' and $context->contextlevel === CONTEXT_MODULE) {

        // Remove item id 0
        array_shift($args);

        require_course_login($course, true, $cm);
        //if (!has_capability('mod/connect:view', $context)) {
        //    return false;
        //}

        $relativepath = implode('/', $args);
        $fullpath = "/$context->id/mod_rtvideo/content/0/$relativepath";

        $fs = get_file_storage();
        if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory()) {
            return false;
        }

        // finally send the file
        send_stored_file($file, 0, 0, true, $options);
    }

    if (preg_match('/_icon$/', $filearea)) {

        // Remove item id 0
        array_shift($args);

        require_course_login($course, true, $cm);
        //if (!has_capability('mod/connect:view', $context)) {
        //    return false;
        //}

        $relativepath = implode('/', $args);
        $fullpath = "/$context->id/mod_rtvideo/$filearea/0/$relativepath";

        $fs = get_file_storage();
        if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory()) {
            return false;
        }

        // finally send the file
        send_stored_file($file, 0, 0, true, $options);
    }

    return false;
}

/**
 * Get the information about the standard rtvideo JavaScript module.
 * @return array a standard jsmodule structure.
 */
function rtvideo_get_js_module() {
    global $PAGE;

    return array(
        'name' => 'mod_rtvideo',
        'fullpath' => '/mod/rtvideo/module.js',
        'requires' => array(),
        'strings' => array(
            array('cancel', 'moodle')
        ),
    );
}

/**
 * Get the information about the standard rtvideo JavaScript module.
 * @return array a standard jsmodule structure.
 */
function rtvideo_get_player( $rtvideo ) {
    global $PAGE, $CFG;

    $playersetupdata = array();
    // video location
    $playersetupdata['file'] = $rtvideo->videourl;
    // closed captions
    rtvideo_set_closed_captions( $rtvideo, $playersetupdata );
    // width and height
    rtvideo_set_width_height( $rtvideo, $playersetupdata );    
    // URL of layover image
    if (!empty($rtvideo->layoverurl)){
        $playersetupdata['image'] = $rtvideo->layoverurl;
    }
    // global setting
    rtvideo_set_global( $rtvideo, $playersetupdata ); 
    
    // monitor
    $monitordata = rtvideo_get_monitordata( $rtvideo );

    /*if (strpos($rtvideo->videourl, 'youtube.com') >= 0 || strpos($rtvideo->videourl, 'youtu.be') >= 0 ){
        $youtubeiframeapi = new moodle_url('/mod/rtvideo/js/youtube_iframe_api.js');
        $PAGE->requires->js($youtubeiframeapi, false);
    }*/
    
    $playerdiv = html_writer::tag('div', '', array('id' => 'rtvideo_player_' . $rtvideo->id));

    $playersetup = array(
        'key' => 'owG1MhTfkqlSo35LTrsfAsRFSwDbVnJl9kj4kA==',
        'playerid' => 'rtvideo_player_' . $rtvideo->id,
        'setupdata' => $playersetupdata,
        'monitordata' => $monitordata,
        'monitorurl' => $CFG->wwwroot . '/mod/rtvideo/ajax/grade_movie.php'
    );

    $PAGE->requires->js_call_amd('mod_rtvideo/rtvideo', 'init', $playersetup);

    return $playerdiv;
}

/**
 * Get the information about the standard rtvideo JavaScript module.
 * @return array a standard jsmodule structure.
 */
function rtvideo_set_width_height( $rtvideo, &$playersetupdata ) {
    global $PAGE, $CFG;
        
    $playersetupdata['width'] =  $rtvideo->width ;
    if (!empty($rtvideo->height)) $playersetupdata['height'] = round( $rtvideo->height );
    if (!empty($rtvideo->aspectratio)) $playersetupdata['aspectratio'] =  $rtvideo->aspectratio ;
    return true;
}

/**
 * Get the information about the standard rtvideo JavaScript module.
 * @return array a standard jsmodule structure.
 */
function rtvideo_set_closed_captions( $rtvideo, &$playersetupdata ) {
    global $PAGE, $CFG;

    $tracks = array();
    if (empty($CFG->rtvideo_enablefilepath)){
        if (empty($rtvideo->ccurl)) return false; 
        $track = rtvideo_get_track( $rtvideo->ccurl, "Default", true );  
        if ($track){
            $tracks[] = $track;
            $playersetupdata['tracks'] = $tracks;
        }
        return true;
    } 
    
    if ( empty($CFG->rtvideo_ccext) ) return false;
    
    $file = preg_replace('/\.[^.]+$/','',$rtvideo->videourl);    
    
    if (empty($CFG->rtvideo_enablemultilang)){
        $file .= "." . $CFG->rtvideo_ccext;
        $track = rtvideo_get_track( $file, "Default", true ); 
        if ($track){
            $tracks[] = $track;       
            $playersetupdata['tracks'] = $tracks;
        }
        return true;
    }
    
    $langs = get_string_manager()->get_list_of_translations();
    foreach( $langs as $key => $label ){
        $label = preg_replace('/\([^.]+\)/','',$label);
        $langfile = $file . "." . $key . "." . $CFG->rtvideo_ccext;
        if ( $key == current_language()){
            $default = true;
        } else {
            $default = false;
        }
        $track = rtvideo_get_track( $langfile, $label, $default );  
        if ($track){
            $tracks[] = $track;
        }
    }
    
    if (!empty($tracks)){
        $playersetupdata['tracks'] = $tracks;
    }
    return true;
}


function rtvideo_get_track( $file, $label, $default = false ){
    global $CFG;
    $track = array();
    //$file_headers = @get_headers($file);
    //error_log($file_headers[0] . ':  ' . $file);
    if (rtvideo_is_url_exist($file) == false) {
        return false;
    }
    $track['kind'] = "captions";
    $track['file'] = $CFG->wwwroot . '/mod/rtvideo/load_caption.php?file='.$file;
    $track['label'] = $label;
    if ($default == true) $track['default'] = true;
    
    return $track;
}

function rtvideo_is_url_exist($url){
    $ch = curl_init($url);    
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if($code == 200){
       $status = true;
    }else{
      $status = false;
    }
    curl_close($ch);
   return $status;
}

/**
 * Get the information about the standard rtvideo JavaScript module.
 * @return array a standard jsmodule structure.
 */
function rtvideo_set_global( $rtvideo, &$playersetupdata ) {
    global $PAGE, $CFG;
    
    $playersetupdata['controls'] = empty($rtvideo->displaycontrols) ? false : true;
    $playersetupdata['autostart'] = empty($rtvideo->autostart) ? false : true;
    $playersetupdata['mute'] = empty($rtvideo->muteoption) ? false : true;
    $playersetupdata['primary'] = $rtvideo->renderingmode == 2 ? 'flash' : 'html5';    
    if (!empty($rtvideo->androidhls)) $playersetupdata['androidhls'] = true;
       
    if (!empty($rtvideo->renderingmode) && ($rtvideo->renderingmode == 2) ) {
        $playersetupdata['flashplayer'] = $CFG->wwwroot . '/mod/rtvideo/js/jwplayer/jwplayer.flash.swf';
    }
            
    return true;
}

/**
 * Get the information about the standard rtvideo JavaScript module.
 * @return array a standard jsmodule structure.
 */
function rtvideo_get_monitordata( $rtvideo ) {
    global $USER, $CFG;
    
    $monitordata = array();
    $monitordata['student_id'] = $USER->id;
    $monitordata['course_id'] = $rtvideo->course;
    $monitordata['activity_id'] = $rtvideo->id;
    
    return $monitordata;
}



