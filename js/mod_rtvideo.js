/**
 * Version details.
 * @package    mod
 * @subpackage rtvideo
 * @copyright  2015 Refined Data Solutions Inc.
 * @author     Elvis Li
 */

$(document).ready(function () {
    
    hideGradingFields();
    $('#id_detailgrading').change(function () {
        hideGradingFields();
    });
});
 
function hideGradingFields() {
    var value = $('#id_detailgrading').val();
    if (value == 0) {
        $('#fgroup_id_tg1').hide();
        $('#fgroup_id_tg2').hide();
        $('#fgroup_id_tg3').hide();

        $('#fgroup_id_tg1vp').hide();
        $('#fgroup_id_tg2vp').hide();
        $('#fgroup_id_tg3vp').hide();
    } else if (value == 1) {
        $('#fgroup_id_tg1').show();
        $('#fgroup_id_tg2').show();
        $('#fgroup_id_tg3').show();

        $('#fgroup_id_tg1vp').hide();
        $('#fgroup_id_tg2vp').hide();
        $('#fgroup_id_tg3vp').hide();
    } else if (value == 3) {
        $('#fgroup_id_tg1').hide();
        $('#fgroup_id_tg2').hide();
        $('#fgroup_id_tg3').hide();

        $('#fgroup_id_tg1vp').show();
        $('#fgroup_id_tg2vp').show();
        $('#fgroup_id_tg3vp').show();
    }
}