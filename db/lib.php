<?php

/**
 * Version details.
 * @package    mod
 * @subpackage rtvideo
 * @copyright  2015 Refined Data Solutions Inc.
 * @author     Elvis Li
 */
function mod_rtvideo_migrate_connect_video() {

    global $DB, $CFG;

    // not proceed if mod connect is not installed.

    $connect = $DB->get_record('config_plugins', array('plugin' => 'mod_connect', 'name' => 'version'));
    if (empty($connect))
        return true;


    $videos = $DB->get_records('connect', array('type' => 'video'));

    foreach ($videos as $video) {
        try {
            $rtvideo = new stdClass();
            $rtvideo->course = $video->course;
            $rtvideo->name = $video->name;
            $rtvideo->intro = $video->intro;
            $rtvideo->introformat = $video->introformat;
            $rtvideo->videourl = $video->url;
            $rtvideo->ccurl = '';
            $rtvideo->displayoncourse = $video->displayoncourse;
            $rtvideo->width = rtvideo_get_legacy_width($video);
            $rtvideo->height = rtvideo_get_legacy_height($video);
            $rtvideo->layoverurl = rtvideo_get_legacy_image($video);
            $rtvideo->aspectratio = isset($CFG->rtvideo_aspectratio) ? $CFG->rtvideo_aspectratio : '16:9';
            $rtvideo->displaycontrols = isset($CFG->rtvideo_displaycontrols) ? $CFG->rtvideo_displaycontrols : 1;
            $rtvideo->autostart = isset($CFG->rtvideo_autostart) ? $CFG->rtvideo_autostart : 0;
            $rtvideo->muteoption = isset($CFG->rtvideo_muteoption) ? $CFG->rtvideo_muteoption : 0;
            $rtvideo->renderingmode = isset($CFG->renderingmode) ? $CFG->renderingmode : 1;
            $rtvideo->rtvideo_androidhls = isset($CFG->rtvideo_androidhls) ? $CFG->rtvideo_androidhls : 0;
            $rtvideo->detailgrading = $video->detailgrading;
            $rtvideo->timecreated = $video->timemodified;
            $rtvideo->timemodified = $video->timemodified;
            $rtvideo->id = $DB->insert_record('rtvideo', $rtvideo);
            if (!empty($rtvideo->id)) {
                rtvideo_migrate_connect_entry($video, $rtvideo);
                rtvideo_migrate_connect_grading($video, $rtvideo);
                rtvideo_migrate_course_module($video, $rtvideo);
                rtvideo_migrate_grade_item($video, $rtvideo);
            }
        } catch (Exception $e) {
            return true;
        }
    }
}

function rtvideo_get_legacy_image($video) {
    if (empty($video->display))
        return '';
    $s = explode('#', $video->display);
    if (empty($s[4]))
        return '';
    $result = preg_replace('/\]/', '', $s[4]);
    $result = preg_replace('/\<\/center\>/', '', $result);
    return $result;
}

function rtvideo_get_legacy_width($video) {
    global $CFG;
    if (empty($video->display)) {
        $width = isset($CFG->rtvideo_width) ? $CFG->rtvideo_width : '50%';
        return $width;
    }
    $s = explode('#', $video->display);
    if (empty($s[2])) {
        $width = isset($CFG->rtvideo_width) ? $CFG->rtvideo_width : '50%';
        return $width;
    }
    $width = $s[2];
    return $width;
}

function rtvideo_get_legacy_height($video) {
    global $CFG;
    if (empty($video->display)) {
        $height = isset($CFG->rtvideo_height) ? $CFG->rtvideo_height : '';
        return $height;
    }
    $s = explode('#', $video->display);
    if (empty($s[3])) {
        $height = isset($CFG->rtvideo_height) ? $CFG->rtvideo_height : '';
        return $height;
    }
    $height = $s[3];
    return $height;
}

function rtvideo_migrate_connect_entry($connect, $rtvideo) {
    global $DB;
    $connect_entries = $DB->get_records('connect_entries', array('connectid' => $connect->id));
    foreach ($connect_entries as $connect_entry) {
        $rtvideo_entry = new stdClass();
        $rtvideo_entry->rtvideoid = $rtvideo->id;
        $rtvideo_entry->userid = $connect_entry->userid;
        $rtvideo_entry->positions = $connect_entry->positions;
        $rtvideo_entry->views = $connect_entry->views;
        $rtvideo_entry->grade = $connect_entry->grade;
        $rtvideo_entry->timemodified = $connect_entry->timemodified;
        $rtvideo_entry->id = $DB->insert_record('rtvideo_entries', $rtvideo_entry);
    }
    return true;
}

function rtvideo_migrate_connect_grading($connect, $rtvideo) {
    global $DB;
    $connect_gradings = $DB->get_records('connect_grading', array('connectid' => $connect->id));
    foreach ($connect_gradings as $connect_grading) {
        $rtvideo_grading = new stdClass();
        $rtvideo_grading->rtvideoid = $rtvideo->id;
        $rtvideo_grading->threshold = $connect_grading->threshold;
        $rtvideo_grading->grade = $connect_grading->grade;
        $rtvideo_grading->timemodified = $connect_grading->timemodified;
        $rtvideo_grading->id = $DB->insert_record('rtvideo_grading', $rtvideo_grading);
    }
    return true;
}

function rtvideo_migrate_course_module($connect, $rtvideo) {
    global $DB, $CFG;
    $connect_module = $DB->get_record('modules', array('name' => 'connect'));
    $connect_module_id = $connect_module->id;

    $rtvideo_module = $DB->get_record('modules', array('name' => 'rtvideo'));
    $rtvideo_module_id = $rtvideo_module->id;

    $cm = $DB->get_record('course_modules', array('course' => $connect->course, 'module' => $connect_module_id, 'instance' => $connect->id));

    if (empty($cm))
        return false;

    $cm->module = $rtvideo_module_id;
    $cm->instance = $rtvideo->id;

    $DB->update_record('course_modules', $cm);

    return true;
}

function rtvideo_migrate_grade_item($connect, $rtvideo) {

    global $DB, $CFG;

    $gi = $DB->get_record('grade_items', array('courseid' => $connect->course, 'itemmodule' => 'connect', 'iteminstance' => $connect->id));

    if (empty($gi))
        return false;

    $gi->itemmodule = 'rtvideo';
    $gi->iteminstance = $rtvideo->id;

    $DB->update_record('grade_items', $gi);

    return true;
}
