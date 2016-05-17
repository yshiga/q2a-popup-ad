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
            qa_opt('qa-popup-ad-html', qa_post_text('qa-popup-ad-html'));
            $ok = qa_lang('admin/options_saved');
        }

        // form fields to display frontend for admin
        $fields = array();

        $fields[] = array(
            'type' => 'textarea',
            'label' => 'html',
            'tags' => 'name="qa-popup-ad-html"',
            'value' => qa_opt('qa-popup-ad-html'),
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
