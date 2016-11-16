<?php

/**
 * Version details.
 * @package    mod
 * @subpackage rtvideo
 * @copyright  2015 Refined Data Solutions Inc.
 * @author     Elvis Li
 */

$string['modulename_help'] = 'The Refined Video activity integrates the JW Player 7 with the LMS allowing videos to be played directly on a course page, or on a separate activity page. The Refined Video activity supports closed captioning and multi-lingual files so users can watch the video with captions in their preferred language.  Incorporating the standard player options available, the Refined Video activity allows for greater control over the aspect ratio, player controls and start up options.

Linking to videos using the Refined Video activity allows users to be graded based on the position they reach within a video.

Important note: The Refined Video activity cannot be added to a course page where a previous “Connect Video” activity or “Flash Video” tag exists. This will introduce a version conflict with the new JW Player 7, causing the video to produce an error message.  

<a target="_blank" href="http://support.refineddata.com/hc/en-us/articles/206367833">More information on adding the Refined Video activity</a>';
$string['modulenameplural'] = 'Refined Video';
$string['modulename'] = 'Refined Video';
$string['rtvideo:addinstance'] = 'Add a new video';
$string['rtvideo:manage'] = 'Manage videos';
$string['rtvideo:view'] = 'View videos';
$string['pluginadministration'] = 'Refined Video administration';
$string['intro'] = 'Introduction';
$string['pluginname'] = 'Refined Video';
$string['videourl'] = 'Video location URL';
$string['ccurl'] = 'CC location URL';
$string['activityname'] = 'Activity name';
$string['summary'] = 'Summary';
$string['videohdr'] = 'Video';
$string['videodisplayhdr'] = 'Video display';
$string['videourl_help'] = 'Location of video file.';
$string['ccurl_help'] = 'Location of closed captioning file. Supports CC in multiple languages.';
$string['displayoncourse'] = 'Embed player on course page';
$string['displayoncourse_help'] = 'When enabled, default behavior will be to display the video player on the course page. Otherwise, a link to a separate activity page with the player will display.';
$string['width'] = 'Width of player';
$string['width_help'] = 'Set the width of the video player in either pixels (e.g. 300 for 300px) for a fixed width, or percent (e.g. 75%) for responsive mode.';
$string['height'] = 'Height of player';
$string['height_help'] = 'Set the height of the player in pixels (e.g. 200 for 200px).';
$string['gradinghdr'] = 'Grading options';
$string['off'] = 'Simple Grading';
$string['interaction_grading'] = 'Interaction Grading';
$string['detailgrading'] = 'Grading options';
$string['detailgrading_help'] = '<b>Simple Grading</b>: launching the activity = 100%.<br />
<b>Interaction Grading</b>:  pulls the position the user has reached in the video and calculates a  grade percentage when thresholds are selected. 
If no thresholds are selected the position the user reached is stored as the grade.';
$string['tg'] = 'Threshold';
$string['tg_help'] = 'Percent of Video Watched -> Activity Grade';
$string['changeaspectratio'] = 'Change aspect ratio';
$string['changeaspectratio_help'] = 'Enable aspect ratio when wanting the player to be responsive.';
$string['displaycontrols'] = 'Display controls';
$string['displaycontrols_help'] = 'Hide the video player controls from users. This could have a negative impact when using the closed captioning option for multi-lingual videos.';
$string['autostart'] = 'Auto start';
$string['autostart_help'] = 'Automatically start the video when the page opens. This could have an impact on grading if the video is being used as a prerequisite.';
$string['muteoption'] = 'Mute option';
$string['muteoption_help'] = 'Mute the video on start up. Playback on mobile devices will not be muted.';
$string['renderingmode'] = 'Rendering mode';
$string['renderingmode_help'] = 'Choose between rendering the video for HTLM5 or Flash.';
$string['androidhls'] = 'Android HLS';
$string['androidhls_help'] = 'Enable this option to allow JW Player to play HLS video sources on Android devices 4.1 and greater.';
$string['nogrades'] = 'No grades available.';
$string['returntocourse'] = 'Return to the course';
$string['layoverurl'] = 'URL of layover image';
$string['layoverurl_help'] = 'Location of the image file to be displayed before video playback is started.';

//Legacy filter connect
$string['oldplayer']             = 'Use Flash Player';
$string['oldplayer_hint']        = '<font size=\"1\">This setting when checked will disable the JW Player for flash videos and enable the old flash player.</font>';

$string['videoserver']           = 'Full path to video location';
$string['videoserver_hint']      = '<font size=\"1\">This value will be pre-pended to video file names that are not fully qualified.</font>';

$string['videophoto']            = 'Default photo image';
$string['videophoto_hint']       = '<font size=\"1\">The Flash video player will display the image defined above<br />' .
                                         'unless a still image name is given.</font>';

$string['videophotopath']        = 'Full path to Photo Images';
$string['videophotopath_hint']   = '<font size=\"1\">The Flash video player will prepend the path defined above<br />unless the still image name is fully qualified.</font>';

$string['legacyconnectvideosettingshdr']        = 'Legacy Connect video settings';
$string['jwplayersettingshdr']        = 'JW Player settings';

$string['cchdr']        = 'Closed caption';
$string['enablefilepath']        = 'Enable file path';
$string['enablefilepath_help']        = "When enabled the LMS will assume that the closed captioning file's name is the same as the video file's name (including path) except for the extension.<br /> ( eg. video: http://folder/video.mp4, cc: http://folder/video.srt )";
$string['enablemultilang']        = 'Enable multiple languages';
$string['enablemultilang_help']        = 'Allows closed captioning with multiple languages. Requires that "Enable file path" be set to "Yes", and that all CC files have the same location and file name as the video file except for the extension, following the pattern below:<br />
When enabled, the CC file\'s name must be filename.language.extension. ( eg. video: video.mp4, cc: video.en.srt, cc: video.fr.srt )<br />
When disabled, cc file\'s name must be filename.extension. ( eg. video: video.mp4, cc: video.srt )';
$string['rtvideoserver']           = 'Full path to video location';
$string['rtvideoserver_hint']      = '<font size=\"1\">This value will be pre-pended to video filenames that are not fully qualified.</font>';

$string['aspectratio']           = 'Aspect ratio';
$string['aspectratio_help']      = 'Enter an aspect ratio (e.g. 16:9) to override the default.';
$string['aspectratiosetting_help']      = 'Enter an aspect ratio (e.g. 16:9) to be used as a default to allow players on the site to maintain a standard shape.';

$string['ccext']      = 'Closed caption file extension';
$string['ccext_help']      = 'The file extension of all closed caption files used by this LMS. ( eg. srt, vtt )';
$string['movieposition']      = 'Movie position';

