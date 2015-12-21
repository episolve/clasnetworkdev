<?php
require_once dirname(__FILE__) . '/bootstrap_theme.api.php';
require_once dirname(__FILE__) . '/page.collection.php';
require_once dirname(__FILE__) . '/../../privatemsg/privatemsg.pages.inc';

function bootstrap_theme_handler_ustream_data($url) {
  return array();
}

function bootstrap_theme_display_node_content($node) {
    global $user;
	//$flag = flag_get_flag('testflag');
	//echo $flag->is_flagged($node->nid);
	
    if ($node->status == NODE_NOT_PUBLISHED) {
        drupal_access_denied();
        exit;
    }
    
	$referer_url = $_SERVER['HTTP_REFERER'];
    $output  = '';
    $account = user_load($node->uid);
	
	$badge_uri = '';
	$badge_img = '';
	//if(isset($node->field_cnob_assigned_badge[$node->language][0]['value']))
	if(isset($node->field_cnob_assigned_badge[$node->language][0]['nid']))
	{
		$query = db_select('file_managed', 'fm');
		$query = $query->fields('fm', array('filename','uri'));
		$query->leftJoin('field_data_field_badges_badge_image', 'fbbi', 'fbbi.field_badges_badge_image_fid=fm.fid');
		//$query->condition('fbbi.entity_id', $node->field_cnob_assigned_badge[$node->language][0]['value'], '=');
		$query->condition('fbbi.entity_id', $node->field_cnob_assigned_badge[$node->language][0]['nid'], '=');
		$result = $query->execute();
		foreach($result as $data) {
			$badge_uri = $data->uri;
			$badge_img = $data->filename;
		}
	}
	
	if(trim($badge_uri) == '')
	{
		if(!in_array('administrator',$user->roles) && trim($node->type) == 'badges')
		{
			bootstrap_theme_set_page_title_block('<div class="buttons"><a href="../dashboard/badges" class="button">Return to Badges</a></div>');
		}
		else
		{
			bootstrap_theme_set_page_title_block('<div class="buttons"><a href="'.((strpos($referer_url, '/search/site') > 0)? $referer_url : $referer_url).'" class="button">Return to Results</a></div>');
		}
	}
	else
	{
		bootstrap_theme_set_page_title_block('<div style="padding:25px;float:left;"><img src="'.image_style_url('badge_thumb', $badge_uri).'" alt="'.$badge_img.'"/></div><div class="buttons"><a href="'.((strpos($referer_url, '/search/site') > 0)? $referer_url : $referer_url).'" class="button">Return to Results</a></div>');
	}

    if ($node->type == NODE_TYPE_CLAS_CONTRIBUTOR) {
        $change_collection_form = drupal_get_form('bootstrap_theme_change_collection_form', $node);

        bootstrap_theme_set_page_small_title('<span>Submitted by '.format_username($account).' on '.date('F j, Y - g:sa', $node->created).'</span>');
        bootstrap_theme_set_page_content_class('page-view-container node-view');
        //bootstrap_theme_set_page_title_block('<div class="buttons"><a href="'.((strpos($referer_url, '/search/site') > 0)? $referer_url : "#").'" class="button">Return to Results</a></div>');
		
		/*echo "<pre>";
		print_r($node);
		echo "</pre>";
		die;*/
		
		$material_type = taxonomy_term_load($node->field_cnob_learning_object_type[$node->language][0]['tid'])->name;
        if (!empty($node->field_cno_associated_materials))
            $contributor_material = $node->field_cno_associated_materials[$node->language][0];
        else
            $contributor_material = null;
		
		$output .= '<div class="page-container">';
            $output .= '<div class="node-view-left">';
			//$output .= '<img src="'.file_create_url($badge_uri).'" alt="'.$badge_img.'" hwight="50" width="50"/>';
            if ($material_type == 'Video')
			{
                /*$output .= '<div class="node-view-video">';
                    $output .= '<video controls="">';
                        $output .= '<source src="'.file_create_url($contributor_material['uri']).'" />';
                    $output .= '</video>';
                $output .= '</div>';*/
				//$videofile = file_load($node->field_cnob_learn_obj_res_video['und'][0]['fid'])
				
				 $output .= '<div class="node-view-video">';
				 $video = array(
						'#theme' => 'video_embed_field_embed_code',
						'#style' => 'normal',
						'#url' => $node->field_cnob_learn_obj_res_video['und'][0]['video_url'],
					  );
				 $output .= drupal_render($video);
				 $output .= '</div>';
            }
			if ($material_type == 'Audio')
			{
				$fload = file_load($node->field_cnob_learn_obj_res_audio['und'][0]['fid']);
				$audiofile = file_create_url($fload->uri);
				$info = pathinfo($audiofile);
				$op = $info['extension'];
				$output .= '<div class="node-view-audio">';
				$output .= audiofield_get_player($audiofile, $op);
				$output .= '</div>';
            }

  	    //$fload_asso_mate = file_load($node->field_cnob_associated_materials['und'][0]['fid']);
            //$asso_mate_file_uri = file_create_url($fload_asso_mate->uri);
	    $asso_mate_file_uri = '';
            if(isset($node->field_cnob_associated_materials['und']))
            {
		$fload_asso_mate = file_load($node->field_cnob_associated_materials['und'][0]['fid']);
		$asso_mate_file_uri = file_create_url($fload_asso_mate->uri);
	    }

            $area_of_study = taxonomy_term_load($node->field_cnob_area_of_study[$node->language][0]['tid']);
                $output .= '<ul class="node-view-fields">';
                    $output .= '<li><strong>Category:</strong><span>'.taxonomy_term_load($node->field_cnob_category[$node->language][0]['tid'])->name.'</span></li>';
                    $output .= '<li><strong>User Type:</strong><span>'.$node->field_cnob_user_type[$node->language][0]['value'].'</span></li>';
                    $output .= '<li><strong>Area of Study:</strong><span>'.(!empty($area_of_study)?$area_of_study->name:'').'</span></li>';
                    $output .= '<li><strong>Grade Level:</strong><span>'.taxonomy_term_load($node->field_cnob_grade_level[$node->language][0]['tid'])->name.'</span></li>';
                    $output .= '<li><strong>Relevant Standards:</strong><span>'.taxonomy_term_load($node->field_cnob_relevant_standards[$node->language][0]['tid'])->name.'</span></li>';
                    $output .= '<li><strong>Learning Object Type:</strong><span>'.$material_type.'</span></li>';
		    
		    //$output .= '<li><strong>Associated Materials:</strong><span><a href="'.$asso_mate_file_uri.'" target="_blank">'.$node->field_cnob_associated_materials[$node->language][0]['filename'].'</a></span></li>';
		    if($asso_mate_file_uri != '')
                    {
			$output .= '<li><strong>Associated Materials:</strong><span><a href="'.$asso_mate_file_uri.'" target="_blank">'.$node->field_cnob_associated_materials[$node->language][0]['filename'].'</a></span></li>';
		    }

					if($material_type == 'Link')
					{
						$output .= '<li><strong>Learning Object Link:</strong><span><a href="'.$node->field_cnob_learn_obj_res_link[$node->language][0]['value'].'" target="_blank">'.$node->field_cnob_learn_obj_res_link[$node->language][0]['value'].'</a></span></li>';
					}
					else if($material_type == 'Document')
					{
						$docload = file_load($node->field_cnob_learn_obj_res_doc['und'][0]['fid']);
						$output .= '<li><strong>Learning Object Document:</strong><span><a href="'.file_create_url($docload->uri).'" target="_blank">'.$docload->filename.'</a></span></li>';
					}
                $output .= '</ul>';
                $output .= '<div class="node-view-body">';
                    $output .= (!empty($node->body[LANGUAGE_NONE])?$node->body[LANGUAGE_NONE][0]['value']:'');
                $output .= '</div>';
                $output .= '<div class="node-view-tags">';
                    $output .= '<strong>Tags:</strong>&nbsp;'.bootstrap_theme_render_html_tags($node->field_cnob_tags);
                $output .= '</div>';
            $output .= '</div>';
            if (user_is_logged_in()) :
            $output .= '<div class="node-view-right">';
                $output .= '<div id="collection_ids_for_contribution">';
                    $output .= drupal_render($change_collection_form);
                $output .= '</div>';
                $output .= '<div class="node-view-share">';
                    $output .= '<strong>Share</strong>';
                    $output .= '<a class="button" href="#share_with_member_modal" role="button" data-toggle="modal">Share with a member</a>';
                    $output .= '<div id="share_with_member_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="share_with_member_modal_label" aria-hidden="true">';
                        $output .= '<div class="modal-header">';
                            $output .= '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
                            $output .= '<h3 id="share_with_member_modal_label">Share with a member</h3>';
                        $output .= '</div>';
                        $output .= '<div class="modal-body">';
                            $output .= _bootstrap_theme_share_with_member_form(null, null);
                        $output .= '</div>';
                        $output .= '<div class="modal-footer">';
                            $output .= '<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>';
                            $output .= '<button class="btn btn-primary">Save changes</button>';
                        $output .= '</div>';
                    $output .= '</div>';
                    $output .= '<!-- AddThis Button BEGIN -->
							<div class="addthis_toolbox addthis_default_style addthis_32x32_style">
							<a href="https://www.facebook.com/" class="addthis_button_facebook"></a>
							<a href="https://twitter.com/" class="addthis_button_twitter"></a>
							<a class="addthis_button_google_plusone_share"></a>
							<a href="https://www.linkedin.com/" class="addthis_button_linkedin"></a>
							</div>
							<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
							<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4f0ac6263266f930"></script>
							<!-- AddThis Button END -->';
							
                $output .= '</div>';
            $output .= '</div>';
            endif;
        $output .= '</div>';
        $output .= '<div class="clear"></div>';
    $output .= '</div>';
        $output .= '<div class="page-title-bar small">';
            $output .= '<div class="inner">';
                $output .= '<div class="page-title">';
                    $output .= '<h3>Comments</h3>';
                    $output .= '<a id="comments" href="#" class="element-invisible">Comments</a>';
                $output .= '</div>';
            $output .= '</div>';
        $output .= '</div>';
        $output .= '<div class="page-container">';
            $output .= _bootstrap_theme_comments_view($node);
        //$output .= '</div>';
    } else if ($node->type == NODE_TYPE_CLAS_COLLECTION) {
        $output = bootstrap_theme_collection_page($node->nid);
    } else {
        $output = node_page_view($node);
    }
	
    return $output;
}

