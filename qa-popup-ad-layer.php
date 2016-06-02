<?php

class qa_html_theme_layer extends qa_html_theme_base
{
	public $plugin_url;

	// needed to get the plugin url
	public function qa_html_theme_layer($template, $content, $rooturl, $request)
	{
		qa_html_theme_base::qa_html_theme_base($template, $content, $rooturl, $request);
		if (!$this->shouldShowPopup()) {
			return;
		}
		global $qa_layers;
		$this->plugin_url = $qa_layers['POPUP ADD']['urltoroot'];
	}

	public function head_script()
	{
		// insert Javascript into the <head>
	qa_html_theme_base::head_script();
		if (!$this->shouldShowPopup()) {
			return;
		}

		$library_src = qa_opt('site_url').$this->plugin_url.'/vender/popup.js';
		$this->output('<script type="text/javascript" src="'.$library_src.'"></script>');

		$htmls = array();
		for ( $i = 1; $i <= 4; $i++ ) {
			$tmp = qa_opt('qa_popup_ad_html_' . $i);
			if (!empty($tmp)) {
				$htmls[] = $tmp;
			}
		}
		$index = mt_rand(0, count($htmls) - 1);
		$html = $htmls[$index];
		$html = preg_replace(array('/\r\n/', '/\r/', '/\n/'), '', $html); // remove line break

		$box_width = (int)qa_opt('qa_popup_ad_box_width');
		if (!is_numeric($box_width) || $box_width <= 0) {
			$box_width = 320;
		}
		$box_height = (int)qa_opt('qa_popup_ad_box_height');
		if (!is_numeric($box_height) || $box_height <= 0) {
			$box_height = 300;
		}
		$percentage = (int)qa_opt('qa_popup_ad_scroll_percentage');
		$js = <<<"EOT"
<script>
$(document).ready(function () {
	var popup = new $.Popup({
		width : {$box_width},
		height: {$box_height}
	});
	percentage = {$percentage}
	popupflg = false;
	if (percentage <= 0) {
		popup.open('$html', 'html');
		popupflg = true;
	}
	$(window).scroll(function (e) {
		var window = $(e.currentTarget),
		scrollTop = window.scrollTop(),
		documentHeight = $(document).height();
		scrollHeight = documentHeight * (percentage / 100)

		if ( !popupflg && scrollHeight <= scrollTop) {
			popup.open('$html', 'html');
			popupflg = true;
		}
	});
});
</script>
EOT;

		$this->output($js);
	}

	public function head_css()
	{
		qa_html_theme_base::head_css();

		if (!$this->shouldShowPopup()) {
			return;
		}

		$css = <<<"EOT"
<style>
/*------------------------------- POPUP.CSS -------------------------------*/
.popup_back {
	height: 100%; left: 0;
	position: fixed;
	top: 0; width: 100%; z-index: 100;
}
.popup_cont {
	position: fixed;
	z-index: 102;
}
.preloader {
	z-index: 101;
}

/*--------------------- EDIT BELOW */
.popup_close {
	color: #888;
	cursor: pointer;
	position: absolute;
	padding: 5px;
	right: 5px;
	top: 0;
}

.popup_close:hover {
	color: #111;
}

.popup_back {
	cursor: pointer;
	background-color: #222;
}

div.popup {
	background: #fff;
	padding: 25px;
	box-shadow: 0 3px 10px #222;
}

.preloader {
	left: 50%;
	margin: -10px 0 0 -25px;
	position: fixed;
	top: 50%;
}
</style>
EOT;

		$this->output($css);
	}

	public function head_custom()
	{
		qa_html_theme_base::head_custom();
	}

	private function shouldShowPopup()
	{
		// $blackList = array('/ask', '/login', '/reset');
		$blackList = explode("\n", qa_opt('qa_popup_ad_exclude_pages'));
		$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

		if (in_array($path, $blackList)) {
			return false;
		}

		if ( !(bool)qa_opt('qa_popup_ad_show_logged_in') && qa_is_logged_in() ) {
			return false;
		}

		if ( !(bool)qa_opt('qa_popup_ad_only_first_access') ) {
			return true;
		}

		if ($this->isJustLand()) {
			return true;
		}

		return false;
	}

	private function isJustLand()
	{
		$referer = $_SERVER['HTTP_REFERER'];

		// no referer
		if (empty($referer)) {
			return true;

			// referer
		} else {
			$url = parse_url($referer);
			$siteUrl = parse_url(qa_opt('site_url'));
			if ($url['host'] != $siteUrl['host']) {
				return true;
			} else {
				return false;
			}
		}
	}
}
