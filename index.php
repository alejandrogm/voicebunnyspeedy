<?php

/*
Plugin Name: VoiceBunny Speedy
Plugin URI: https://voicebunny.com/
Description: The easiest way to create a VoiceBunny Speedy project using WordPress.
Version: 1.0
Author: Alejandro González
*/


define ('VBS_PATH', dirname(__file__));

//* Register post types
require_once( VBS_PATH . '/include/custom-post-register.php' );

//* Custom meta boxes
require_once( VBS_PATH . '/include/custom-meta-boxes.php' );

//* Plugin dashboard
require_once( VBS_PATH . '/include/plugin-dashboard.php' );


//* Get Chuck Norris Radom Quote

function get_chuck_random_quote(){

	$url = 'http://api.icndb.com/jokes/random';

	$result = file_get_contents($url);

	$result = json_decode($result, true);

	// var_dump($result['joke']);

	return $result['value']['joke'];

}

//* Function to get the player

function get_voiceproject_url_player($project_id){

	$readId = $project_id;
	$url_api = 'https://api.voicebunny.com/reads/' . $readId;
	$opts = array(
		CURLOPT_URL => $url_api,
		CURLOPT_RETURNTRANSFER => TRUE,
		CURLOPT_INFILESIZE => -1,
		CURLOPT_TIMEOUT => 60,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPGET => TRUE,
	);
	$curl = curl_init();
	curl_setopt_array($curl, $opts);
	$response = curl_exec($curl);
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);
	// print_r($response);

	// print $response['reads']['urls']['part001']['default'];

}

// Single hook to embed the script

function show_speedy_project_content(){

	global $post;

	if (is_singular( $post_types = 'voiceproject' )){

		$data_voiceproject = get_post_meta($post->ID, 'voiceproject', true);
		$project_script = $data_voiceproject['project_script']['name'];
		
		$project_id = $data_voiceproject['project_id']['name'];

		$embed_url = get_voiceproject_url_player($project_id);

		echo '<p><strong>Project Script</strong>: ' .  $project_script . '</p>';
		echo '<p><strong>Project ID</strong>: ' . $project_id . '</p>';

		echo '<audio controls>
  				<source src="$embed_url;" type="audio/ogg">
  				Your browser does not support the audio tag.
			</audio>';

		// echo '<audio controls>
  // 				<source src="https://voicebunny.s3.amazonaws.com/sandbox/low_test.mp3" type="audio/ogg">
  // 				Your browser does not support the audio tag.
		// 	</audio>';
		

	}

}

add_filter( 'the_content', 'show_speedy_project_content' );