/*******************************************************************************
 *
 * Create Change Collection Form
 *
 *******************************************************************************/
function bootstrap_theme_change_collection_form($form, &$form_state) {

    $node = $form_state['build_info']['args'][0];
    $cnob_collections_array = array();
    if(isset($node->field_cnob_collections[$node->language]) && count($node->field_cnob_collections[$node->language]) > 0)
    {
		for($cv=0; $cv<count($node->field_cnob_collections[$node->language]); $cv++)
		{
			$cnob_collections_array[] = $node->field_cnob_collections[$node->language][$cv]['nid'];
		}
	}

    $form['collection_id'] = array(
        '#type' => 'select',
        '#title' => 'Collection',
        '#attributes' => array(),
        '#required' => TRUE,
        '#multiple' => TRUE,
        '#attributes' => array('size'=>4),
        '#options' => bootstrap_theme_get_collection_options(array('' => 'Add to a Collection(s)')),
        //'#default_value' => (!empty($node->og_group_ref[LANGUAGE_NONE])?$node->og_group_ref[LANGUAGE_NONE][0]['target_id']:''),
        '#default_value' => array_values($cnob_collections_array),
    );
    $form['nid'] = array(
        '#type' => 'hidden',
        '#attributes' => array(),
        '#value' => $node->nid
    );

    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => 'Change',
        '#attributes' => array('data-ignore-theme' => true),
        '#ajax' => array(
            'event' => 'click',
            'callback' => 'ajax_bootstrap_theme_change_collection_form_submit',
            'wrapper' => 'collection_ids_for_contribution',
            'method' => 'replace',
            'effect' => 'fade'
        )
    );

    $form['#theme'] = 'bootstrap_theme_change_collection_form';

    return $form;
}

