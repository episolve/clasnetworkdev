<?php
define("USER_TYPE_CONTRIBUTOR", "contributor");
define("USER_TYPE_EDITOR", "editor");
define("NODE_TYPE_CLAS_COLLECTION", "clas_collection");
define("NODE_TYPE_CLAS_CONTRIBUTOR", "clas_network_object");
define("TAXONOMY_VOCABULARY_ID_TAGS", 1);
define("TAXONOMY_VOCABULARY_MACHINE_NAME_TAGS", 'field_tags');
define("TAXONOMY_VOCABULARY_MACHINE_NAME_RELEVANT_STANDARDS", 'field_cnob_relevant_standards');
define("TAXONOMY_VOCABULARY_ID_CATEGORY", 23); // machine name = category
define("TAXONOMY_VOCABULARY_ID_AREA_OF_STUDY", 24); // machine name = area_of_study
define("TAXONOMY_VOCABULARY_ID_GRADE_LEVEL", 25); // machine name = grade_level
define("TAXONOMY_VOCABULARY_ID_CONTENT_AREA", 26); // machine name = content_area
define("TAXONOMY_VOCABULARY_ID_RELEVANT_STANDARDS", 27); // machine name = relevant_standards
define("TAXONOMY_VOCABULARY_ID_LEARNING_OBJECT_TYPE", 28); // machine name = learning_object_type

function bootstrap_theme_get_taxonomy_vocabulary_category_options($taxonomy_vocabulary_id) {
    $options = array();
    //$options[] = '<none>';

    foreach (taxonomy_get_tree($taxonomy_vocabulary_id) as $category) {
        $options[$category->tid] = $category->name;
    }

    return $options;
}

function bootstrap_theme_get_collections() {
    global $user;
    $query = db_select('node', 'n');
    $query->fields('n');
    $result = $query
        ->condition('n.type', NODE_TYPE_CLAS_COLLECTION, '=')
        ->condition('n.status', NODE_PUBLISHED, '=')
        ->condition('n.uid', $user->uid, '=')
        ->execute();

    return $result;
}

function bootstrap_theme_get_badges() {
    global $user;
    $query = db_select('node', 'n');
	$query = $query->fields('n', array('nid','title'));
	$query->leftJoin('field_data_field_assign_badge_to_users', 'fab', 'fab.entity_id=n.nid');
	$query->condition('n.type', 'badges', '=');
	$query->condition('n.status', 1, '=');
	$query->condition('fab.field_assign_badge_to_users_uid', $user->uid, '=');
	$result = $query->orderBy('n.title', 'ASC')->execute();
    return $result;
}

function bootstrap_theme_get_all_collections() {
    global $user;
    $query = db_select('node', 'n');
    $query->fields('n');
    $result = $query
        ->condition('n.type', NODE_TYPE_CLAS_COLLECTION, '=')
        ->condition('n.status', NODE_PUBLISHED, '=')
        //->condition('n.uid', $user->uid, '=')
        ->execute();

    return $result;
}

function bootstrap_theme_get_collection_options($empty = array()) {
    $options = $empty;
    foreach (bootstrap_theme_get_collections() as $collection) {
        $options[$collection->nid] = $collection->title;
    }

    return $options;
}

function bootstrap_theme_get_contributions($status = NODE_PUBLISHED, $account = null, $collection_id = null) {
    global $user;
	
	/********************************************/
	/*$node_uid = '';
	if($account != null && !empty($collection_id))
	{
		$query1 = db_select('node', 'n');
		$query1->fields('n', array('uid'));
		$query1->condition('n.nid', $collection_id, '=');
		$result1 = $query1->execute();
		foreach($result1 as $row1)
		{
			$node_uid = $row1->uid;
		}
		
		if($node_uid != $account->uid)
		{
			$collection_id = -1;
		}
	}*/
	/********************************************/
	
    $query = db_select('node', 'n');
    if (!empty($collection_id)) {
		$query->join('field_data_field_cnob_collections"', 'ogm', 'ogm.entity_id=n.nid');
        $query->condition('ogm.field_cnob_collections_nid', $collection_id, '=');
		//$query->condition('ogm.entity_type', 'node', '=');
    }
	/*if($account != null)
	{
       $query = $query->condition('ogm.uid', $account->uid, '=');
	}*/
	
    //$query->leftJoin('comment', 'c', 'c.nid=n.nid');
    $query->fields('n', array('nid'));
    //$query->addExpression("COUNT(c.nid)", 'comment_count');
    $query->groupBy('n.nid');
    
    $result = $query
        ->condition('n.type', NODE_TYPE_CLAS_CONTRIBUTOR, '=')
        ->condition('n.status', $status, '=');

	//if ($account != null)
    //   $query = $query->condition('n.uid', $account->uid, '=');

    $result = $query->orderBy('n.created', 'DESC')->execute();

    return $result;
}

function bootstrap_theme_get_contributors() {
	$query = db_select('users', 'u');
	$query = $query->fields('u', array('uid'));
	$query->leftJoin('field_data_field_is_pending_contributor', 'fdfipc', 'fdfipc.entity_id=u.uid');
	$query = $query->condition('fdfipc.field_is_pending_contributor_value', 1, '=');
	
	$result = $query->execute();
	
	return $result;
}

function bootstrap_theme_is_contributor($account = null) {
    global $user;

    $account = ($account?$account:$user);

    foreach ($account->roles as $role) {
        if ($role == USER_TYPE_CONTRIBUTOR || $role == 'administrator')
            return TRUE;
    }
    return FALSE;
}

