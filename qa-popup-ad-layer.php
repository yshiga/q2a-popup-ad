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
		$js = file_get_contents(POPAD_DIR . '/ad.js');
		$html = file_get_contents(POPAD_DIR . '/ad.html');
		$html = str_replace(PHP_EOL, '', $html);
//		$percentage = qa_opt('qa_popup_ad_scroll_percentage');
		$params = array(
			'^html' => $html,
			'^box_width' => '700',
			'^box_height' => '430',
//			'^percentage' => (!empty($percentage) ? (int)$percentage : 0),
//			'^window' => '".mdl-layout__content"',
		);
		$js = strtr($js, $params);
		$this->output($js);
	}

	public function head_css()
	{
		qa_html_theme_base::head_css();
		if (!$this->shouldShowPopup()) {
			return;
		}
		$css = qa_opt("site_url") . $this->plugin_url . '/style.css';

		$this->output('<link rel="stylesheet" type="text/css" href="' . $css . '" >');
	}

	public function head_custom()
	{
		qa_html_theme_base::head_custom();
	}

	private function shouldShowPopup()
	{
		$blackList = array('/ask', '/login', '/reset');
		$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$show_mobile = qa_opt('qa_popup_ad_show_mobile');
		// モバイルまたはタブレットでは表示しない
		if (!$show_mobile && $this->is_mobile_or_tablet()) {
			return false;
		}

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
		$referer = @$_SERVER['HTTP_REFERER'];

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
	
	private function is_mobile_or_tablet()
	{
		// qa_is_mobile_probably()がiPadをPC扱い
		// Androidは別のカスタマイズでPC扱い
		// ココではモバイルと同じ扱いにしたい
		$loweragent = strtolower(@$_SERVER['HTTP_USER_AGENT']);
		if (strpos($loweragent, 'ipad') !== false
			|| strpos($loweragent, 'android') !== false) {
				return true;
		}
		return qa_is_mobile_probably();
	}
}
