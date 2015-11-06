<?php

require_once dirname(__FILE__) . '/bootstrap_theme.api.php';

function bootstrap_theme_dashboard_page($action = 'create-contribute', $uid = null) {
	global $user;
    //bootstrap_theme_set_page_title_class('');
    bootstrap_theme_set_page_title_block('<div class="dashboard-search-form"></div>');
	
	$output = '
                <ul class="dashboard-tabs">
                    <li class="'.($action=='create-contribute'?'active':'').'"><a href="'.url('dashboard/create-contribute').'">Contribute</a></li>
                    <!--<li><a href="#">Create</a></li>-->
                    <li class="'.($action=='contributions'?'active':'').'"><a href="'.url('dashboard/contributions').'">Contributions</a></li>
                    <li class="'.($action=='collections'?'active':'').'"><a href="'.url('dashboard/collections').'">My Collections</a></li>
					<li class="'.($action=='all_collections'?'active':'').'"><a href="'.url('dashboard/all_collections').'">All Collections</a></li>';
	
	if(!in_array('administrator',$user->roles))
	{
		$output .= '<li class="'.($action=='badges'?'active':'').'"><a href="'.url('dashboard/badges').'">My Badges</a></li>';
	}

    if (bootstrap_theme_is_editor())
        $output .= '
                    <li class="'.($action=='contributions-to-approve'?'active':'').'"><a href="'.url('dashboard/contributions-to-approve').'">Contributions to Approve</a></li>
                    <li class="'.($action=='contributors-to-approve'?'active':'').'"><a href="'.url('dashboard/contributors-to-approve').'">Contributors to Approve</a></li>
                    ';
    $output .= '
                </ul>';

    switch ($action) {
        case 'create-contribute':
            $output .= '<div class="dashboard-tab-content">'._bootstrap_theme_dashboard_create_contribute_form_tab_contents().'</div>';
            break;
        case 'create':
            $output .= '<div class="dashboard-tab-content">'._bootstrap_theme_dashboard_create_contribute_form_tab_contents().'</div>';
            break;
        case 'contributions':
            $output .= '<div class="dashboard-tab-content">'._bootstrap_theme_dashboard_contributions_form_tab_contents().'</div>';
            break;
        case 'collections':
            $output .= '<div class="dashboard-tab-content">'._bootstrap_theme_dashboard_my_collections_tab_contents().'</div>';
            break;
		case 'badges':
            $output .= '<div class="dashboard-tab-content">'._bootstrap_theme_dashboard_my_badges_tab_contents().'</div>';
            break;
		case 'all_collections':
            $output .= '<div class="dashboard-tab-content">'._bootstrap_theme_dashboard_all_collections_tab_contents().'</div>';
            break;
        /*case 'groups':
            $output .= '<div class="dashboard-tab-content">'._bootstrap_theme_dashboard_create_contribute_form_tab_contents().'</div>';
            break;
        case 'connect':
            $output .= '<div class="dashboard-tab-content">'._bootstrap_theme_dashboard_create_contribute_form_tab_contents().'</div>';
            break;
        case 'calendar':
            $output .= '<div class="dashboard-tab-content">'._bootstrap_theme_dashboard_create_contribute_form_tab_contents().'</div>';
            break;*/
        case 'become-contributor':
            if (!bootstrap_theme_is_contributor())
                $output .= '<div class="dashboard-tab-content">'._bootstrap_theme_done_to_be_contributor($uid).'</div>';
            else
                $output .= '<div class="dashboard-tab-content">'._bootstrap_theme_dashboard_create_contribute_form_tab_contents().'</div>';
            break;
        case 'contributions-to-approve':
            if (!bootstrap_theme_is_editor()) {
                drupal_access_denied();
                exit;
            }
            $output .= '<div class="dashboard-tab-content">'._bootstrap_theme_dashboard_contributions_to_approve_contents().'</div>';
            break;
        case 'contributors-to-approve':
            if (!bootstrap_theme_is_editor()) {
                drupal_access_denied();
                exit;
            }
            $output .= '<div class="dashboard-tab-content">'._bootstrap_theme_dashboard_contributors_to_approve_contents().'</div>';
            break;
        default:
            $output .= '<div class="dashboard-tab-content">'._bootstrap_theme_dashboard_create_contribute_form_tab_contents().'</div>';
            break;
    }

    $output .= '';
    return $output;
}


/*******************************************************************************
 *
 * Create Contribute Form - Tab Contents
 *
 *******************************************************************************/
function _bootstrap_theme_dashboard_create_contribute_form_tab_contents() {
    global $user;
    if (bootstrap_theme_is_contributor()) {
        $form = drupal_get_form('bootstrap_theme_dashboard_contribute_create_form');
        $output = '
            <div class="dashboard-create-contribute-form">
                <div class="tab-content-title">
                    <h2>Contribute</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed mattis felis vel accumsan sodales. Ut a est turpis. Morbi non condimentum quam, ut scelerisque lorem. Sed sed ligula nec lectus venenatis auctor. Sed nulla lectus, auctor non adipiscing eu, molestie in massa. Donec vel augue quis sapien semper cursus. Aenean convallis metus eget tincidunt sollicitudin. Integer urna ante, lacinia eget placerat quis, eleifend in lectus. Aliquam vehicula rhoncus dictum. Aenean convallis metus eget tincidunt sollicitudin.</p>
                </div>
                <div id="dashboard-create-contributor">
                    '.drupal_render($form).'
                </div>
            </div>
        ';
    } else {
        //$form = drupal_get_form('bootstrap_theme_dashboard_request_to_be_contributor_form');
        $output = '
            <div class="dashboard-create-contribute-form">
                <div class="tab-co ntent-title">
                    <h2>Request to Become a Contributor</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed mattis felis vel accumsan sodales. Ut a est turpis. Morbi non condimentum quam, ut scelerisque lorem. Sed sed ligula nec lectus venenatis auctor. Sed nulla lectus, auctor non adipiscing eu, molestie in massa. Donec vel augue quis sapien semper cursus. Aenean convallis metus eget tincidunt sollicitudin. Integer urna ante, lacinia eget placerat quis, eleifend in lectus. Aliquam vehicula rhoncus dictum. Aenean convallis metus eget tincidunt sollicitudin.</p>
                    <div id="dashboard-request-contributor">
                        <a href="'.url('user/'.$user->uid.'/edit').'" class="button">Get Started</a>
                    </div>
                </div>
            </div>
        ';
    }

    return $output;
}