function bootstrap_theme_is_editor($account = null) {
    global $user;

    $account = ($account?$account:$user);

    foreach ($account->roles as $role) {
        if ($role == USER_TYPE_EDITOR || $role == 'administrator')
            return TRUE;
    }
    return FALSE;
}

function bootstrap_theme_get_user_picture($account = null) {
    global $user;
    $account = ($account?$account:$user);

    if (empty($account->picture)) {
        $default_picture = variable_get('user_picture_default', '');
    } else {
    	if (!empty($account->picture->uri))
        	$default_picture = $account->picture->uri;
    	else {
			$file = file_load($account->picture);
			$default_picture = $file->uri;
    	}
    }
    
    return file_create_url($default_picture);
}

function bootstrap_theme_set_page_title_class($_page_title_class) {
    global $page_title_class;
    $page_title_class = $_page_title_class;
}

function bootstrap_theme_set_page_small_title($_page_small_title) {
    global $page_small_title;
    $page_small_title = $_page_small_title;
}

function bootstrap_theme_set_page_title_block($_page_title_block) {
    global $page_title_block;
    $page_title_block = $_page_title_block;
}

function bootstrap_theme_set_page_title_prefix_block($_page_title_prefix_block) {
    global $page_title_prefix_block;
    $page_title_prefix_block = $_page_title_prefix_block;
}

function bootstrap_theme_set_page_content_class($_page_content_class) {
    global $page_content_class;
    $page_content_class = $_page_content_class;
}

function bootstrap_theme_render_html_tags($tags) {
    $output  = '';

    if (!empty($tags)) {
        foreach ($tags[LANGUAGE_NONE] as $tag) {
            $term = taxonomy_term_load($tag['tid']);
            if (!empty($output))
                $output .= ', ';
                
            $output .= '<a href="'.base_path().'taxonomy/term/'.$tag['tid'].'">'.$term->name.'</a>';
        }
    }

    return $output;
}

function bootstrap_theme_get_editor() {
    $query = db_select('users', 'u');
    $query->innerJoin('users_roles', 'ur', 'u.uid=ur.uid');
    $query->innerJoin('role', 'r', 'r.rid=ur.rid');
    $query = $query->condition('r.name', USER_TYPE_EDITOR, '=');
    $query = $query->fields('u', array('uid'));
    $query = $query->distinct();
    $result = $query->execute();

    $editor_id = null;
    foreach ($result as $user) {
        $editor_id = $user->uid;
    }

    return user_load($editor_id);
}

function bootstrap_theme_send_message($sender, $recipient, $subject, $body) {
    require_once dirname(__FILE__) . '/../../privatemsg/privatemsg.module';
    $recipient->recipient = $recipient->uid;
    $recipient->type = 'user';

    $new_message = new stdClass();
    $new_message->author = $sender;
    $new_message->timestamp = time();
    $new_message->recipient = $recipient->name;
    $new_message->recipients = array(
        'user_'.$recipient->uid => $recipient
    );

    $new_message->subject = $subject;
    $new_message->body = $body;
    $new_message->has_tokens = '';
    $new_message->format = 'filtered_html';

    _privatemsg_send($new_message);
}

function bootstrap_theme_display_user_location($account) {
    $address = '';

    if (!empty($account->field_location[LANGUAGE_NONE])) {

        $location = $account->field_location[LANGUAGE_NONE][0];

        if (!empty($location['street'])) {
            $address .= ' '  . $location['street'];
        }

        if (!empty($location['additional'])) {
            $address .= ' '  . $location['additional'];
        }

        if (!empty($location['city'])) {
            $address .= (empty($address)?'':', ')  . $location['city'];
        }

        if (!empty($location['province'])) {
            $address .= (empty($address)?'':', ')  . $location['province_name'];
        }

        if (!empty($location['postal_code'])) {
            $address .= (empty($address)?'':' ')  . $location['postal_code'];
        }

        if (!empty($location['country'])) {
            $address .= (empty($address)?'':', ')  . $location['country_name'];
        }
    }

    return $address;
}

function bootstrap_theme_get_taxonomy_vocabularies() {
	$vocabulary = taxonomy_get_vocabularies();
	return $vocabulary;
}

function bootstrap_theme_get_featured_contributions($status = NODE_PUBLISHED) {
    global $user;

    $query = db_select('node', 'n');
	$query = $query->fields('n', array('nid'));
    $query->leftJoin('field_data_field_cnob_is_featured', 'fdff', 'fdff.entity_id=n.nid');
    
    $result = $query
        ->condition('n.type', NODE_TYPE_CLAS_CONTRIBUTOR, '=')
        ->condition('n.status', $status, '=')
        ->condition('fdff.field_cnob_is_featured_value', 1);

    $result = $query->orderBy('n.created', 'DESC')->execute();

    return $result;
}

function bootstrap_theme_get_user_badges()
{
	global $user;
	$options = array();
	$options[0] = '-- Select Badge --';
	$query = db_select('node', 'n');
	$query = $query->fields('n', array('nid','title'));
	$query->leftJoin('field_data_field_assign_badge_to_users', 'fab', 'fab.entity_id=n.nid');
	$query->condition('n.type', 'badges', '=');
	$query->condition('n.status', 1, '=');
	if(!in_array('administrator',$user->roles))
	{
		$query->condition('fab.field_assign_badge_to_users_uid', $user->uid, '=');
	}
	$result = $query->orderBy('n.title', 'ASC')->execute();
	foreach($result as $data) {
		$options[$data->nid] = $data->title;
    }
    return $options;
}
