<?php

/**
 *
 * @link              http://wpchurch.team
 * @since             1.0.0
 * @package           WP_Church_Center_External_Links
 *
 * @wordpress-plugin
 * Plugin Name:       WP Church Center: Planning Center Online Giving
 * Plugin URI:        http://wpchurch.center/addons
 * Description:       Adds a 'Planning Center Online' Giving card which allows churches to have giving initiated through their 'center'
 * Version:           1.0.0
 * Author:            Jordesign, WP Church Team
 * Author URI:        http://wpchurch.team/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       WP_Church_Center_PCO_Giving
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}



/******* Add 'External Link' as an option  in the 'Card Type' field ******/
function wpcc_load_pco_giving_card( $field ) {
             
    $field['choices'][ 'pco_giving' ] = 'PCO Giving';
    return $field;   
}
add_filter('acf/load_field/name=wpcc_card_type', 'wpcc_load_pco_giving_card');

/******* Add Field for Church ID ******/
add_action( 'acf/init', 'wpcc_load_pco_giving_fields',20 );

function wpcc_load_pco_giving_fields() {
acf_add_local_field( array (
  'key' => 'field_59fa684896h82',
  'label' => 'Your PCO subdomain',
  '_name' => 'pco_giving_subdomain',
  'name' => 'pco_giving_subdomain',
  'type' => 'text',
  'value' => NULL,
  'instructions' => 'Enter your unique ChurchCenter subdomain, which your Organization Administrator can find in your PCO Accounts.',
  'required' => 1,
  'wrapper' => array (
    'width' => '',
    'class' => '',
    'id' => '',
  ),
  'parent' => 'acf_card-content',
  'conditional_logic' => array (
          'status' => 1,
          'rules' => array (
            array (
              'field' => 'field_5994ca00ccd17',
              'operator' => '==',
              'value' => 'pco_giving',
            ),
          ),
          'allorany' => 'all',
        ),
  'ui' => 1,
  'ajax' => 0,
  'return_format' => 'value',
  'placeholder' => '',
) );


}


/******* Include reference to PCO Giving Javascript ******/
add_action('wp_print_scripts', 'wpcc_pco_giving_script', 100);

function wpcc_pco_giving_script (){

	if ( is_post_type_archive('card') || is_page_template('center_home.php') ){

		wp_enqueue_script( 'wpcc-pco-giving', 'https://js.churchcenter.com/modal/v1', array( 'jquery' ) );

	}
}


/******* Filter the card link to trigger the popup ******/
function wpcc_pco_giving_link($card_link) {

  if(get_field('wpcc_card_type', get_the_ID()) === 'pco_giving') {
	
	 $card_link = 'https://' . get_field('pco_giving_subdomain', get_the_ID() ) . '.churchcenteronline.com/giving?open-in-church-center-modal=true';
  }
 
	return $card_link;
}
add_filter('wpcc_card_link', 'wpcc_pco_giving_link', 10, 1);