function bootstrap_theme_dashboard_request_to_be_contributor_form($form, &$form_state) {
    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => 'Get Started',
        '#attributes' => array('data-ignore-theme' => true),
        '#ajax' => array(
            'event' => 'click',
            'callback' => 'ajax_bootstrap_theme_dashboard_request_to_be_contributor_form_submit',
            'wrapper' => 'dashboard-request-contributor',
            'method' => 'replace',
            'effect' => 'fade',
        )
    );

    return $form;
}
function ajax_bootstrap_theme_dashboard_request_to_be_contributor_form_submit($form, $form_state) {
    return '<div id="dashboard-request-contributor">'.drupal_render(drupal_rebuild_form('bootstrap_theme_dashboard_request_to_be_contributor_form', $form_state)).'</div>';
}
function theme_bootstrap_theme_dashboard_request_to_be_contributor_form($variables) {
    $form = $variables['form'];
    $output  = drupal_render($form['submit']);
    $output .= drupal_render_children($form);
    return $output;
}
function bootstrap_theme_dashboard_request_to_be_contributor_form_submit(&$form, &$form_state) {

}

function bootstrap_theme_dashboard_request_to_be_contributor($account) {
    require_once dirname(__FILE__) . '/../../privatemsg/privatemsg.module';

    $to = variable_get('site_mail');
    //drupal_mail('bootstrap_theme', 'to_become_contributor', $to, LANGUAGE_NONE, array('subject' => 'Request to Become a Contributor', 'message' => url('dashboard/become-contributor', array('absolute' => TRUE))), $account->email);
    //drupal_set_message('Thanks for your request to become contributor.');

    global $user;

    $editor = bootstrap_theme_get_editor();
    //$to = variable_get('site_mail');
    //drupal_mail('bootstrap_theme', 'to_become_contributor', $to, LANGUAGE_NONE, array('subject' => 'Request to Become a Contributor', 'message' => url('dashboard/become-contributor/'.$user->uid, array('absolute' => TRUE))), $user->email);
    //drupal_set_message('Thanks for your request.');
    
    $account->field_is_pending_contributor[LANGUAGE_NONE][0]['value'] = 1;
    user_save($account);

    bootstrap_theme_send_message($user, $editor, 'Request to Become a Contributor', url('user/'.$user->uid, array('absolute' => true)));
    drupal_set_message('Thanks for your request to become contributor.');

    /*$recipient = $editor;
    $recipient->recipient = $editor->uid;
    $recipient->type = 'user';

    $new_message = new stdClass();
    $new_message->author = $user;
    $new_message->timestamp = time();
    $new_message->recipient = $editor->name;
    $new_message->recipients = array(
        'user_'.$editor->uid => $recipient
    );

    $new_message->subject = 'Request to Become a Contributor';
    $new_message->body = url('user/'.$user->uid, array('absolute' => true));

    _privatemsg_send($new_message);*/

    $form_state['no_redirect'] = TRUE;
    $form_state['rebuild'] = TRUE;
    $form_state['programmed'] = FALSE;

    echo drupal_json_encode(array('url' =>  url('user/'.$account->uid.'/edit')));
    exit;
}

