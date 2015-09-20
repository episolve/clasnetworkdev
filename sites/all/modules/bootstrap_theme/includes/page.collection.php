<?php

require_once dirname(__FILE__) . '/bootstrap_theme.api.php';

function bootstrap_theme_collection_page($collection_id = 0) {
    $collection = node_load($collection_id);
    $form = drupal_get_form('bootstrap_theme_collection_contributions_form', $collection_id);
	$contributions_list_form = drupal_get_form('bootstrap_theme_collection_contributions_list_form');

    bootstrap_theme_set_page_title_block('<a href="'.url('dashboard/collections').'" class="button">Return to My Collection</a>');

        global $user;
	$output  = '<div class="collection-page">';
	$output .= bootstrap_theme_collections_sidebar();
	$output .= '<div id="collection-contributions">
					<h4 style="margin-top:0;">'.$collection->title.'</h4>';
	if($user->uid == $collection->uid) { $output .= 'Remove contribution(s) from your collection<br>'; }
	$output .= drupal_render($form);
	$output .= '</div>';
	
	if($user->uid == $collection->uid)
	{
		$output .= '<br><div style="clear:both;"></div><br><div id="all-contributions">Add contribution(s) to your collection<br>';
		$output .= drupal_render($contributions_list_form);
		$output .= '</div>';
		$output .= '<div>';
	}

    return $output;
}

function bootstrap_theme_collection_contributions_form($form, &$form_state) {
    global $base_url;
    global $user;

    $collection_id = $form_state['build_info']['args'][0];
    $collection_data = node_load($collection_id);

    $header = array(
        'contribution' => '<a href="#" class="remove-button"><img src="'.$base_url.'/'.drupal_get_path('module', 'bootstrap_theme').'/images/dashboard/icon_remove.png" /></a>'
    );
    $options = array();
    
    if($user->uid == $collection_data->uid)
	{
		foreach (bootstrap_theme_get_contributions(NODE_PUBLISHED, $user, $collection_id) as $contribution) {
			$contribution = node_load($contribution->nid);
			$options[$contribution->nid] =array(
				'contribution' => '
					<a href="'.url('node/'.$contribution->nid).'" class="ctb-title">'.$contribution->title.'</a>
					<div class="ctb-desc">'.(!empty($contribution->body[$contribution->language])?substr($contribution->body[$contribution->language][0]['value'], 0, 100).'...':'').'</div>
				'
			);
		}
	}
	else
	{
		foreach (bootstrap_theme_get_contributions(NODE_PUBLISHED, $user, $collection_id) as $contribution) {
			$contribution = node_load($contribution->nid);
			$options[$contribution->nid] =array('<a href="'.url('node/'.$contribution->nid).'" class="ctb-title">'.$contribution->title.'</a>
					<div class="ctb-desc">'.(!empty($contribution->body[$contribution->language])?substr($contribution->body[$contribution->language][0]['value'], 0, 100).'...':'').'</div>'
			);
		}
	}

    $form['collection_title'] = array(
		'#type' => 'hidden',
        '#value' => $collection_data->title,
    );

    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => 'Remove',
        '#attributes' => array('data-ignore-theme' => true),
        '#ajax' => array(
            'event' => 'click',
            'callback' => 'ajax_bootstrap_theme_collection_contributions_form_submit',
            'wrapper' => 'collection-contributions',
            'method' => 'replace',
            'effect' => 'fade',
        )
    );

    if($user->uid == $collection_data->uid)
	{
		$form['table'] = array(
			'#type' => 'tableselect',
			'#header' => $header,
			'#options' => $options,
			'#empty' => t('No contributions found'),
			'#attributes' => array('class' => array('data-table', 'collection-contributions')),
		);
	}
	else
	{
		$form['table'] = array(
			'#theme' => 'item_list',
			'#items' => $options,
			'#empty' => t('No contributions found'),
		);
	}

    return $form;
}

function ajax_bootstrap_theme_collection_contributions_form_submit($form, $form_state) {
    return '<div id="collection-contributions"><h4 style="margin-top:0;">'.$form['collection_title']['#value'].'</h4>Remove contribution(s) from your collection<br>'.drupal_render(drupal_rebuild_form('bootstrap_theme_collection_contributions_form', $form_state)).'</div>';
}

