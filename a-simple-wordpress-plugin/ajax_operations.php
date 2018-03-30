<?php
add_action( 'wp_ajax_ny_get_country_list', 'NY_get_country_list_callback' );


function NY_get_country_list_callback(){
	header('Content-type: application/json');
	$responses =  wp_remote_retrieve_body(wp_remote_get(NYDA_REST_API_URI.$_POST['term'] ));
	$countries = json_decode($responses,true);
	$json = array();
	if($countries['status'] != '404'){
		foreach($countries as $country){
			$json[]['name'] = $country['name'];
			$json[]['value'] = $country['name'];
		}
	}
	echo json_encode($json);
	wp_die();
}