function bootstrap_theme_dashboard_contribute_create_form() {
    global $user;
    $form = array();

    $form['#attributes'] = array('enctype' => 'multipart/form-data');
    
	$form['title'] = array(
        '#type' => 'textfield',
        '#title' => 'Title',
        '#required' => TRUE,
        '#attributes' => array()
    );
	
	$form['cnob_photo'] = array(
        '#type' => 'managed_file',
        //'#name' => 'files[cno_associated_materials]',
        '#title' => 'Photo',
        //'#required' => TRUE,
        '#attributes' => array(),
        '#upload_location' => 'public://cno_materials',
        //'#progress_indicator' => 'bar',
        '#upload_validators' => array(
            //'file_validate_size' => array(500 * 1024 * 1024),
            'file_validate_extensions' => array('png gif jpg jpeg')
        )
    );
	
	$form['body'] = array(
        '#type' => 'textarea',
        '#title' => 'Description',
        '#attributes' => array()
    );
	$form['cno_type'] = array(
        '#type' => 'select',
        '#title' => 'User Type',
        '#attributes' => array(),
        '#required' => TRUE,
        '#options' => array('Learning Object' => 'Learning Object', 'Other' => 'Other')
    );
	$form['cno_category'] = array(
        '#type' => 'select',
        '#title' => 'Category',
        '#attributes' => array(),
        '#required' => TRUE,
        '#options' => bootstrap_theme_get_taxonomy_vocabulary_category_options(TAXONOMY_VOCABULARY_ID_CATEGORY)
    );
	$form['cno_learning_object_type'] = array(
        '#type' => 'select',
        '#title' => 'Learning Object Type',
        '#attributes' => array(),
        '#required' => TRUE,
        '#options' => bootstrap_theme_get_taxonomy_vocabulary_category_options(TAXONOMY_VOCABULARY_ID_LEARNING_OBJECT_TYPE),
    );
	
	$form['cno_lobj_res_audio'] = array(
        '#type' => 'managed_file',
		'#title' => 'Learning Object Resource - Audio',
        '#attributes' => array(),
		'#upload_location' => 'public://',
        //'#required' => TRUE,
		'#states' => array('visible' => array(':input[name="cno_learning_object_type"]' => array('value' => 73),),),
		'#upload_validators' => array(
            'file_validate_size' => array(500 * 1024 * 1024),
            'file_validate_extensions' => array('mp3 avi wmv')
        )
    );
	$form['cno_lobj_res_video'] = array(
        //'#type' => 'managed_file',
		'#type' => 'textfield',
		'#description' => '<br><i>You can enter vimeo OR youtube URL for example, https://vimeo.com/XXXXX<br>
OR<br>https://youtu.be/XXXXX</i>',
        '#title' => 'Video URL',
        '#attributes' => array(),
        //'#required' => TRUE,
		'#states' => array('visible' => array(':input[name="cno_learning_object_type"]' => array('value' => 74),),),
		/*'#upload_validators' => array(
            'file_validate_size' => array(500 * 1024 * 1024),
            'file_validate_extensions' => array('mov m4v mp4 mpeg')
        )*/
    );
	$form['cno_lobj_res_document'] = array(
        '#type' => 'managed_file',
        '#title' => 'Learning Object Resource - Document',
        '#attributes' => array(),
        //'#required' => TRUE,
		'#states' => array('visible' => array(':input[name="cno_learning_object_type"]' => array('value' => 75),),),
		'#upload_validators' => array(
            'file_validate_size' => array(500 * 1024 * 1024),
            'file_validate_extensions' => array('txt doc docx xls xlsx pdf ppt pptx pps ppsx')
        )
    );
	$form['cno_lobj_res_link'] = array(
        '#type' => 'textfield',
        '#title' => 'Learning Object Resource - Link',
        '#attributes' => array(),
        //'#required' => TRUE,
		'#states' => array('visible' => array(':input[name="cno_learning_object_type"]' => array('value' => 76),),),
    );
	
	$form['cno_area_of_study'] = array(
        '#type' => 'select',
        '#title' => 'Area of Study',
        '#attributes' => array(),
        '#required' => TRUE,
        '#options' => bootstrap_theme_get_taxonomy_vocabulary_category_options(TAXONOMY_VOCABULARY_ID_AREA_OF_STUDY)
    );
	$form['cno_grade_level'] = array(
        '#type' => 'select',
        '#title' => 'Grade Level',
        '#attributes' => array(),
        '#required' => TRUE,
        '#options' => bootstrap_theme_get_taxonomy_vocabulary_category_options(TAXONOMY_VOCABULARY_ID_GRADE_LEVEL)
    );
	$form['expiration_date'] = array(
        '#type' => 'date_popup',
		'#date_format' => 'm/d/Y',
        '#title' => 'Expiration Date',
        '#attributes' => array(),
        '#required' => FALSE
    );
	$form['cno_contributor'] = array(
        '#type' => 'hidden',
        '#title' => 'Contributor',
        '#attributes' => array(),
        '#value' => $user->uid
    );
	
	$coll_msg = '';
	if(count(bootstrap_theme_get_collection_options()) <= 1)
	{
		$coll_msg = '<br><a href="collections/redirect">Please create at least one collection before creating a contribution</a>';
	}
	
	$form['og_group_ref'] = array(
        '#type' => 'select',
        '#title' => 'Collections',
        '#attributes' => array(),
        '#required' => TRUE,
		'#description' => t($coll_msg),
        '#options' => bootstrap_theme_get_collection_options()
    );
	$form['cno_content_area'] = array(
        '#type' => 'select',
        '#title' => 'Content Area',
        '#attributes' => array(),
        '#required' => TRUE,
        '#options' => bootstrap_theme_get_taxonomy_vocabulary_category_options(TAXONOMY_VOCABULARY_ID_CONTENT_AREA)
    );
	/*$form['cno_relevant_standards'] = array(
        '#type' => 'select',
        '#title' => 'Relevant Standards',
        '#attributes' => array(),
        '#required' => TRUE,
        '#options' => bootstrap_theme_get_taxonomy_vocabulary_category_options(TAXONOMY_VOCABULARY_ID_RELEVANT_STANDARDS)
    );*/
	$form['cno_relevant_standards'] = array(
        '#type' => 'textfield',
        '#title' => 'Relevant Standards',
        '#required' => TRUE,
        '#attributes' => array(),
        '#autocomplete_path' => 'taxonomy/autocomplete/'.TAXONOMY_VOCABULARY_MACHINE_NAME_RELEVANT_STANDARDS,
	'#description' => '<br><i>You can enter multiple standards using comma(,) as separator.</i>',
    );
	$form['lesson_plan'] = array(
        '#type' => 'textarea',
        '#title' => 'Lesson Plan',
        '#attributes' => array()
    );
	$form['cno_associated_materials'] = array(
        '#type' => 'managed_file',
        //'#name' => 'files[cno_associated_materials]',
        '#title' => 'Associated Materials',
        //'#required' => TRUE,
        '#attributes' => array(),
        '#upload_location' => 'public://cno_materials',
        //'#progress_indicator' => 'bar',
        '#upload_validators' => array(
            'file_validate_size' => array(500 * 1024 * 1024),
            'file_validate_extensions' => array('txt pdf doc docx mp4 jpg png')
        )
    );
	$form['comments'] = array(
        '#type' => 'textarea',
        '#title' => 'Comments',
        '#attributes' => array()
    );
	$form['tags'] = array(
        '#type' => 'textfield',
        '#title' => 'Tags',
        '#required' => TRUE,
        '#attributes' => array(),
        '#autocomplete_path' => 'taxonomy/autocomplete/' . TAXONOMY_VOCABULARY_MACHINE_NAME_TAGS,
    );
	
	$nid = '';
	if(isset($form_state['node']->nid))
	{
		$nid = $form_state['node']->nid;
	}
	$query2 = db_select('field_data_field_cnob_assigned_badge', 'fcab');
	$query2 = $query2->condition('fcab.entity_id', $nid, '=');
	//$query2 = $query2->fields('fcab', array('field_cnob_assigned_badge_value'));
	$query2 = $query2->fields('fcab', array('field_cnob_assigned_badge_nid'));
	$result2 = $query2->execute();
	$badge_id = '';
	foreach($result2 as $data2)
	{
		//$badge_id = $data2->field_cnob_assigned_badge_value;
		$badge_id = $data2->field_cnob_assigned_badge_nid;
	}
	$form['cno_badge'] = array(
        '#type' => 'select',
        '#title' => 'Badge this content',
        '#attributes' => array(),
        '#required' => FALSE,
		'#default_value' => $badge_id,
        '#options' => bootstrap_theme_get_user_badges()
    );
	
	$userDetails = user_load($user->uid);
	$userRoles = $userDetails->roles;
	if(in_array('administrator',$userRoles) || in_array('editor',$userRoles))
	{
		$form['featured'] = array(
			'#type' => 'checkbox',
			'#title' => 'Is Featured?',
			'#required' => FALSE,
			'#attributes' => array()
		);
	}

    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => 'Submit for Approval',
        '#attributes' => array('data-ignore-theme' => true)
    );

    return $form;
}

function bootstrap_theme_dashboard_ajax_callback($form, $form_state) {
    return $form['cno_lobj_res_audio'];
}

