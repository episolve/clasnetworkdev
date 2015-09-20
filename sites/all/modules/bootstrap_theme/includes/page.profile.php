<?php

require_once dirname(__FILE__) . '/bootstrap_theme.api.php';
require_once dirname(__FILE__) . '/../../privatemsg/privatemsg.pages.inc';

function bootstrap_theme_profile_page($account) {
    global $user;

    bootstrap_theme_set_page_content_class('public-profile-page');
    //bootstrap_theme_set_page_title_block('<div class="buttons">'.($user->uid==$account->uid?'<a href="'.url('user/'.$user->uid.'/edit').'" class="button">Edit</a>':'').'<a href="'.url('messages/new').'" class="button">Message</a>'.(bootstrap_theme_is_editor() && !bootstrap_theme_is_contributor($account)?'<a href="'.url('user/'.$account->uid.'/approve-contributor').'" class="button">Approve to become contributor</a>':'').'</div>');
    bootstrap_theme_set_page_title_block('<div class="buttons">'.($user->uid==$account->uid?'<a href="'.url('user/'.$user->uid.'/edit').'" class="button">Edit</a>':'').'<a href="#new_message_modal" class="button" role="button" data-toggle="modal">Message</a></div>');
    bootstrap_theme_set_page_small_title('<strong>'.format_username($account).'</strong>');

    $output = '';

    $output .= '<div id="new_message_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="new_message_modal_label" aria-hidden="true">';
        $output .= '<div class="modal-header">';
            $output .= '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
            $output .= '<h3 id="new_message_modal_label">New Message</h3>';
        $output .= '</div>';
        $output .= '<div class="modal-body">';
            $output .= _bootstrap_theme_new_message_form(null, null);
        $output .= '</div>';
        $output .= '<div class="modal-footer">';
            $output .= '<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>';
            $output .= '<button class="btn btn-primary">Send Message</button>';
        $output .= '</div>';
    $output .= '</div>';
    $output .= '<div class="public-profile-info">';
        $output .= '<div class="profile-picture">';
            $output .= '<img src="'.bootstrap_theme_get_user_picture($account).'" />';
        $output .= '</div>';
        $output .= '<div class="profile-extra">';
            $output .= '<p class="main">'.bootstrap_theme_display_user_location($account).'</p>';
            $output .= '<p>(Ranking)</p>';
            $output .= '<p>(Badges)</p>';
            $output .= '<p>Specialization</p>';
        $output .= '</div>';
        $output .= '<a href="'.url('user/'.$account->uid.'/about-me').'" class="button">About Me</a>';
        $output .= '<div class="clear"></div>';
        $output .= '<div class="block contributions">';
            $output .= '<div class="title-bar">Contributions</div>';
            $output .= bootstrap_theme_profile_contributions($account);
        $output .= '</div>';
    $output .= '</div>';

    return $output;
}

function bootstrap_theme_profile_contributions($account) {
    $header = array('#');

    $rows = array();
    foreach (bootstrap_theme_get_contributions(NODE_PUBLISHED, $account) as $contribution) {
        $contribution = node_load($contribution->nid);

        if (empty($contribution->og_group_ref))
            continue;
        $collection = node_load($contribution->og_group_ref[$contribution->language][0]['target_id']);
        $row = array();

        $row_output  = '';
        $row_output .= '<div class="ctb-img">';
        $row_output .= '</div>';
        $row_output .= '<div class="ctb-info">';
            $row_output .= '<div class="ctb-title">';
            if (!empty($collection)) :
                $row_output .= '<a href="'.url('collection/'.$collection->nid).'">'.$collection->title.'</a>&nbsp;:&nbsp;';
            endif;
                $row_output .= '<a href="'.url('node/'.$contribution->nid).'">'.$contribution->title.'</a>';
            $row_output .= '</div>';
            $row_output .= '<div class="ctb-date">Submitted '.format_interval(time() - $contribution->created).' ago</div>';
            $row_output .= '<div class="ctb-desc">'.(!empty($contribution->body[$contribution->language])?substr($contribution->body[$contribution->language][0]['value'], 0, 100).'...':'').'</div>';
        $row_output .= '</div>';
        $row_output .= '<div class="clear"></div>';

        $row[] = array('data' => $row_output, 'class' => array('cell-collection-name'));

        $rows[] = $row;
    }

    $output = theme('table', array('header' => $header, 'rows' => $rows, 'attributes' => array('class' => array('datatable-contributions'))));
    $output .= theme('pager');

    return $output;
}

