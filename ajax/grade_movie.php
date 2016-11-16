<?php  // $Id: grade_movie.php
/**
 * No Display - called from flash movie to grade progress.
 * 
 * @author  Gary Menezes
 * @version $Id: grade_movie.php
 * @package connect
 **/

    require_once("../../../config.php");
    require_once("$CFG->dirroot/mod/rtvideo/lib.php");

    $userid     = optional_param('student_id', 0, PARAM_INT);   // User ID who's playing movie
    $activityid = optional_param('activity_id', 0, PARAM_INT);  // URL of flash movie file playing
    $position   = optional_param('pos', 0, PARAM_INT);          // What position they are in the movie
   
    if ( $activityid != 0 && $userid != 0 && $position != 0 ) {
        rtvideo_movie_launch( $activityid, $userid, "$position" );
    }
?>
