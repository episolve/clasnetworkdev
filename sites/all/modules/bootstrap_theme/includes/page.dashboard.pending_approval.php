<?php

require_once dirname(__FILE__) . '/bootstrap_theme.api.php';

function bootstrap_theme_pending_approval_page() {
    bootstrap_theme_set_page_content_class('pending-approval-page');
    bootstrap_theme_set_page_title_class('only-line');
    drupal_set_title('Pending Approval');

    $output  = '';
    $output .= '<div class="contribution-list">';
        $output .= '<h3>Contributions</h3>';
        $output .= _bootstrap_theme_pending_approval_contributions();
    $output .= '</div>';
    $output .= '<div class="reported-list">';
        $output .= '<h3>Reported</h3>';
        $output .= _bootstrap_theme_pending_approval_reported_contents();
    $output .= '</div>';
    return $output;
}

function _bootstrap_theme_pending_approval_contributions() {

    $header = array('Contribution Data');

    $rows = array();
    foreach (bootstrap_theme_get_contributions(NODE_NOT_PUBLISHED) as $contribution) {
        $contribution = node_load($contribution->nid);
        $collection = node_load($contribution->og_group_ref[$contribution->language][0]['target_id']);
        $row = array();

        $html  = '';
        $html .= '<div class="ctb-title"><a href="'.url('node/'.$collection->nid).'">'.$collection->title.': <a href="'.url('node/'.$contribution->nid).'">'.$contribution->title.'</a>&nbsp;&nbsp;&nbsp;submitted by <a href="'.url('user/'.$contribution->uid).'" class="ctb-author">'.format_username(user_load($contribution->uid)).'</a></div>';
        $html .= '<div class="ctb-added">'.date('F d Y', $contribution->created).'</div>';
        $html .= '<div class="ctb-desc">'.$contribution->body[$contribution->language][0]['value'].'...</div>';
        $row[] = array('data' => $html);

        $rows[] = $row;
    }

    $output = theme('table', array('header' => $header, 'rows' => $rows, 'attributes' => array('class' => array('data-table-contributions'))));
    $output .= theme('pager');

    return $output;
}

function _bootstrap_theme_pending_approval_reported_contents() {
    $header = array('Contribution Data');

    $rows = array();
    foreach (bootstrap_theme_get_contributions(NODE_NOT_PUBLISHED) as $contribution) {
        $contribution = node_load($contribution->nid);
        $collection = node_load($contribution->og_group_ref[$contribution->language][0]['target_id']);
        $row = array();

        $html  = '';
        $html .= '<div class="ctb-title"><a href="'.url('node/'.$collection->nid).'">'.$collection->title.': <a href="'.url('node/'.$contribution->nid).'">'.$contribution->title.'</a></div>';
        $html .= '<div class="ctb-added">'.date('F d Y', $contribution->created).' by '.'<a href="'.url('user/'.$contribution->uid).'" class="ctb-author">'.format_username(user_load($contribution->uid)).'</a>'.'</div>';
        $html .= '<div class="ctb-desc">'.$contribution->body[$contribution->language][0]['value'].'...</div>';
        $row[] = array('data' => $html);

        $rows[] = $row;
    }

    $output = theme('table', array('header' => $header, 'rows' => $rows, 'attributes' => array('class' => array('data-table-reported-contents'))));
    $output .= theme('pager');

    return $output;
}