function ajax_bootstrap_theme_change_collection_form_submit($form, $form_state) {
    return '<div id="collection_ids_for_contribution">'.drupal_render(drupal_rebuild_form('bootstrap_theme_change_collection_form', $form_state)).'</div>';
}

function theme_bootstrap_theme_change_collection_form($variables) {
    global $user;

    $form = $variables['form'];

    $output  = drupal_render($form['collection_id']);
    $output .= drupal_render($form['nid']);
    $output .= drupal_render($form['submit']);
    $output .= drupal_render_children($form);

    return $output;
}
function bootstrap_theme_change_collection_form_submit(&$form, &$form_state) {
    $collection_form = $form_state['values'];
    $contribution = node_load($collection_form['nid']);
    
    if(count($collection_form['collection_id']) > 0)
	{
		$cv = 0;
		unset($contribution->field_cnob_collections[$contribution->language]);
		foreach($collection_form['collection_id'] as $cval)
		{
			$contribution->field_cnob_collections[$contribution->language][$cv]['nid'] = $cval;
			$cv++;
		}
	}
	//$contribution->field_cnob_collections[$contribution->language][0]['nid'] = $collection_form['collection_id'];
    //$contribution->og_group_ref[$contribution->language][0]['target_id'] = $collection_form['collection_id'];
    $contribution->group_content_access[$contribution->language][0]['value'] = OG_CONTENT_ACCESS_PRIVATE;

    node_object_prepare($contribution);
    node_save($contribution);

    drupal_set_message('Successfully added to this collection.');
    $form_state['no_redirect'] = TRUE;
    $form_state['rebuild'] = TRUE;
    $form_state['programmed'] = FALSE;
}