function bootstrap_theme_profile_aboutme_page($account) {

    bootstrap_theme_set_page_content_class('profile-about-me-page public-profile-page');
    bootstrap_theme_set_page_title_block('<div class="buttons"><!--<a href="#" class="button">Add to Network</a>--><a href="'.url('messages/new').'" class="button">Message</a></div>');
    bootstrap_theme_set_page_small_title('<strong>Professor in Bioengineering</strong>');
    bootstrap_theme_set_page_title_class('profile-title');
    drupal_set_title(format_username($account));

    $output = '';

    $output .= '<div class="public-profile-info">';
        $output .= '<div class="profile-picture">';
            $output .= '<img src="'.bootstrap_theme_get_user_picture($account).'" />';
        $output .= '</div>';
    $output .= '<div class="profile-extra">';
        $output .= '<p class="main">'.bootstrap_theme_display_user_location($account).'</p>';
        $output .= '<p>(Ranking)</p>';
        $output .= '<p>(Badges)</p>';
        $output .= '<p>Specialization</p>';
    $output .= '</div>';
    //$output .= '<a href="'.url('user/'.$account->uid.'/about-me').'" class="button">Download PDF</a>';
    $output .= '<div class="clear"></div>';
    $output .= '<div class="block biography">';
        $output .= '<div class="title-bar">Biography</div>';
        $output .= '<div class="block-content">';
            $output .= '<p>'.(!empty($account->field_publications[LANGUAGE_NONE])?nl2br($account->field_publications[LANGUAGE_NONE][0]['value']):'').'</p>';
        $output .= '</div>';
    $output .= '</div>';
    $output .= '<div class="block float-left">';
        $output .= '<div class="title-bar">Experience</div>';
        $output .= '<div class="block-content">';
        	$output .= '<p>'.(!empty($account->field_experience[LANGUAGE_NONE])?nl2br($account->field_experience[LANGUAGE_NONE][0]['value']):'').'</p>';
            /*$output .= '<div class="title">California State University - Chico</div>';
            $output .= '<p>Professsor of Biology</p>';
            $output .= '<div class="date">August 1982 - Present</div>';*/
        $output .= '</div>';
    $output .= '</div>';
    $output .= '<div class="block float-right">';
        $output .= '<div class="title-bar">Education</div>';
        if (!empty($account->field_education[LANGUAGE_NONE])) :
        foreach($account->field_education[LANGUAGE_NONE] as $val) {
        	$field_edus = explode(',', $val['value']);
	        $output .= '<div class="block-content">';
	            $output .= '<div class="title">'.(!empty($field_edus[0])?nl2br($field_edus[0]):'').'</div>';
	            $output .= '<p>'.(!empty($field_edus[1])?nl2br($field_edus[1]):'').'</p>';
	            $output .= '<div class="date">'.(!empty($field_edus[2])?nl2br($field_edus[2]):'').'</div>';
	        $output .= '</div>';
		}
		endif;
    $output .= '</div>';
    $output .= '<div class="clear"></div>';
    $output .= '<div class="block float-left">';
        $output .= '<div class="title-bar">Expertise and Interests</div>';
        $output .= '<div class="block-content">';
            $output .= '<p>'.(!empty($account->field_expertise[LANGUAGE_NONE])?nl2br($account->field_expertise[LANGUAGE_NONE][0]['value']):'').'</p>';
			//$output .= '<p>Genetics</p>';
        $output .= '</div>';
    $output .= '</div>';
    $output .= '<div class="block float-right">';
        $output .= '<div class="title-bar">Associations</div>';
        $output .= '<div class="block-content">';
        $output .= '<p>'.(!empty($account->field_work_address[LANGUAGE_NONE])?nl2br($account->field_work_address[LANGUAGE_NONE][0]['value']):'').'</p>';
            //$output .= '<div class="title">Southern Cal. American Society for Microbiology (SCASM)</div>';
			//$output .= '<p>Member</p>';
			//$output .= '<div class="date">December 2010 - Present</div>';
        $output .= '</div>';
    $output .= '</div>';
    $output .= '<div class="clear"></div>';
    $output .= '<div class="block activity-feeds">';
        $output .= '<div class="title-bar">Contributors</div>';
        $output .= '<div class="block-content">';
            $output .= bootstrap_theme_profile_aboutme_activity_feeds($account);
        $output .= '</div>';
    $output .= '</div>';

    return $output;
}

