<?php

/*
Plugin Name: SINM Nice Tab
Description: This is an awesome wordpress tab plugin for Wordpress where you install plugin, after installation you will get a menu in your admin panel, add new tab and enjoy your awesome tab.
Author: Md. Abdullah
Author URI: http://wordcorepress.com/plugins/sinm-nice-tab
Plugins URI: http://wordcorepress.com/
Version: 1.0
*/

function main_sinm_awesome_jquery_from_wordpress(){

    wp_enqueue_script('jquery');
}

add_action('init','main_sinm_awesome_jquery_from_wordpress');


function sinm_awesome_tab_main_files() {
    wp_enqueue_script( 'sinm_awesome_tab-js', plugins_url( '/js/jquery.easytabs.min.js', __FILE__ ), array('jquery'), 1.0, false);
    wp_enqueue_script( 'sinm_awesome_hashchange-js', plugins_url( '/js/jquery.hashchange.min.js', __FILE__ ), array('jquery'), 1.0, false);
    wp_enqueue_script( 'sinm_awesome-tab-active-js', plugins_url( '/js/awesome-tab-active.js', __FILE__ ), array('jquery'), 1.0, false);

    wp_enqueue_style( 'sinm_awesome_tab_css', plugins_url( '/css/awesome.css', __FILE__ ));
}
add_action('init','sinm_awesome_tab_main_files');




//custom post and taxonomy
add_action( 'init', 'sinm_awesome_custom_post' );
function sinm_awesome_custom_post() {
	register_post_type( 'awesome-tab-items',
		array(
			'labels' => array(
				'name' => __( 'Awesome Tabs' ),
				'singular_name' => __( 'Awesome Tab' ),
				'add_new_item' => __( 'Add New Awesome Tab' )
			),
			'public' => true,
			'supports' => array('title', 'editor'),
			'has_archive' => true,
            'menu_icon' => 'dashicons-migrate',
			'rewrite' => array('slug' => 'tab-item'),
		)
	);
}
function sinm_awesome_post_taxonomy() {
	register_taxonomy(
		'sinm_cat',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
		'awesome-tab-items',                  //post type name
		array(
			'hierarchical'          => true,
			'label'                         => ' Awesome Tab Category',  //Display name
			'query_var'             => true,
			'show_admin_column'             => true,
			'rewrite'                       => array(
				'slug'                  => 'sinm-tab-category', // This controls the base slug that will display before each term
				'with_front'    => true // Don't display the category base before
				)
			)
	);
}
add_action( 'init', 'sinm_awesome_post_taxonomy');


function sinm_tab_title_shortcode($atts){
	extract( shortcode_atts( array(
		'category' => ''
		
		
      
	), $atts) );
	
    $q = new WP_Query(
        array('posts_per_page' => -1, 'post_type' =>'awesome-tab-items', 'sinm_cat' => $category)
        );		
		
		
	$list = '<ul class="sinm_titles">';
	while($q->have_posts()) : $q->the_post();
		$list .= '
        
        <li><a  href="#sinm-tab-single-'.get_the_ID().'">'.do_shortcode(get_the_title()).'</a></li>
        
		';        
	endwhile;
	$list.= '</ul>';
	wp_reset_query();
	return $list;
}
add_shortcode('sinmt', 'sinm_tab_title_shortcode');

function sinm_wordpress_tab_content_shortcode($atts){
	extract( shortcode_atts( array(
		'category' => ''
	), $atts) );
	
    $q = new WP_Query(
        array('posts_per_page' => -1, 'post_type' =>'awesome-tab-items', 'sinm_cat' => $category)
        );		
		
		
	$list = '';
	while($q->have_posts()) : $q->the_post();
		$list .= '
        
        <div id="sinm-tab-single-'.get_the_ID().'" class="sinm_inner_tab">
            <h2>'.do_shortcode(get_the_title()).'</h2>
            '.do_shortcode(get_the_content()).'
       </div>
        
        
		';        
	endwhile;
	$list.= '';
	wp_reset_query();
	return $list;
}
add_shortcode('sinmc', 'sinm_wordpress_tab_content_shortcode');

//combind shortcode
function sinm_wordpress_combind_tab($atts, $content = null){
    
    extract( shortcode_atts( array(
		'category' => '',
		'id' => 'sinmtab'
	), $atts) );
    
    return'
    
    <script>
        jQuery(document).ready(function(){
    jQuery("#awesome'.$id.'").easytabs();
    
}); 
    </script>
    
    
    <div id="awesome'.$id.'" class="sinm_hit_tab">'.do_shortcode('[sinmt catecory="'.$category.'"][sinmc catecory="'.$category.'"]').'</div>';
}
add_shortcode('swtp','sinm_wordpress_combind_tab');









?>