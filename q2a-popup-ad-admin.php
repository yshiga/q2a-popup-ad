<?php
class q2a_popup_ad_admin {
	function init_queries($tableslc) {
		return null;
	}
	function option_default($option) {
		switch($option) {
			case 'q2a-popup-ad-html':
				return ''; 
			default:
				return null;
		}
	}
		
	function allow_template($template) {
		return ($template!='admin');
	}       
		
	function admin_form(&$qa_content){                       
		// process the admin form if admin hit Save-Changes-button
		$ok = null;
		if (qa_clicked('q2a-popup-ad-save')) {
			qa_opt('q2a-popup-ad-html', qa_post_text('q2a-popup-ad-html'));
			$ok = qa_lang('admin/options_saved');
		}
		
		// form fields to display frontend for admin
		$fields = array();
		
		$fields[] = array(
			'type' => 'textarea',
			'label' => 'html',
			'tags' => 'name="q2a-popup-ad-html"',
			'value' => qa_opt('q2a-popup-ad-html'),
		);

		return array(     
			'ok' => ($ok && !isset($error)) ? $ok : null,
			'fields' => $fields,
			'buttons' => array(
				array(
					'label' => qa_lang_html('main/save_button'),
					'tags' => 'name="q2a-popup-ad-save"',
				),
			),
		);
	}
}

