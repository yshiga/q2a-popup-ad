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
			case 'qa-popup-ad-html':
				return '';
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
			qa_opt('qa_popup_ad_box_width', qa_post_text('qa_popup_ad_box_width'));
			qa_opt('qa_popup_ad_box_height', qa_post_text('qa_popup_ad_box_height'));
			qa_opt('qa_popup_ad_html_1', qa_post_text('qa_popup_ad_html_1'));
			qa_opt('qa_popup_ad_html_2', qa_post_text('qa_popup_ad_html_2'));
			qa_opt('qa_popup_ad_html_3', qa_post_text('qa_popup_ad_html_3'));
			qa_opt('qa_popup_ad_html_4', qa_post_text('qa_popup_ad_html_4'));
			qa_opt('qa_popup_ad_exclude_pages', qa_post_text('qa_popup_ad_exclude_pages'));
			// qa_opt('qa-popup-ad-html', qa_post_text('qa-popup-ad-html'));
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
			'label' => qa_lang('qa_popup_ad_lang/scroll'),
			'type' => 'number',
			'suffix' => '%(0-100)',
			'value' => qa_opt('qa_popup_ad_scroll_percentage'),
			'tags' => 'name="qa_popup_ad_scroll_percentage"',
		);

		$fields[] = array(
			'label' => qa_lang('qa_popup_ad_lang/popup_width'),
			'type' => 'number',
			'value' => (int)qa_opt('qa_popup_ad_box_width'),
			'suffix' => qa_lang('qa_popup_ad_lang/pixels'),
			'tags' => 'name="qa_popup_ad_box_width"',
		);

		$fields[] = array(
			'label' => qa_lang('qa_popup_ad_lang/popup_height'),
			'type' => 'number',
			'value' => (int)qa_opt('qa_popup_ad_box_height'),
			'suffix' => qa_lang('qa_popup_ad_lang/pixels'),
			'tags' => 'name="qa_popup_ad_box_height"',
		);
		$tmplabel = qa_lang('qa_popup_ad_lang/content_html');
		$label = strtr($tmplabel, array( '^1' => '1' ));
		$fields[] = array(
			'label' => $label,
			'type' => 'textarea',
			'value' => qa_opt('qa_popup_ad_html_1'),
			'tags' => 'name="qa_popup_ad_html_1"',
			'rows' => 2,
		);
		$label = strtr($tmplabel, array( '^1' => '2' ));
		$fields[] = array(
			'label' => $label,
			'type' => 'textarea',
			'value' => qa_opt('qa_popup_ad_html_2'),
			'tags' => 'name="qa_popup_ad_html_2"',
			'rows' => 2,
		);
		$label = strtr($tmplabel, array( '^1' => '3' ));
		$fields[] = array(
			'label' => $label,
			'type' => 'textarea',
			'value' => qa_opt('qa_popup_ad_html_3'),
			'tags' => 'name="qa_popup_ad_html_3"',
			'rows' => 2,
		);
		$label = strtr($tmplabel, array( '^1' => '4' ));
		$fields[] = array(
			'label' => $label,
			'type' => 'textarea',
			'value' => qa_opt('qa_popup_ad_html_4'),
			'tags' => 'name="qa_popup_ad_html_4"',
			'rows' => 2,
		);

		$fields[] = array(
			'label' => qa_lang('qa_popup_ad_lang/exclude_page'),
			'type' => 'textarea',
			'value' => qa_opt('qa_popup_ad_exclude_pages'),
			'tags' => 'name="qa_popup_ad_exclude_pages"',
			'rows' => 4,
			'cols' => 15,
		);
		// $fields[] = array(
		// 	'type' => 'textarea',
		// 	'label' => 'html',
		// 	'tags' => 'name="qa-popup-ad-html"',
		// 	'value' => qa_opt('qa-popup-ad-html'),
		// );

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