function theme_bootstrap_theme_dashboard_contribute_create_form($variables) {
    $form = $variables['form'];

    $output  = drupal_render($form['title']);
    $output .= drupal_render($form['description']);
    $output .= drupal_render($form['category']);
    $output .= drupal_render($form['submit']);
    $output .= drupal_render_children($form);

    return $output;
}
function bootstrap_theme_dashboard_contribute_create_form_submit(&$form, &$form_state) {
    global $user;
	
	$contribution = new stdClass();
	$contribution->type = NODE_TYPE_CLAS_CONTRIBUTOR;
    $contribution->status = NODE_NOT_PUBLISHED;
    $contribution->language = LANGUAGE_NONE;
    $contribution->uid = $user->uid;
	
	$term = taxonomy_term_load($form_state['values']['cno_learning_object_type']);
	$termname = $term->name;
	
	if (!empty($form_state['values']['cno_associated_materials'])) {
        $file = file_load($form_state['values']['cno_associated_materials']);
    } else {
        $file = file_managed_file_save_upload($form['cno_associated_materials']);
    }
	
	if (!empty($form_state['values']['cnob_photo'])) {
        $photo = file_load($form_state['values']['cnob_photo']);
    } else {
        $photo = file_managed_file_save_upload($form['cnob_photo']);
    }
	
	if($termname == 'Audio')
	{
		if (!empty($form_state['values']['cno_lobj_res_audio'])) {
			$audio_file = file_load($form_state['values']['cno_lobj_res_audio']);
			
			//$fname_arr = explode("://",$audio_file->uri);
			//file_move($audio_file, "public://".$fname_arr[1], FILE_EXISTS_RENAME);
			
			$contribution->field_cnob_learn_obj_res_audio[$contribution->language][0] = array('fid' => $audio_file->fid,'display' => 1);
		} else {
			$audio_file = file_managed_file_save_upload($form['cno_lobj_res_audio']);
		}
	}
	/*if (!empty($form_state['values']['cno_lobj_res_video'])) {
        $video_file = file_load($form_state['values']['cno_lobj_res_video']);
		$contribution->field_cnob_learn_obj_res_video[$contribution->language][0] = array('fid' => $video_file->fid,'display' => 1);
    } else {
        $video_file = file_managed_file_save_upload($form['cno_lobj_res_video']);
    }*/
	if($termname == 'Document')
	{
		if (!empty($form_state['values']['cno_lobj_res_document'])) {
			$doc_file = file_load($form_state['values']['cno_lobj_res_document']);
			$contribution->field_cnob_learn_obj_res_doc[$contribution->language][0] = array('fid' => $doc_file->fid,'display' => 1);
		} else {
			$doc_file = file_managed_file_save_upload($form['cno_lobj_res_document']);
		}
	}
	if($termname == 'Link')
	{
		if (!empty($form_state['values']['cno_lobj_res_link'])) {
			$res_link = $form_state['values']['cno_lobj_res_link'];
		} else {
			$res_link = '';
		}
		$contribution->field_cnob_learn_obj_res_link[$contribution->language][0]['value'] = $res_link;
	}
	
	$contribution->title = $form_state['values']['title'];
    $contribution->body[$contribution->language][0]['value'] = $form_state['values']['body'];
    $contribution->field_cnob_user_type[$contribution->language][0]['value'] = $form_state['values']['cno_type'];
	$contribution->field_cnob_category[$contribution->language][0]['tid'] = $form_state['values']['cno_category'];
	$contribution->field_cnob_learning_object_type[$contribution->language][0]['tid'] = $form_state['values']['cno_learning_object_type'];
	if($termname == 'Video')
	{
		$contribution->field_cnob_learn_obj_res_video[$contribution->language][0]['video_url'] = $form_state['values']['cno_lobj_res_video'];
	}
	$contribution->field_cnob_area_of_study[$contribution->language][0]['tid'] = $form_state['values']['cno_area_of_study'];
	$contribution->field_cnob_grade_level[$contribution->language][0]['tid'] = $form_state['values']['cno_grade_level'];
	$contribution->field_cnob_expiration_date[$contribution->language][0]['value'] = date('Y-m-d',strtotime($form_state['values']['expiration_date']));
	$contribution->field_cnob_collections[$contribution->language][0]['nid'] = $form_state['values']['og_group_ref'];
	$contribution->og_group_ref[$contribution->language][0]['target_id'] = $form_state['values']['og_group_ref'];
	$contribution->field_cnob_content_area[$contribution->language][0] = array('tid' => $form_state['values']['cno_content_area'],);
	$contribution->group_content_access[$contribution->language][0]['value'] = OG_CONTENT_ACCESS_PRIVATE;
    $contribution->cnob_content_area[$contribution->language][0]['value'] = OG_CONTENT_ACCESS_PRIVATE;
	//$contribution->field_cnob_relevant_standards[$contribution->language][0]['tid'] = $form_state['values']['cno_relevant_standards'];
	$contribution->field_cnob_lesson_plan[$contribution->language][0]['value'] = $form_state['values']['lesson_plan'];
	$contribution->field_cnob_assigned_badge[$contribution->language][0]['nid'] = $form_state['values']['cno_badge'];
	
	if($file)
	{
		$contribution->field_cnob_associated_materials[$contribution->language][0] = array('fid' => $file->fid,'display' => 1);
	}
	if($photo)
	{
		$contribution->field_cnob_photo[$contribution->language][0] = array('fid' => $photo->fid,'display' => 1);
	}
	$contribution->field_cnob_comments[$contribution->language][0]['value'] = $form_state['values']['comments'];
	//////Tags////
		$tags = $form_state['values']['tags'];
		$typed_terms = drupal_explode_tags($tags);
		$tags_vocabulary = taxonomy_vocabulary_load(TAXONOMY_VOCABULARY_ID_TAGS);
		$value_tags = array();
		foreach ($typed_terms as $typed_term) {
			if ($possibilities = taxonomy_term_load_multiple(array(), array('name' => trim($typed_term), 'vid' => array(TAXONOMY_VOCABULARY_ID_TAGS)))) {
				$term = array_pop($possibilities);
			}
			else {
				$vocabulary = $tags_vocabulary;
				$term = array(
					'tid' => 'autocreate',
					'vid' => $vocabulary->vid,
					'name' => $typed_term,
					'vocabulary_machine_name' => $vocabulary->machine_name,
				);
			}
			$value_tags[] = (array)$term;
		}
		$contribution->field_cnob_tags[$contribution->language] = $value_tags;
	//////Tags////

	//////Relevant Standards////
		$relevant_standards = $form_state['values']['cno_relevant_standards'];
		$typed_terms = drupal_explode_tags($relevant_standards);
		$rs_vocabulary = taxonomy_vocabulary_load(TAXONOMY_VOCABULARY_ID_RELEVANT_STANDARDS);
		$value_rs = array();
		foreach ($typed_terms as $typed_term) {
			if ($possibilities = taxonomy_term_load_multiple(array(), array('name' => trim($typed_term), 'vid' => array(TAXONOMY_VOCABULARY_ID_TAGS)))) {
				$term = array_pop($possibilities);
			}
			else {
				$vocabulary = $rs_vocabulary;
				$term = array(
					'tid' => 'autocreate',
					'vid' => $vocabulary->vid,
					'name' => $typed_term,
					'vocabulary_machine_name' => $vocabulary->machine_name,
				);
			}
			$value_rs[] = (array)$term;
		}
		$contribution->field_cnob_relevant_standards[$contribution->language] = $value_rs;
	//////Relevant Standards////
	
	$userDetails = user_load($user->uid);
	$userRoles = $userDetails->roles;
	if(in_array('administrator',$userRoles) || in_array('editor',$userRoles))
	{
		$contribution->field_cnob_is_featured[$contribution->language][0]['value'] = $form_state['values']['featured'];
	}
    $contribution->field_cnob_contributor[$contribution->language][0]['uid'] = $form_state['values']['cno_contributor'];

    node_object_prepare($contribution);

    //node_submit($contribution);
    //bootstrap_theme_node_presave($contribution);
    node_save($contribution);

    drupal_set_message('Created new contribution successfully.');
}