function bootstrap_theme_change_collection_form_validate($form, &$form_state) {
    $values = $form_state['values'];
    if (empty($values['collection_id'])) {
        form_set_error('collection_id', 'Collection field is required.');
    }
}

function _bootstrap_theme_get_comments($node) {
    $result = null;
    $query = db_select('comment', 'c')
        ->fields('c', array('cid'))
        ->condition('c.nid', $node->nid, '=')
        ->condition('c.status', COMMENT_PUBLISHED, '=')
    ;
    $result = $query->execute();

    return $result;
}

function _bootstrap_theme_comments_view($node) {
    $comments = _bootstrap_theme_get_comments($node);

    $output = '';
    $header = array('Avatar', 'Comments');

    $rows = array();
    $comment_cnt = 0;
    foreach ($comments as $comment) {
        $comment = comment_load($comment->cid);
        $row = array();
        $account = user_load($comment->uid);
        $row[] = array('data' => '<a href="'.url('user/'.$account->uid).'"><img src="'.bootstrap_theme_get_user_picture($account).'" alt="'.format_username($account).'" /></a>', 'class' => array('cell-comment-avatar'));

        $html  = '<div class="comment-info">';
            $html .= '<a href="'.url('user/'.$account->uid).'" class="comment-user">'.format_username($account).'</a>';
            $html .= '<span class="comment-date">'.format_interval(time() - $comment->created).' ago</span>';
            $html .= '<div class="comment-body"">'.$comment->comment_body[LANGUAGE_NONE][0]['value'].'</comment>';
        $html .= '</div>';
        $row[] = array('data' => $html, 'class' => 'cell-comment-data');

        $rows[] = $row;

        $comment_cnt++;
    }

    $output .= '<div class="comment-list '.($comment_cnt == 0?'no-comment':'').'">';
    if ($comment_cnt == 0)
        $output .= 'No Comments';
    else {
        $output .= theme('table', array('header' => $header, 'rows' => $rows, 'attributes' => array('class' => array('data-table-comments')), 'sticky' => FALSE));
        $output .= theme('pager');
    }
    $output .= '</div>';

    $form = drupal_get_form('bootstrap_theme_create_comment_form', $node);

    $output .= '<div id="comment_form_container">';
    if (user_is_logged_in()) {
        $output .= ''.drupal_render($form).'</div>';
        $output .= '<a id="write-comments" href="#" class="element-invisible">Write Comments</a>';
    } else {
        $output .= '<div class="comment-not-logged-in">';
            $output .= '<a href="'.url('user/login').'?destination='.urlencode(url('node/'.$node->nid)).'">log in to submit a comment</a>';
            $output .= '<div class="clear"></div>';
        $output .= '</div>';
    }
    $output .= '</div>';

    return $output;
}

/*******************************************************************************
 *
 * Create Comment Form
 *
 *******************************************************************************/
