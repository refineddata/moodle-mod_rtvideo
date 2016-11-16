<?php // $Id: view.php
/**
 * Version details.
 * @package    mod
 * @subpackage rtvideo
 * @copyright  2015 Refined Data Solutions Inc.
 * @author     Elvis Li
 */


require_once("../../config.php");
require_once("$CFG->dirroot/mod/rtvideo/lib.php");
global $CFG, $OUTPUT, $PAGE;

$id = required_param('id', PARAM_INT); // Course Module ID

if (!$cm = get_coursemodule_from_id('rtvideo', $id)) {
    print_error('Course Module ID was incorrect');
}
if (!$course = $DB->get_record('course', array('id' => $cm->course))) {
    print_error('course is misconfigured');
}
if (!$rtvideo = $DB->get_record('rtvideo', array('id' => $cm->instance))) {
    print_error('course module is incorrect');
}


require_login($course);
$context = context_course::instance($course->id);
$strtitle = get_string('view');

$PAGE->set_url('/mod/rtvideo/view.php?id=' . $id);
$PAGE->set_context($context);
$PAGE->set_title($strtitle);
$PAGE->set_heading($strtitle);
$PAGE->set_pagelayout('incourse');
$PAGE->navbar->add($strtitle, $PAGE->url);

$event = \mod_rtvideo\event\course_module_viewed::create(array(
    'objectid' => $cm->instance,
    'context' => context_module::instance($cm->id),
));
$event->add_record_snapshot('course', $course);
// In the next line you can use $PAGE->activityrecord if you have set it, or skip this line if you don't have a record.
$event->add_record_snapshot('rtvideo', $rtvideo);
$event->trigger();


//    $PAGE->requires->jquery();
//	$PAGE->requires->jquery_plugin('qtip');
//	$PAGE->requires->jquery_plugin('qtip-css');

echo $OUTPUT->header();
/*include($CFG->dirroot . '/filter/connect/scripts/styles.css');

if ($connect->type == 'video') {
    $text = '<center>[[flashvideo#' . $connect->url . ']]</center>';
} else if ($connect->display) {
    $text = $connect->display;
} else {
    $text = '[[connect#' . $connect->url . ']]';
}*/

//$text = '<center>'. $rtvideo->name .'</center>';

//echo format_text($text);

echo rtvideo_get_player( $rtvideo );
echo '<br/><br/><center>' . $OUTPUT->single_button($CFG->wwwroot . '/course/view.php?id=' . $course->id, get_string('returntocourse', 'rtvideo')) . '</center>';
echo $OUTPUT->footer();
