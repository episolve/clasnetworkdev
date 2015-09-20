<?php

require_once dirname(__FILE__) . '/bootstrap_theme.api.php';

function bootstrap_theme_category_page($vocab_name = '') {

	$output = '';
    bootstrap_theme_set_page_title_class('');
    //bootstrap_theme_set_page_title_block('<div class="dashboard-search-form"></div>');

	$output .= '<div class="category-page">'._bootstrap_theme_category_contents($vocab_name).'</div>';

    return $output;
}

/*******************************************************************************
 *
 * Category Contents
 *
 *******************************************************************************/
function _bootstrap_theme_category_contents($vocab_name) {
    global $user;

    //$contents = bootstrap_theme_get_category_contents(NODE_PUBLISHED, $user);

    $output = '';
    
    $myvoc = taxonomy_vocabulary_machine_name_load($vocab_name);
	$tree = taxonomy_get_tree($myvoc->vid);

	$depth = 0;
        $output .= '<div>Vocabulary terms under <a href="'.base_path().'category/'.$vocab_name.'" class="category-title">'.$myvoc->name.'</a>. Click on term you want to search content for.</div>
				<div style="float:right;"><a href="'.base_path().'" class="btn btn-primary form-submit">Back</a></div><br>';
	$output .= '<ul>';
	foreach ($tree as $term) {
	  if ($term->depth > $depth) {
	    $output .= '<ul>';
	    $depth = $term->depth;
	  }
	  if ($term->depth < $depth) {
	    $output .= '</ul>';
	    $depth = $term->depth;
	  }
	  //$output .= '<li>' . l($term->name, 'taxonomy/term/' . $term->tid) . '</li>';
          $output .= '<li>' . l($term->name, 'taxonomy/term/' . $term->tid) . "<br>";
	  $output .= '<div>'.$term->description.'</div></li>';
	}
	$output .= '</ul>';	

    return $output;
}


?>