function bootstrap_theme_create_comment_form($form, &$form_state) {

    $node = $form_state['build_info']['args'][0];

    $form['body'] = array(
        '#title' => t('Body'),
        '#type' => 'textarea',
        '#attributes' => array('data-placeholder' => 'Comment')
    );
    $form['nid'] = array(
        '#type' => 'hidden',
        '#attributes' => array(),
        '#value' => $node->nid
    );

    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => 'Submit Comment',
        '#attributes' => array('data-ignore-theme' => true),
        '#ajax' => array(
            'event' => 'click',
            'callback' => 'ajax_bootstrap_theme_create_comment_form_submit',
            'wrapper' => 'comment_form_container',
            'method' => 'replace',
            'effect' => 'fade'
        )
    );

    $form['#theme'] = 'bootstrap_theme_create_comment_form';

    return $form;
}

function ajax_bootstrap_theme_create_comment_form_submit($form, $form_state) {
    return '<div id="comment_form_container">'.drupal_render(drupal_rebuild_form('bootstrap_theme_create_comment_form', $form_state)).'</div>';
}

function theme_bootstrap_theme_create_comment_form($variables) {
    global $user;

    $form = $variables['form'];

    $output  = '<div class="avatar">';
        $output .= '<a href="'.url('user/'.$user->uid).'"><img src="'.bootstrap_theme_get_user_picture($user).'" alt="'.format_username($user).'" /></a>';
    $output .= '</div>';

    $output .= '<div class="comment-create-form">';
        $output .= drupal_render($form['body']);
        $output .= drupal_render($form['submit']);
        $output .= drupal_render($form['nid']);
        $output .= drupal_render_children($form);
    $output .= '</div>';

    $output .= '<div class="clear"></div>';

    return $output;
}
function bootstrap_theme_create_comment_form_submit(&$form, &$form_state) {
    global $user;

    $comment_form = $form_state['values'];

    $comment = (object) array(
        'nid' => $comment_form['nid'],
        'uid' => $user->uid,
        'mail' => '',
        'is_anonymous' => FALSE,
        'status' => COMMENT_PUBLISHED,
        'language' => LANGUAGE_NONE,
        'comment_body' => array(
            LANGUAGE_NONE => array(
                0 => array (
                    'value' => $comment_form['body'],
                    'format' => 'filtered_html'
                )
            )
        ),
    );
    comment_submit($comment);
    comment_save($comment);

    drupal_set_message('Successfully created new comment.');
    $form_state['no_redirect'] = TRUE;
    $form_state['rebuild'] = TRUE;
    $form_state['programmed'] = FALSE;
}

function bootstrap_theme_create_comment_form_validate($form, &$form_state) {
    if (!user_is_logged_in())
        form_set_error('', 'You need to log in.');
    $comment = $form_state['values'];
    $collection['body'] = trim($comment['body']);
    if (empty($comment['body']) || $comment['body'] == 'Comment') {
        form_set_error('body', 'Body field is required.');
    }
}

/**
 * Share with a member form
 */
function _bootstrap_theme_share_with_member_form($form, $form_state) {

    if ($form == null && $form_state == null) {
        $form = drupal_get_form('bootstrap_theme_share_with_member_form');
        $form_output = drupal_render($form);
    } else {
        $form_output = drupal_render(drupal_rebuild_form('bootstrap_theme_share_with_member_form', $form_state));
    }

    return '<div id="share_with_member_form_container">'.$form_output.'</div>';
}

function bootstrap_theme_share_with_member_form($form, &$form_state) {
    require_once dirname(__FILE__) . '/../../privatemsg/privatemsg.pages.inc';

    $page_title = drupal_get_title();
    $output = privatemsg_new($form, $form_state);
    $output['body']['#default_value'] = url('node/'.arg(1), array('absolute' => true));

    drupal_set_title($page_title);

    return $output;
}

function ajax_bootstrap_theme_share_with_member_form_submit($form, $form_state) {
    return _bootstrap_theme_share_with_member_form($form, $form_state);
}

function bootstrap_theme_share_with_member_form_submit(&$form, &$form_state) {
    privatemsg_new_submit($form, $form_state);
}

function bootstrap_theme_share_with_member_form_validate(&$form, &$form_state) {
    privatemsg_new_validate($form, $form_state);
}

