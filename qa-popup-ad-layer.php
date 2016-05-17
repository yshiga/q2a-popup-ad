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

        $html = qa_opt('qa-popup-ad-html');
        $html = preg_replace(array('/\r\n/', '/\r/', '/\n/'), '', $html); // remove line break

        $js = <<<"EOT"
<script>
$(window).load(function () {
	var popup = new $.Popup({
		width : 320,
		height: 300
	});
	popup.open('$html', 'html');
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
        $blackList = array('/ask', '/login', '/reset');
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (in_array($path, $blackList)) {
            return false;
        }

        if (qa_is_logged_in()) {
            return false;
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
