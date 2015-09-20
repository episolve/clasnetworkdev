<?php

require_once dirname(__FILE__) . '/bootstrap_theme.api.php';

function bootstrap_theme_home_page($banner = FALSE) {
    global $base_url;

    $output = '';

    $output .= '<div class="user-login-form-container">';
    if (!$banner && !user_is_logged_in()) {
        /****************************************************
         * User Registration Form
         */
        $user_register_form = drupal_get_form('user_register_form');
            $output .= '<div class="block-inner">';
                $output .= '<div class="block-description">';
                    $output .= '<p class="title">Educational Innovation through<br />Practice and Evaluation</p>';
                    $output .= '<p>Find peer reviewed resources with CLAS Contribute, create and equalate materials to improve the quality of education.</p>';
                $output .= '</div>';
                $output .= '<div class="block-user-register-form">';
                    $output .= drupal_render($user_register_form);
                    $output .= '<p>By clicking Create My Account you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</p>';
                $output .= '</div>';
            $output .= '</div>';
    } else {
        $output .= '<div id="bootstrap_theme_banner_carousel" class="carousel slide">';
            $output .= '<div class="carousel-indicators-container">';
                $output .= '<ol class="carousel-indicators">';
                    $output .= '<li data-target="#bootstrap_theme_banner_carousel" data-slide-to="0" class="active"></li>';
                    $output .= '<li data-target="#bootstrap_theme_banner_carousel" data-slide-to="1"></li>';
                    $output .= '<li data-target="#bootstrap_theme_banner_carousel" data-slide-to="2"></li>';
                $output .= '</ol>';
            $output .= '</div>';
            $output .= '<!-- Carousel items -->';
                $output .= '<div class="carousel-inner">';
                    $output .= '<div class="active item"><h2>Register for the 2013 Convention</h2><p>Registration for the 2014 convention in July begins today.</p></div>';
                    $output .= '<div class="item"><h2>Register for the 2013 Convention</h2><p>Registration for the 2014 convention in July begins today.</p></div>';
                    $output .= '<div class="item"><h2>Register for the 2013 Convention</h2><p>Registration for the 2014 convention in July begins today.</p></div>';
            $output .= '</div>';
            $output .= '<!-- Carousel nav -->';
            $output .= '<a class="carousel-control left" href="#bootstrap_theme_banner_carousel" data-slide="prev">&lsaquo;</a>';
            $output .= '<a class="carousel-control right" href="#bootstrap_theme_banner_carousel" data-slide="next">&rsaquo;</a>';
        $output .= '</div>';
    }
    $output .= '</div>';

    /****************************************************
     * Build the future
     */
    $output .= '<div class="build-future-container">';
        $output .= '<div class="block-inner">';
            $output .= '<div class="title"><strong>Build the future</strong><br />Improve the world by improving education with CLAS.</div>';
            $output .= '<ul>';
                $output .= '<li>';
                    $output .= '<a href="'.$base_url.'/alphabetical-view/" class="thumb"><img src="'.$base_url.'/sites/default/files/images/browse_our_collection.jpg" /></a>';
                    $output .= '<a href="'.$base_url.'/alphabetical-view/" class="title">Browse our collection</a>';
                    $output .= '<p>Find peer-reviewed materials for your<br />specific subject and audience with our<br />advanced search filter.</p>';
                $output .= '</li>';
                $output .= '<li>';
                    $output .= '<a href="'.$base_url.'/become-a-contributor/" class="thumb"><img src="'.$base_url.'/sites/default/files/images/become_a_contributor.jpg" /></a>';
                    $output .= '<a href="'.$base_url.'/become-a-contributor/" class="title">Become a Contributor</a>';
                    $output .= '<p>Have a resource you want to share?<br />Be recognized for your contributions to<br />the quality of education.</p>';
                $output .= '</li>';
                $output .= '<li>';
                    $output .= '<a href="'.$base_url.'/connect-with-community/" class="thumb"><img src="'.$base_url.'/sites/default/files/images/connect_with_community.jpg" /></a>';
                    $output .= '<a href="'.$base_url.'/connect-with-community/" class="title">Connect with the Community</a>';
                    $output .= '<p>Connect and collaborate with others<br />within your discipline. Join events and<br />receive the latest news.</p>';
                $output .= '</li>';
            $output .= '</ul>';
        $output .= '</div>';
    $output .= '</div>';

    /****************************************************
     * Categories
     */
    $output .= '<div class="categories-container">';
    $output .= '<div class="block-inner">';
        $output .= '<div class="title">Categories</div>';
            $output .= '<ul>';
            	$i = 0;
	            foreach (bootstrap_theme_get_taxonomy_vocabularies() as $key => $vocabulary) {
        			$i++;
		            $output .= '<li class="'.(($i % 2 == 0)? 'last' : '').'">';
		                $output .= '<img src="'.$base_url.'/sites/default/files/images/front/default_future.png" />';
		                $output .= '<a href="'.base_path().'category/'.$vocabulary->machine_name.'" class="category-title">'.$vocabulary->name.'</a>';
		                $output .= '<div class="category-desc">'.$vocabulary->description.'</div>';
		            $output .= '</li>';
				}

            $output .= '</ul>';
        $output .= '</div>';
    $output .= '</div>';

    /****************************************************
     * Featured Contributions
     */
    $output .= '<div class="featured-contributions-container">';
    $output .= '<div class="block-inner">';
        $output .= '<div class="title">Featured Contributions</div>';
        $output .= '<ul>';
        	$i = 0;
        	foreach (bootstrap_theme_get_featured_contributions(NODE_PUBLISHED) as $contribution) {
        		$i++;
        		$contribution = node_load($contribution->nid);
	            $output .= '<li class="'.(($i % 2 == 0)? 'last' : '').'">';
	                //$output .= '<img src="'.$base_url.'/sites/default/files/images/front/default_future.png" />';

if(isset($contribution->field_cnob_photo['und'][0]['filename']))
					{
						$output .= '<img src="'.$base_url.'/sites/default/files/'.$contribution->field_cnob_photo['und'][0]['filename'].'" width="100" height="100"/>';
					}
					else
					{
						$output .= '<img src="'.$base_url.'/sites/default/files/images/front/default_contributor.png"/>';
					}

	                $output .= '<a href="'.base_path().'node/'.$contribution->nid.'" class="category-title">'.$contribution->title.'</a>';
	                $output .= '<a href="'.base_path().'node/'.$contribution->og_group_ref[$contribution->language][0]['target_id'].'" class="category-subtitle">'.node_load($contribution->og_group_ref[$contribution->language][0]['target_id'])->title.'</a>';
	                $output .= '<div class="category-desc">'.(!empty($contribution->body[LANGUAGE_NONE])?$contribution->body[LANGUAGE_NONE][0]['value']:'').'</div>';
	            $output .= '</li>';
			}
        $output .= '</ul>';
        $output .= '</div>';
    $output .= '</div>';

    /****************************************************
     * Image Gallery
     */

    /*$output .= '<div class="front-sponsors-container">';
        $output .= '<div class="block-inner">';
            $output .= '<ul>';
                $output .= '<li><a href="#"><img src="'.$base_url.'/sites/default/files/images/front/default_future.png" /></a></li>';
                $output .= '<li><a href="#"><img src="'.$base_url.'/sites/default/files/images/front/default_future.png" /></a></li>';
                $output .= '<li><a href="#"><img src="'.$base_url.'/sites/default/files/images/front/default_future.png" /></a></li>';
                $output .= '<li><a href="#"><img src="'.$base_url.'/sites/default/files/images/front/default_future.png" /></a></li>';
                $output .= '<li class="last"><a href="#"><img src="'.$base_url.'/sites/default/files/images/front/default_future.png" /></a></li>';
            $output .= '</ul>';
        $output .= '</div>';
    $output .= '</div>';*/

    return $output;
}
