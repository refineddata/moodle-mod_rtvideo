<?php
namespace mod_rtvideo\event;
defined('MOODLE_INTERNAL') || die();
class rtvideo_movieposition extends \core\event\base {
	protected function init() {
		global $CFG;
// 		$this->context = \context_system::instance();
		$this->data['crud'] = 'r'; // c(reate), r(ead), u(pdate), d(elete)
		if( $CFG->branch >= 27 ){
			$this->data['edulevel'] = self::LEVEL_OTHER;
		}else{
			$this->data['level'] = self::LEVEL_OTHER;
		}
		$this->data['objecttable'] = 'rtvideo';
// 		$this->data['anonymous'] = 1;
	}

	public static function get_name() {
		return get_string('movieposition', 'mod_rtvideo');
	}

	public function get_description() {
		return isset( $this->other['description'] ) ? $this->other['description'] : serialize($this->other);
	}

	public function get_url() {
		global $CFG;
		return new \moodle_url("/mod/$this->objecttable/view.php", array('id' => $this->contextinstanceid));
	}

}
?>