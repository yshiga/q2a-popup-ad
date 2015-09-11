<?php
class qa_html_theme_layer extends qa_html_theme_base {

	function head_script() {// insert Javascript into the <head>
//		$this->content['script'][]=
  		qa_html_theme_base::head_script();
	}

	function head_css() {

		if($this->shouldShowPopup()) {
		} else {
			qa_html_theme_base::head_css();
		}

	}

	private function shouldShowPopup(){
		if(qa_is_logged_in()) {
			return false;
		}

		if($this->isJustLand()) {
			return true;
		} 

		return false;
	}

	private function isJustLand(){

		$referer = $_SERVER['HTTP_REFERER'];

		// no referer
		if(empty($referer)) {
			return true;

			// referer
		} else {
			$url = parse_url($referer);
			$siteUrl = parse_url(qa_opt('site_url'));
			if($url['host'] != $siteUrl['host']){
				return true;
			} else {
				return false;
			}
		}
	}
}
