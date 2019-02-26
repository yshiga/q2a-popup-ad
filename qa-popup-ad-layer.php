<?php

class qa_html_theme_layer extends qa_html_theme_base
{
	public $plugin_url;
	private $should_show_popup = false;

	// needed to get the plugin url
	public function __construct($template, $content, $rooturl, $request)
	{
		$this->should_show_popup = $this->shouldShowPopup();
		qa_html_theme_base::qa_html_theme_base($template, $content, $rooturl, $request);
		if (!$this->should_show_popup) {
			return;
		}
		global $qa_layers;
		$this->plugin_url = $qa_layers['POPUP ADD']['urltoroot'];
	}

	public function head_script()
	{
		// insert Javascript into the <head>
		qa_html_theme_base::head_script();

		if (!$this->should_show_popup) {
			return;
		}
		$library_src = qa_opt('site_url').$this->plugin_url.'/vender/popup.js';
		$this->output('<script type="text/javascript" src="'.$library_src.'"></script>');
		$js = file_get_contents(POPAD_DIR . '/ad.js');
		$html_tmpl = file_get_contents(POPAD_DIR . '/ad.html');
		$html_tmpl = str_replace(PHP_EOL, '', $html_tmpl);
		$subs = array(
			'^image_url'  => qa_opt('qa_popup_ad_image_url'),
			'^ad_title'   => qa_opt('qa_popup_ad_title'),
			'^ad_content' => qa_opt('qa_popup_ad_content'),
			'^btn_link'  => qa_opt('qa_popup_ad_btn_link'),
			'^btn_label'  => qa_opt('qa_popup_ad_btn_label'),
			'^regist_facebook' => qa_lang('qa_popup_ad_lang/regist_facebook'),
			'^regist_twitter' => qa_lang('qa_popup_ad_lang/regist_twitter'),
			'^regist_google' => qa_lang('qa_popup_ad_lang/regist_google'),
			'^regist_email' => qa_lang('qa_popup_ad_lang/regist_email'),
		);
		$html = strtr($html_tmpl, $subs);
		$percentage = qa_opt('qa_popup_ad_scroll_percentage');
		$params = array(
			'^html' => $html,
			'^box_width' => '700',
			'^box_height' => '430',
			'^percentage' => (!empty($percentage) ? (int)$percentage : 0),
			'^window' => 'window',
		);
		$js = strtr($js, $params);
		$this->output($js);
	}

	public function head_css()
	{
		qa_html_theme_base::head_css();
		if (!$this->should_show_popup) {
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
		$blackList = array('/ask', '/login', '/reset', '/register', '/blog/new', '/forgot');
		$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

		// モバイルでは表示しない(オプションに従う)
		if (!(bool)qa_opt('qa_popup_ad_show_mobile') && qa_is_mobile_probably()) {
			return false;
		}

		// 表示しないページ
		if (in_array($path, $blackList)) {
			return false;
		}

		// ログインユーザーには表示しない(オプションに従う)
		if ( !(bool)qa_opt('qa_popup_ad_show_logged_in') && qa_is_logged_in() ) {
			return false;
		}

		if($this->isJustLand()) {
			return true;
		} else if (!(bool)qa_opt('qa_popup_ad_only_first_access') ) {
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

	private function is_post_recently($userid) {
 		// 2週間以内に1件以上投稿しているかチェック
		$day = 14;
		$min = 0;

        	$sql = "SELECT count(*)";
	        $sql .= " FROM ^posts";
      		$sql .= " WHERE (TYPE = 'Q' OR TYPE = 'A') AND userid = #";
	        $sql .= " AND created > DATE_SUB(NOW(), INTERVAL # DAY)";

       		$count = qa_db_read_one_value(qa_db_query_sub($sql, $userid, $day));
		return ($count > $min);
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