function bootstrap_theme_file_save_upload($element) {
    $upload_name = $element['#parents'];
    if (empty($_FILES['files']['name'][$upload_name])) {
        return FALSE;
    }

    $destination = 'public://';
    if (isset($destination) && !file_prepare_directory($destination, FILE_CREATE_DIRECTORY)) {
        watchdog('file', 'The upload directory %directory for the file field !name could not be created or is not accessible. A newly uploaded file could not be saved in this directory as a consequence, and the upload was canceled.', array('%directory' => $destination, '!name' => $element['#field_name']));
        form_set_error($upload_name, t('The file could not be uploaded.'));
        return FALSE;
    }

    if (!$file = file_save_upload($upload_name, $element['#upload_validators'], $destination)) {
        watchdog('file', 'The file upload failed. %upload', array('%upload' => $upload_name));
        form_set_error($upload_name, t('The file in the !name field was unable to be uploaded.', array('!name' => $element['#title'])));
        return FALSE;
    }

    return $file;
}

/*******************************************************************************
 *
 * Contributions Tab Contents
 *
 *******************************************************************************/
function _bootstrap_theme_dashboard_contributions_form_tab_contents() {
    global $user;

    //$contributions = bootstrap_theme_get_contributions(NODE_PUBLISHED, $user);
    //$pending_contributions = bootstrap_theme_get_contributions(NODE_NOT_PUBLISHED, $user);
	$contributions = bootstrap_theme_get_contributions(NODE_PUBLISHED);
    $pending_contributions = bootstrap_theme_get_contributions(NODE_NOT_PUBLISHED);

    $output ='
        <div class="dashboard-contributions">
            <div class="tab-content-title">
                <h2>All Contributions</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed mattis felis vel accumsan sodales. Ut a est turpis. Morbi non condimentum quam, ut scelerisque lorem. Sed sed ligula nec lectus venenatis auctor. Sed nulla lectus, auctor non adipiscing eu, molestie in massa. Donec vel augue quis sapien semper cursus. Aenean convallis metus eget tincidunt sollicitudin. Integer urna ante, lacinia eget placerat quis, eleifend in lectus. Aliquam vehicula rhoncus dictum. Aenean convallis metus eget tincidunt sollicitudin.</p>
            </div>
            <div class="contribution-list">
                <div class="approved-contributions">
                    <h3>Approved Contributions</h3>';

    $cnt = 0;
    foreach ($contributions as $contribution) {
        $cnt++;
        $contribution = node_load($contribution->nid);
        if (!empty($contribution->og_group_ref))
            $collection = node_load($contribution->og_group_ref[$contribution->language][0]['target_id']);
        else
            $collection = null;

        if (!empty($contribution->field_cno_associated_materials))
            $contribution_material = $contribution->field_cno_associated_materials[$contribution->language][0];
        else
            $contribution_material = null;
        $output .= '
                    <div class="contribute">
                        <div class="ctb-title">'.(is_null($collection)?'':'<a href="'.url('collection/'.$collection->nid).'">'.$collection->title.': ').'<a href="'.url('node/'.$contribution->nid).'">'.$contribution->title.'</a></div>';
        if (!empty($contribution_material)) {
            $material_url = file_create_url($contribution_material['uri']);
            $output .= '<a href="'.$material_url.'" class="ctb-download" target="_blank">'.format_size($contribution_material['filesize']).'</a>';
        }
		
		$change_collection_form = drupal_get_form('bootstrap_theme_change_collection_form', $contribution);
		
		if($user->uid == $contribution->uid)
		{
			$output .= '<a href="'.url('node/'.$contribution->nid.'/edit').'" class="ctb-link">Edit</a>';
		}
		else
		{
			 $output .= '<a href="'.url('node/'.$contribution->nid).'" class="ctb-link">'.url('node/'.$contribution->nid, array('absolute' => TRUE)).'</a>';
		}
		
        $output .= ' <!--a href="'.url('node/'.$contribution->nid).'" class="ctb-link">'.url('node/'.$contribution->nid, array('absolute' => TRUE)).'</a-->
                        <div class="ctb-author">Author: <a href="'.url('user/'.$contribution->uid).'">'.format_username(user_load($contribution->uid)).'</a></div>
                        <div class="ctb-added">Added: <span>'.date('m/d/Y', $contribution->created).'</span></div>
                        <div class="ctb-modified">Modified: <span>'.date('m/d/Y', $contribution->changed).'</span></div>
                        <div class="ctb-desc">
                            <strong>Description:</strong>
                            <div>'.(!empty($contribution->body[$contribution->language])?$contribution->body[$contribution->language][0]['value']:'').'</div>
                        </div>
						
						<div id="collection_ids_for_contribution_'.$contribution->nid.'" class="collids_for_contri">'.drupal_render($change_collection_form).'</div>
						
                        <a href="'.url('node/'.$contribution->nid).'#write-comments'.'" class="ctb-add-comment">Comment</a>
                        <a href="'.url('node/'.$contribution->nid).'#comments'.'" class="ctb-see-comment">See Comments('.$contribution->comment_count.')</a>
                        <div class="ctb-tags">Tags: '.bootstrap_theme_render_html_tags($contribution->field_cnob_tags).'</div>
                    </div>';
    }

    if ($cnt == 0) {
        $output .= '<div class="empty-contribute">';
            $output .= 'No Contribution';
        $output .= '</div>';
    }

    $output .= '
                </div>
                <div class="pending-contributions">
                    <h3>Pending Contributions</h3>
    ';

    $cnt = 0;
    foreach ($pending_contributions as $contribution) {
        $cnt++;
        $contribution = node_load($contribution->nid);
        if (!empty($contribution->og_group_ref))
            $collection = node_load($contribution->og_group_ref[$contribution->language][0]['target_id']);
        else
            $collection = null;

        $output .= '
                    <div class="contribute">
                        <div class="ctb-title">'.(is_null($collection)?'':'<a href="'.url('collection/'.$collection->nid).'">'.$collection->title.': ').'<a href="'.url('node/'.$contribution->nid).'">'.$contribution->title.'</a></div>
                        <div class="ctb-desc">
                            '.substr($contribution->body[$contribution->language][0]['value'], 0, 100).'...
                        </div>
                        <div class="ctb-submitted">Submitted '.format_interval(time() - $contribution->created).' ago</div>
                    </div>
        ';
    }

    if ($cnt == 0) {
        $output .= '<div class="empty-contribute">';
            $output .= 'No Contribution';
        $output .= '</div>';
    }

    $output .= '
                </div>
                <div class="clear"></div>
            </div>
        </div>
    ';

    return $output;
}

/*******************************************************************************
 *
 * My Collections Tab Contents
 *
 *******************************************************************************/
