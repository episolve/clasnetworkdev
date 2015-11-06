<?php

require_once dirname(__FILE__) . '/bootstrap_theme.api.php';

require_once dirname(__FILE__) . '/../../privatemsg/privatemsg.pages.inc';
require_once dirname(__FILE__) . '/../../privatemsg/privatemsg.module';

function bootstrap_theme_messages_page($arg) {

    bootstrap_theme_set_page_content_class('messages-board');
    bootstrap_theme_set_page_title_block('<div class="buttons"><a href="'.url('messages/new').'" class="button">Compose</a></div>');

    return bootstrap_theme_messages_list();
}

function bootstrap_theme_messages_list($form = null, $form_state = null) {
    if ($form == null && $form_state == null) {
        $form = drupal_get_form('bootstrap_theme_messages_list_form');
        $form_output = drupal_render($form);
    } else {
        $form_output = drupal_render(drupal_rebuild_form('bootstrap_theme_messages_list_form', $form_state));
    }

    return '<div id="messages-list">'.$form_output.'</div>';
}

function bootstrap_theme_messages_list_form($form, &$form_state) {

    global $base_url;
    global $user;

    $header = array(
        'author' => '<a href="#" class="remove-button"><img src="'.$base_url.'/'.drupal_get_path('module', 'bootstrap_theme').'/images/dashboard/icon_remove.png" /></a>',
        'subject' => '&nbsp;',
        'timestamp' => '<a href="'.url('messages/blocked').'" class="button block-user-button">Block User</a>'
    );

    $options = array();
    $message_cnt = 0;
    foreach (privatemsg_sql_list($user)->execute() as $message) {
        $options[$message->thread_id] = array(
            'author' => '<div class="msg-participants">'._bootstrap_theme_get_participants_html($message->participants).'</div>',
            'subject' => '<div class="msg-subject"><a href="'.url('messages/view/'.$message->thread_id).'">'.$message->subject.'</a></div>',
            'timestamp' => '<div class="msg-date">'.date('F j, Y', $message->last_updated).'</div>'
        );

        $message_cnt++;
    }

    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => 'Remove',
        '#attributes' => array('data-ignore-theme' => true),
        '#ajax' => array(
            'event' => 'click',
            'callback' => 'ajax_bootstrap_theme_messages_list_form_submit',
            'wrapper' => 'messages-list',
            'method' => 'replace',
            'effect' => 'fade',
        )
    );

    $form['action'] = array(
        '#type' => 'hidden',
        '#value' => 'remove',
    );

    $form['table'] = array(
        '#type' => 'tableselect',
        '#header' => $header,
        '#options' => $options,
        '#empty' => t('No messages found'),
        '#attributes' => array('class' => array('data-table',  'messages-list')),
    );

    return $form;
}

function _bootstrap_theme_get_participants_html($participants) {
    global $user;
    if (!empty($participants))
        $participants = _privatemsg_generate_user_array($participants);
    else
        $participants = array($user);

    $output  = '';
    foreach ($participants as $account) {
        if (!empty($output))
            $output .= ', ';
        $output .= '<a href="'.url('user/'.$account->uid).'">'.format_username($account).'</a>';
    }

    return $output;
}

function ajax_bootstrap_theme_messages_list_form_submit($form, $form_state) {
    return bootstrap_theme_messages_list($form, $form_state);
}

function bootstrap_theme_messages_list_form_submit(&$form, &$form_state) {
    global $user;

    $message_ids = $form_state['values']['table'];
    $action = $form_state['values']['action'];

    $deleted_cnt = 0;
    foreach ($message_ids as $message_id) {
        if (empty($message_id))
            continue;

        if ($action == 'remove') {
            privatemsg_message_change_delete($message_id, 1);
            $delete_value = REQUEST_TIME;
            $update = db_update('pm_index')
			    ->fields(array('deleted' => $delete_value))
			    ->condition('mid', $pmid)
			    ->condition('recipient', $user->uid);
			    ;
        } else if ($action == 'blocking-user') {
            foreach (privatemsg_sql_participants($message_id)->execute() as $participant) {
            }
        }
        $deleted_cnt++;
    }

    drupal_set_message(format_plural($deleted_cnt, 'Deleted 1 message.', 'Deleted @count messages.'));
}

function bootstrap_theme_messages_list_form_validate($form, &$form_state) {
    $message_ids = $form_state['values']['table'];

    $is_selected = false;
    foreach ($message_ids as $message_id) {
        if (!empty($message_id))
            $is_selected = true;
    }

    if (!$is_selected)
        form_set_error('', t('No message selected.'));
}