function bootstrap_theme_profile_aboutme_activity_feeds($account) {
    $output = '';

    foreach (bootstrap_theme_get_contributions(NODE_PUBLISHED, $account) as $contribution) {
        $contribution = node_load($contribution->nid);

        if (empty($contribution->og_group_ref))
            continue;

        $collection = node_load($contribution->og_group_ref[$contribution->language][0]['target_id']);

        $output .= '<div class="profile-activity-feed">';
            $output .= '<div class="feed-title title"><span>Contributed</span> <a href="'.url('node/'.$collection->nid).'">'.$collection->title.'</a> : <a href="'.url('node/'.$contribution->nid).'">'.$contribution->title.'</a></div>';
            $output .= '<div class="feed-time date">'.format_interval(time() - $contribution->created).' ago</div>';
            $output .= '<div class="feed-desc">'.(!empty($contribution->body[$contribution->language])?substr($contribution->body[$contribution->language][0]['value'], 0, 100).'...':'').'</div>';
        $output .= '</div>';
    }

    return $output;
}

function bootstrap_theme_user_edit_page($account) {

    require_once(drupal_get_path('module', 'user') . '/user.pages.inc');

    $output = '';

    $output .= '<div class="public-profile-page">';
        $output .= '<div class="page-title-bar">';
            $output .= '<div class="inner">';
                $output .= '<div class="profile-title">';
                    $output .= '<h2>My Profile</h2>';
                    $output .= '<strong>'.format_username($account).'</strong>';
                $output .= '</div>';
                $output .= '<div class="profile-buttons">';
                    //$output .= '<a href="#" class="button">Add to Network</a>';
                    $output .= '<a href="'.url('messages/new').'" class="button">Message</a>';
                $output .= '</div>';
            $output .= '</div>';
        $output .= '</div>';
        $output .= '<div class="public-profile-info">';
            $output .= '<div class="profile-picture">';
                $output .= '<img src="'.bootstrap_theme_get_user_picture($account).'" />';
            $output .= '</div>';
            $output .= '<div class="profile-extra">';
                $output .= '<p class="main">Princeton University, Princeton, NJ</p>';
                $output .= '<p>(Ranking)</p>';
                $output .= '<p>(Badges)</p>';
                $output .= '<p>Specialization</p>';
            $output .= '</div>';
            $output .= '<a href="'.url('user/'.$account->uid.'/about-me').'" class="button">About Me</a>';
            $output .= '<div class="clear"></div>';
            $output .= '<div class="block contributions">';
                $output .= '<div class="title-bar">Contributions</div>';
                $output .= bootstrap_theme_profile_contributions($account);
            $output .= '</div>';
        $output .= '</div>';
    $output .= '</div>';

    $form = drupal_get_form('user_profile_form', array($account, 'account'));

    $output .= drupal_render($form);

    return $output;
}

function bootstrap_theme_user_profile_form_submit($form, &$form_state) {
    user_profile_form_submit($form, $form_state);
}

function bootstrap_theme_user_profile_form_validate($form, &$form_state) {
    user_profile_form_validate($form, $form_state);
}

function bootstrap_theme_approve_contributor($account) {
    global $user;

    $role = user_role_load_by_name(USER_TYPE_CONTRIBUTOR);
    $account->roles[$role->rid] = USER_TYPE_CONTRIBUTOR;

    user_save($account);

    bootstrap_theme_send_message($user, $account, 'You are now contributor.', 'You are now contributor.');

    drupal_goto('user/'.$account->uid);
}


/**
 * New Message form
 */

function _bootstrap_theme_new_message_form($form, $form_state) {

    if ($form == null && $form_state == null) {
        $form = drupal_get_form('bootstrap_theme_new_message_form');
        $form_output = drupal_render($form);
    } else {
        $form_output = drupal_render(drupal_rebuild_form('bootstrap_theme_new_message_form', $form_state));
    }

    return '<div id="new_message_form_container">'.$form_output.'</div>';
}

function bootstrap_theme_new_message_form($form, &$form_state) {

    $page_title = drupal_get_title();
    $output = privatemsg_new($form, $form_state);

	$user = user_load(arg(1));
    $output['recipient']['#default_value'] = $user->name;

    drupal_set_title($page_title);

    return $output;
}

function ajax_bootstrap_theme_new_message_form_submit($form, $form_state) {
    return _bootstrap_theme_new_message_form($form, $form_state);
}

function bootstrap_theme_new_message_form_submit(&$form, &$form_state) {
    privatemsg_new_submit($form, $form_state);
}

function bootstrap_theme_new_message_form_validate(&$form, &$form_state) {
    privatemsg_new_validate($form, $form_state);
}