function _bootstrap_theme_dashboard_my_collections_tab_contents($form = null, $form_state = null) {

    if ($form == null && $form_state == null) {
        $form = drupal_get_form('bootstrap_theme_dashboard_create_collection_form');
        $form_output = drupal_render($form);
    } else {
        $form_output = drupal_render(drupal_rebuild_form('bootstrap_theme_dashboard_create_collection_form', $form_state));
    }

    drupal_set_title('My Collections');
    bootstrap_theme_set_page_title_block('<div class="dashboard-search-form"></div>');

    $output = '
        <div id="dashboard-my-collections" class="dashboard-my-collections">
            <div class="dashboard-create-collection-form">
                <h4>Create a New Collection</h4>'
                .'<div id="dashboard_create_collection_form">'.$form_output.'</div>'.
                '
            </div>
            <div id="dashboard_a_collections">
            '._bootstrap_theme_collections_html().'
            </div>
         </div>
    ';

    return $output;
}

function _bootstrap_theme_dashboard_my_badges_tab_contents($form = null, $form_state = null) {

    /*if ($form == null && $form_state == null) {
        $form = drupal_get_form('bootstrap_theme_dashboard_create_collection_form');
        $form_output = drupal_render($form);
    } else {
        $form_output = drupal_render(drupal_rebuild_form('bootstrap_theme_dashboard_create_collection_form', $form_state));
    }*/

    drupal_set_title('My Badges');
    bootstrap_theme_set_page_title_block('<div class="dashboard-search-form"></div>');

    $output = '
        <div id="dashboard-my-badges" class="dashboard-my-badges">
            <div id="dashboard_a_badges">
            '._bootstrap_theme_badges_html().'
            </div>
         </div>
    ';

    return $output;
}

function _bootstrap_theme_dashboard_all_collections_tab_contents($form = null, $form_state = null) {

    if ($form == null && $form_state == null) {
        $form = drupal_get_form('bootstrap_theme_dashboard_create_collection_form');
        $form_output = drupal_render($form);
    } else {
        $form_output = drupal_render(drupal_rebuild_form('bootstrap_theme_dashboard_create_collection_form', $form_state));
    }

    drupal_set_title('All Collections');
    bootstrap_theme_set_page_title_block('<div class="dashboard-search-form"></div>');

    $output = '
        <div id="dashboard-my-collections" class="dashboard-my-collections">
            <!--div class="dashboard-create-collection-form">
                <h4>Create a New Collection</h4>'
                .'<div id="dashboard_create_collection_form">'.$form_output.'</div>'.
                '
            </div-->
            <div id="dashboard_a_collections">
            '._bootstrap_theme_all_collections_html().'
            </div>
         </div>
    ';

    return $output;
}

function bootstrap_theme_dashboard_create_collection_form($form, &$form_state) {

    $form['name'] = array(
        '#title' => t('Name'),
        '#type' => 'textfield',
        '#attributes' => array('data-placeholder' => 'Name')
    );
	
	$redirect = explode('/',$_GET['q']);
	if(isset($redirect[2]))
	{
		$form['redirect'] = array('#type' => 'hidden', '#value' => $redirect[2]);
		$form['submit'] = array(
				'#type' => 'submit',
				'#value' => 'Create',
				'#attributes' => array('data-ignore-theme' => true),
			);
	}
	else
	{
		$form['submit'] = array(
			'#type' => 'submit',
			'#value' => 'Create',
			'#attributes' => array('data-ignore-theme' => true),
			'#ajax' => array(
				'event' => 'click',
				'callback' => 'ajax_bootstrap_theme_dashboard_create_collection_form_submit',
				'wrapper' => 'dashboard-my-collections',
				'method' => 'replace',
				'effect' => 'fade'
			)
		);
	}
	
	return $form;
}
function ajax_bootstrap_theme_dashboard_create_collection_form_submit($form, $form_state) {
	
	return _bootstrap_theme_dashboard_my_collections_tab_contents($form, $form_state);
}
function theme_bootstrap_theme_dashboard_create_collection_form($variables) {
    $form = $variables['form'];
    $output  = drupal_render($form['name']);
    $output .= drupal_render($form['submit']);
    $output .= drupal_render_children($form);
    return $output;
}
function bootstrap_theme_dashboard_create_collection_form_submit(&$form, &$form_state) {
    global $user;

    $collection = new stdClass();

    $collection->title = $form_state['values']['name'];
    $collection->type = NODE_TYPE_CLAS_COLLECTION;
    $collection->status = NODE_PUBLISHED;
    $collection->language = LANGUAGE_NONE;
    $collection->uid = $user->uid;
    $collection->body[$collection->language][0]['value'] = '';
    $collection->group_group[$collection->language][0]['value'] = 1;

    //node_submit($collection);
    node_save($collection);
	
	if(isset($form_state['values']['redirect']) && $form_state['values']['redirect'] == 'redirect')
	{
		$form_state['rebuild'] = FALSE;
		$form_state['redirect'] = 'dashboard/create-contribute';
	}
	else
	{
		drupal_set_message('Successfully created new collection.');
		$form_state['no_redirect'] = TRUE;
		$form_state['rebuild'] = TRUE;
		$form_state['programmed'] = FALSE;
	}
}

function bootstrap_theme_dashboard_create_collection_form_validate($form, &$form_state) {
    $collection = $form_state['values'];
    $collection['name'] = trim($collection['name']);
    if (empty($collection['name']) || $collection['name'] == 'Name') {
        form_set_error('name', 'Name field is required.');
    }
}

function _bootstrap_theme_collections_html() {
    $header = array('Collection', 'Link to Collection', 'Settings');

    $rows = array();
    foreach (bootstrap_theme_get_collections() as $collection) {
        $row = array();
        $row[] = array('data' => $collection->title, 'class' => array('cell-collection-name'));
        $row[] = array('data' => '<a href="'.url('collection/'.$collection->nid, array('absolute' => TRUE)).'">'.url('collection/'.$collection->nid, array('absolute' => TRUE)).'</a>', 'class' => 'cell-collection-link');
        //$row[] = array('data' => '<a href="#">privacy</a><span>&nbsp;&nbsp;|&nbsp;&nbsp;</span><a href="#">delete collection</a>', 'class' => 'cell-collection-settings');

        $link = array(
			  '#type' => 'link',
			  '#title' => t('delete collection'),
			  '#href' => 'dashboard/collection/delete/' . $collection->nid,
			  '#ajax' => array(
			    'callback' => 'bootstrap_theme_delete_collection',
			    'wrapper' => 'dashboard_a_collections',
			    'method' => 'replace',
			    'effect' => 'fade',
			  ),
			);
		$edit_link = array(
			  '#type' => 'link',
			  '#title' => t('edit collection'),
			  '#href' => 'node/'.$collection->nid.'/edit',
			  /*'#ajax' => array(
			    'callback' => 'bootstrap_theme_delete_collection',
			    'wrapper' => 'dashboard_a_collections',
			    'method' => 'replace',
			    'effect' => 'fade',
			  ),*/
			);
        $row[] = array('data' => drupal_render($edit_link) .' | '. drupal_render($link));
        $rows[] = $row;
    }

    $output = theme('table', array('header' => $header, 'rows' => $rows, 'attributes' => array('class' => array('data-table'))));
    $output .= theme('pager');

    return $output;
}

