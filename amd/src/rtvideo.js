/**
 * Module details
 * @package    mod
 * @subpackage rtvideo
 * @copyright  2016 Refined Data Solutions Inc.
 * @author     Ras Ferdinando
 */
define(['jquery', 'mod_rtvideo/jwplayer'], function($, player){

    var rtvideo = {
        //the number of parameters must match what is passed in rtvideo/lib.php
        init: function(key, playerid, setupdata, monitordata, monitorurl) {
            window.jwplayer = player;
            window.jwplayer.key = key;

            var sentPoints = [];
            /* commented because never used
            var requestData = {};*/
            var data = monitordata;

            if ($('#' + playerid).length === 0) {
                console.log('no video element found with id: ' + playerid);
                return;
            }

            var playerInstance = window.jwplayer(playerid);

            playerInstance.setup(setupdata);
            //alert(setupdata.mute);
            if (setupdata.mute === true) {
                playerInstance.setMute(true);
            } else {
                playerInstance.setMute(false);
            }

            $(".section .activity.modtype_rtvideo .mod-indent-outer").css("display", "block");
            $(".section .activity.modtype_rtvideo .contentwithoutlink").css("display", "inline");

            playerInstance.onTime(function (event) {
                /* commented because never used
                var position = Math.round(event.position);
                var duration = Math.round(event.duration);
                */
                var percent = Math.round((event.position / event.duration) * 100) / 100;

                var pos = 0;
                if (percent <= 0.11) {
                    pos = 1;

                } else if (percent > 0.19 && percent <= 0.21) {
                    pos = 2;

                } else if (percent > 0.29 && percent <= 0.31) {
                    pos = 3;

                } else if (percent > 0.39 && percent <= 0.41) {
                    pos = 4;

                } else if (percent > 0.49 && percent <= 0.51) {
                    pos = 5;

                } else if (percent > 0.59 && percent <= 0.61) {
                    pos = 6;

                } else if (percent > 0.69 && percent <= 0.71) {
                    pos = 7;

                } else if (percent > 0.79 && percent <= 0.81) {
                    pos = 8;

                } else if (percent > 0.89 && percent <= 0.91) {
                    pos = 9;

                } else if (percent > 0.95) {
                    pos = 10;

                }
                if (pos !== 0 && sentPoints.indexOf(pos) == -1) {
                    sentPoints.push(pos);
                    data.pos = pos;
                    $.post(monitorurl, data);
                }
            });
        },
    };

    return rtvideo;
});