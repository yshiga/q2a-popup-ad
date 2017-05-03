<?php

class qa_popup_ad_admin
{
	public function init_queries($tableslc)
	{
		return;
	}
	public function option_default($option)
	{
		switch ($option) {
			case 'qa_popup_ad_only_first_access':
				return true;
			case 'qa_popup_ad_show_logged_in':
				return true;
			case 'qa_popup_ad_scroll_percentage':
				return 0;
			case 'qa-popup_ad_show_mobile':
				return false;
			default:
				return;
		}
	}

	public function allow_template($template)
	{
		return $template != 'admin';
	}

	public function admin_form(&$qa_content)
	{
		// process the admin form if admin hit Save-Changes-button
		$ok = null;
		if (qa_clicked('qa-popup-ad-save')) {
			qa_opt('qa_popup_ad_only_first_access', (bool)qa_post_text('qa_popup_ad_only_first_access'));
			qa_opt('qa_popup_ad_show_logged_in', (bool)qa_post_text('qa_popup_ad_show_logged_in'));
			qa_opt('qa_popup_ad_scroll_percentage', qa_post_text('qa_popup_ad_scroll_percentage'));
			qa_opt('qa_popup_ad_show_mobile', (bool)qa_post_text('qa_popup_ad_show_mobile'));
			$ok = qa_lang('admin/options_saved');
		}

		// form fields to display frontend for admin
		$fields = array();

		$fields[] = array(
			'label' => qa_lang('qa_popup_ad_lang/show_only_first'),
			'type' => 'checkbox',
			'value' => qa_opt('qa_popup_ad_only_first_access'),
			'tags' => 'name="qa_popup_ad_only_first_access"',
		);

		$fields[] = array(
			'label' => qa_lang('qa_popup_ad_lang/show_logged_in'),
			'type' => 'checkbox',
			'value' => qa_opt('qa_popup_ad_show_logged_in'),
			'tags' => 'name="qa_popup_ad_show_logged_in"',
		);

		$fields[] = array(
			'label' => qa_lang('qa_popup_ad_lang/show_mobile'),
			'type' => 'checkbox',
			'value' => qa_opt('qa_popup_ad_show_mobile'),
			'tags' => 'name="qa_popup_ad_show_mobile"',
		);

		$fields[] = array(
			'label' => qa_lang('qa_popup_ad_lang/scroll'),
			'type' => 'number',
			'suffix' => '%(0-100)',
			'value' => qa_opt('qa_popup_ad_scroll_percentage'),
			'tags' => 'name="qa_popup_ad_scroll_percentage"',
		);

		return array(
			'ok' => ($ok && !isset($error)) ? $ok : null,
			'fields' => $fields,
			'buttons' => array(
				array(
					'label' => qa_lang_html('main/save_button'),
					'tags' => 'name="qa-popup-ad-save"',
				),
			),
		);
	}
}