function _bootstrap_theme_badges_html() {
    $header = array('Title', 'Badge', 'Settings');

    $rows = array();
    foreach (bootstrap_theme_get_badges() as $badge) {
	
		$query = db_select('file_managed', 'fm');
		$query = $query->fields('fm', array('filename','uri'));
		$query->leftJoin('field_data_field_badges_badge_image', 'fbbi', 'fbbi.field_badges_badge_image_fid=fm.fid');
		$query->condition('fbbi.entity_id', $badge->nid, '=');
		$result = $query->execute();
		foreach($result as $data) {
			$badge_uri = $data->uri;
			$badge_img = $data->filename;
		}
	
        $row = array();
        $row[] = array('data' => $badge->title, 'class' => array('cell-collection-name'));
		$row[] = array('data' => '<img src="'.image_style_url('badge_thumb', $badge_uri).'" alt="'.$badge_img.'"/>');
        //$row[] = array('data' => '<a href="'.url('collection/'.$badge->nid, array('absolute' => TRUE)).'">'.url('badge/'.$badge->nid, array('absolute' => TRUE)).'</a>', 'class' => 'cell-collection-link');
        //$row[] = array('data' => '<a href="#">privacy</a><span>&nbsp;&nbsp;|&nbsp;&nbsp;</span><a href="#">delete collection</a>', 'class' => 'cell-collection-settings');

        $link = array(
			  '#type' => 'link',
			  '#title' => t('delete badge'),
			  '#href' => 'dashboard/badge/delete/' . $badge->nid,
			  '#ajax' => array(
			    'callback' => 'bootstrap_theme_delete_badge',
			    'wrapper' => 'dashboard_a_badges',
			    'method' => 'replace',
			    'effect' => 'fade',
			  ),
			);
		$edit_link = array(
			  '#type' => 'link',
			  '#title' => t('edit badge'),
			  '#href' => 'node/'.$badge->nid.'/edit',
			  /*'#ajax' => array(
			    'callback' => 'bootstrap_theme_delete_collection',
			    'wrapper' => 'dashboard_a_collections',
			    'method' => 'replace',
			    'effect' => 'fade',
			  ),*/
			);
        $row[] = array('data' => drupal_render($edit_link) .' | '. drupal_render($link));
        $rows[] = $row;
    }

    $output = theme('table', array('header' => $header, 'rows' => $rows, 'attributes' => array('class' => array('data-table'))));
    $output .= theme('pager');

    return $output;
}

function _bootstrap_theme_all_collections_html() {
    $header = array('Collection', 'Link to Collection'/*, 'Settings'*/);

    $rows = array();
    foreach (bootstrap_theme_get_all_collections() as $collection) {
        $row = array();
        $row[] = array('data' => $collection->title, 'class' => array('cell-collection-name'));
        $row[] = array('data' => '<a href="'.url('collection/'.$collection->nid, array('absolute' => TRUE)).'">'.url('collection/'.$collection->nid, array('absolute' => TRUE)).'</a>', 'class' => 'cell-collection-link');
        //$row[] = array('data' => '<a href="#">privacy</a><span>&nbsp;&nbsp;|&nbsp;&nbsp;</span><a href="#">delete collection</a>', 'class' => 'cell-collection-settings');

        $link = array(
			  '#type' => 'link',
			  '#title' => t('delete collection'),
			  '#href' => 'dashboard/collection/delete/' . $collection->nid,
			  '#ajax' => array(
			    'callback' => 'bootstrap_theme_delete_collection',
			    'wrapper' => 'dashboard_a_collections',
			    'method' => 'replace',
			    'effect' => 'fade',
			  ),
			);
        //$row[] = array('data' => drupal_render($link));
        $rows[] = $row;
    }

    $output = theme('table', array('header' => $header, 'rows' => $rows, 'attributes' => array('class' => array('data-table'))));
    $output .= theme('pager');

    return $output;
}

function _bootstrap_theme_get_contributor_role() {
    foreach (user_roles() as $role_id => $role_name) {
        if ($role_name == USER_TYPE_CONTRIBUTOR)
            return array($role_id => $role_name);
    }
    return 0;
}


function _bootstrap_theme_done_to_be_contributor($uid) {
    $user = user_load($uid);
    $output = '
        <div class="tab-content-title">
            <h2>You are a contributor NOW!!!</h2>
        </div>
    ';

    $user->roles += _bootstrap_theme_get_contributor_role();
    user_save((object) array('uid' => $user->uid), (array) $user);

    return $output;
}

/**
 Page for Contributions to Approve
 */
function _bootstrap_theme_dashboard_contributions_to_approve_contents($form = null, $form_state = null) {
    if (empty($form) && empty($form_state)) {
        $form = drupal_get_form('bootstrap_theme_dashboard_contributions_to_approve_form');
        $form_output = drupal_render($form);
    } else {
        $form_output = drupal_render(drupal_rebuild_form('bootstrap_theme_dashboard_contributions_to_approve_form', $form_state));
    }

    return '<div id="bootstrap_theme_pending_contributions">'.$form_output.'</div>';
}

function bootstrap_theme_dashboard_contributions_to_approve_form($form, &$form_state) {
    global $base_url;
    global $user;

    $header = array(
        'contribution' => 'Title',
        'author' => 'Contributor',
        'action' => 'Action',
    );
    $options = array();
    foreach (bootstrap_theme_get_contributions(NODE_NOT_PUBLISHED) as $contribution) {
        $contribution = node_load($contribution->nid);
        $contributor = user_load($contribution->uid);

        $options[$contribution->nid] =array(
            'contribution' => '<div class="ctb-title"><a href="'.url('node/'.$contribution->nid).'">'.$contribution->title.'</a></div>',
            'author' => '<div class="ctb-author"><a href="'.url('user/'.$contributor->uid).'">'.format_username($contributor).'</a></div>',
            'action' => '<a href="#" class="ctb-action-publish">Publish</a>'
        );
    }

    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => 'Publish',
        '#attributes' => array('data-ignore-theme' => true),
        '#ajax' => array(
            'event' => 'click',
            'callback' => 'ajax_bootstrap_theme_dashboard_contributions_to_approve_form_submit',
            'wrapper' => 'bootstrap_theme_pending_contributions',
            'method' => 'replace',
            'effect' => 'fade',
        )
    );

    $form['table'] = array(
        '#type' => 'tableselect',
        '#header' => $header,
        '#options' => $options,
        '#empty' => t('No pending contributions found'),
        '#attributes' => array('class' => array('data-table', 'pending-contributions')),
    );

    return $form;
}