function bootstrap_theme_collection_contributions_form_submit(&$form, &$form_state) {
    $contribution_ids = $form_state['values']['table'];
	
	$deleted_cnt = 0;
    foreach ($contribution_ids as $contributor_id) {
        if (empty($contributor_id))
            continue;
		
		$contribution = node_load($contributor_id);
		unset($contribution->field_cnob_collections[$contribution->language][0]);
		unset($contribution->og_group_ref[$contribution->language][0]);
		node_save($contribution);
       	
		//node_delete($contributor_id);
        $deleted_cnt++;
    }
	
    drupal_set_message(format_plural($deleted_cnt, 'Deleted 1 contribution.', 'Deleted @count contributions.'));
}

function bootstrap_theme_collection_contributions_form_validate($form, &$form_state) {
    $contribution_ids = $form_state['values']['table'];

    $is_selected = false;
    foreach ($contribution_ids as $contribution_id) {
        if (!empty($contribution_id))
            $is_selected = true;
    }

    if (!$is_selected)
        form_set_error('', t('No contribution selected.'));
}

function bootstrap_theme_collection_contributions_list_form($form, &$form_state) {
    global $base_url;
    global $user;
	
	if(isset($form_state['values']['nid']))
	{
		$collection_id = $form_state['values']['nid'];
	}
	else
	{
		$query_params = explode('/',$_GET['q']);
		$collection_id = $query_params[1];
	}
    $header = array('contribution' => 'All Contributions');
	
	$added_contributions = array();
	foreach (bootstrap_theme_get_contributions(NODE_PUBLISHED, $user, $collection_id) as $addedcontribution) {
        $added_contributions[] = $addedcontribution->nid;
    }
	
    $options = array();
    foreach (bootstrap_theme_get_contributions(NODE_PUBLISHED) as $contribution)
	{
		if(!in_array($contribution->nid,$added_contributions))
		{
			$contribution = node_load($contribution->nid);
			$options[$contribution->nid] =array(
				'contribution' => '<a href="'.url('node/'.$contribution->nid).'" class="ctb-title">'.$contribution->title.'</a>'
			);
		}
    }
	
	$form['nid'] = array(
        '#type' => 'hidden',
        '#value' => $collection_id
    );
	
    $form['table'] = array(
        '#type' => 'tableselect',
        '#header' => $header,
        '#options' => $options,
        '#empty' => t('No contributions found'),
        '#attributes' => array('class' => array('contribution-table', 'all-contributions')),
    );
	
	$form['submit'] = array(
        '#type' => 'submit',
        '#value' => 'Add to Collection',
        '#attributes' => array('data-ignore-theme' => true),
        '#ajax' => array(
            'event' => 'click',
           	'callback' => 'ajax_bootstrap_theme_collection_contributions_list_form_submit',
            'wrapper' => 'all-contributions',
            'method' => 'replace',
            'effect' => 'fade',
        )
    );

    return $form;
}

function ajax_bootstrap_theme_collection_contributions_list_form_submit($form, $form_state) {
    return '<div id="all-contributions">Add contribution(s) to your collection<br>'.drupal_render(drupal_rebuild_form('bootstrap_theme_collection_contributions_list_form', $form_state)).'</div>';
}

function bootstrap_theme_collection_contributions_list_form_submit(&$form, &$form_state) {
	$contribution_ids = $form_state['values']['table'];
    foreach($contribution_ids as $contributor_id)
	{
        if(empty($contributor_id))
		{
            continue;
		}
		$collection_id = $form_state['values']['nid'];
		$contribution = node_load($contributor_id);
		$contribution->field_cnob_collections[$contribution->language][0]['nid'] = $collection_id;
		$contribution->og_group_ref[$contribution->language][0]['target_id'] = $collection_id;
		$contribution->group_content_access[$contribution->language][0]['value'] = OG_CONTENT_ACCESS_PRIVATE;
		node_object_prepare($contribution);
		node_save($contribution);
    }
    drupal_set_message('Selected contribution has been added to collection.');
}

function bootstrap_theme_collection_contributions_list_form_validate($form, &$form_state) {
    $contribution_ids = $form_state['values']['table'];

    $is_selected = false;
    foreach ($contribution_ids as $contribution_id) {
        if (!empty($contribution_id))
            $is_selected = true;
    }

    if (!$is_selected)
        form_set_error('', t('No contribution selected.'));
}

function bootstrap_theme_collections_sidebar() {
    $collections = bootstrap_theme_get_collections();

    $output  = '<div class="sidebar-collections">';
        $output .= '<h3>Collections</h3>';
    if (!empty($collections)) {
        $output .= '<ul>';
        foreach ($collections as $collection) {
            $output .= '<li><a href="'.url('collection/'.$collection->nid).'">'.$collection->title.'</a></li>';
        }
        $output .= '</ul>';
    }
    $output .= '</div>';

    return $output;
}