function ajax_bootstrap_theme_dashboard_contributions_to_approve_form_submit($form, $form_state) {
    return _bootstrap_theme_dashboard_contributions_to_approve_contents($form, $form_state);
}

function bootstrap_theme_dashboard_contributions_to_approve_form_submit(&$form, &$form_state) {
    $contribution_ids = $form_state['values']['table'];

    $cnt = 0;
    foreach ($contribution_ids as $contribution_id) {
        if (empty($contribution_id))
            continue;

        $contribution = node_load($contribution_id);
        $contribution->status = NODE_PUBLISHED;
        $contribution->group_content_access[$contribution->language][0]['value'] = OG_CONTENT_ACCESS_PRIVATE;

        //print_r($contribution);
        //node_submit($contribution);
        node_object_prepare($contribution);
        node_save($contribution);

        $cnt++;
    }

    drupal_set_message(format_plural($cnt, 'Published 1 contribution.', 'Published @count contributions.'));
}

function bootstrap_theme_dashboard_contributions_to_approve_form_validate($form, &$form_state) {
    $contribution_ids = $form_state['values']['table'];

    $is_selected = false;
    foreach ($contribution_ids as $contribution_id) {
        if (!empty($contribution_id))
            $is_selected = true;
    }

    if (!$is_selected)
        form_set_error('', t('No contributor selected.'));
}

function bootstrap_theme_delete_collection($collection_id)
{
	$node = node_load($collection_id);
	if(!empty($node))
	{
		node_delete($collection_id);
	}
	return '<div id="dashboard_a_collections">'._bootstrap_theme_collections_html().'</div>';
}

function bootstrap_theme_delete_badge($badge_id)
{
	global $user;
	$node = node_load($badge_id);
	if(!empty($node))
	{
		db_delete('field_data_field_assign_badge_to_users')
		  ->condition('field_assign_badge_to_users_uid', $user->uid)
		  ->condition('entity_id', $badge_id)
		  ->execute();
	}
	return '<div id="dashboard_a_badges">'._bootstrap_theme_badges_html().'</div>';
}

/**
* To become contributor
*/
function _bootstrap_theme_dashboard_contributors_to_approve_contents($form = null, $form_state = null) {
    if (empty($form) && empty($form_state)) {
        $form = drupal_get_form('bootstrap_theme_dashboard_contributors_to_approve_form');
        $form_output = drupal_render($form);
    } else {
        $form_output = drupal_render(drupal_rebuild_form('bootstrap_theme_dashboard_contributors_to_approve_form', $form_state));
    }

    return '<div id="bootstrap_theme_pending_contributions">'.$form_output.'</div>';
}

function bootstrap_theme_dashboard_contributors_to_approve_form($form, &$form_state) {
    global $base_url;
    //global $user;

    $header = array(
        'name' => 'Name',
        'action' => 'Action',
    );
    $options = array();
    foreach (bootstrap_theme_get_contributors() as $user) {
        $user = user_load($user->uid);
        
        $options[$user->uid] =array(
            'name' => '<div class="ctb-title"><a href="'.url('node/'.$user->uid).'">'.format_username($user).'</a></div>',            
            'action' => '<a href="#" class="ctb-action-publish">Publish</a>'
        );
    }

    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => 'Publish',
        '#attributes' => array('data-ignore-theme' => true),
        '#ajax' => array(
            'event' => 'click',
            'callback' => 'ajax_bootstrap_theme_dashboard_contributors_to_approve_form_submit',
            'wrapper' => 'bootstrap_theme_pending_contributions',
            'method' => 'replace',
            'effect' => 'fade',
        )
    );

    $form['table'] = array(
        '#type' => 'tableselect',
        '#header' => $header,
        '#options' => $options,
        '#empty' => t('No pending contributors found'),
        '#attributes' => array('class' => array('data-table', 'pending-contributions')),
    );

    return $form;
}

function ajax_bootstrap_theme_dashboard_contributors_to_approve_form_submit($form, $form_state) {
    return _bootstrap_theme_dashboard_contributors_to_approve_contents($form, $form_state);
}

function bootstrap_theme_dashboard_contributors_to_approve_form_submit(&$form, &$form_state) {
    $contributor_ids = $form_state['values']['table'];

    $cnt = 0;
    foreach ($contributor_ids as $uid) {
        if (empty($uid))
            continue;

        $user = user_load($uid);

    	$user->roles += _bootstrap_theme_get_contributor_role();
    	$user->field_is_pending_contributor[LANGUAGE_NONE][0]['value'] = 0;
    	
    	//user_save((object) array('uid' => $user->uid), (array) $user);
    	user_save($user);

        $cnt++;
    }

    drupal_set_message(format_plural($cnt, 'Approved 1 user.', 'Approved @count users.'));
}

function bootstrap_theme_dashboard_contributors_to_approve_form_validate($form, &$form_state) {
    $contributor_ids = $form_state['values']['table'];

    $is_selected = false;
    foreach ($contributor_ids as $uid) {
        if (!empty($uid))
            $is_selected = true;
    }

    if (!$is_selected)
        form_set_error('', t('No contributor selected.'));
}

function bootstrap_theme_change_collection_form($form, &$form_state) {

    $node = $form_state['build_info']['args'][0];

    $form['collection_id'] = array(
        '#type' => 'select',
        '#title' => 'Collection',
        '#attributes' => array(),
        '#required' => TRUE,
        '#default_value' => (!empty($node->og_group_ref[LANGUAGE_NONE])?$node->og_group_ref[LANGUAGE_NONE][0]['target_id']:''),
        '#options' => bootstrap_theme_get_collection_options(array('' => 'Add to a Collection'))
    );
    $form['nid'] = array(
        '#type' => 'hidden',
        '#attributes' => array(),
        '#value' => $node->nid
    );

    $form['submit'] = array(
        '#type' => 'submit',
        '#value' => 'Add to Collection',
        '#attributes' => array('data-ignore-theme' => true),
        '#ajax' => array(
            'event' => 'click',
            'callback' => 'ajax_bootstrap_theme_change_collection_form_submit',
            'wrapper' => 'collection_ids_for_contribution_'.$node->nid,
            'method' => 'replace',
            'effect' => 'fade'
        )
    );

    $form['#theme'] = 'bootstrap_theme_change_collection_form';

    return $form;
}

function ajax_bootstrap_theme_change_collection_form_submit($form, $form_state) {
    return '<div class="collids_for_contri" id="collection_ids_for_contribution_'.$form_state['values']['nid'].'">'.drupal_render(drupal_rebuild_form('bootstrap_theme_change_collection_form', $form_state)).'</div>';
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
	$contribution->field_cnob_collections[$contribution->language][0]['nid'] = $collection_form['collection_id'];
   	$contribution->og_group_ref[$contribution->language][0]['target_id'] = $collection_form['collection_id'];
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
