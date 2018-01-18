<?php

$chuck_script = get_chuck_random_quote();

$vbUser = 'xx';
$vbToken = 'xx';

/* Custom meta boxes for Voice Speedy Projects
--------------------------------------------- */

$key_voiceproject = "voiceproject";

$meta_boxes_voiceproject = array(

	"project_script" => array(
	    "name" => false,
	    "title" => "Script for the Voice Speedy Project"),
	"project_id" => array(
	    "name" => false,
	    "title" => "Project ID"),
);

// 
function create_meta_box_voiceproject() {
   global $key_voiceproject;
   
   $screens = array( 'voiceproject');

   foreach ( $screens as $screen ) {
       add_meta_box( 'voice-project-meta-boxes', 'Speedy Project details', 'show_meta_box_voiceproject', $screen, 'normal', 'high' );
   }
}

// 
function show_meta_box_voiceproject() {
	
	global $post, $meta_boxes_voiceproject, $key_voiceproject, $chuck_script;

?>

	<table>
	
		<?php

		wp_nonce_field( plugin_basename( __FILE__ ), $key_voiceproject . '_wpnonce', false, true );

		$data_voiceproject = get_post_meta($post->ID, $key_voiceproject, true);
		
		$meta_box_script = $meta_boxes_voiceproject['project_script'];
		$meta_box_id = $meta_boxes_voiceproject['project_id'];

		?>

    	<tr>
    		<td>
    			<label for="<?php echo $meta_box_script[ 'name' ]; ?>"><?php echo $meta_box_script[ 'title' ]; ?>:</label>
    		</td>
			<td>
				<?php
						if( isset( $data_voiceproject['project_script'])){ ?>

							<span>

							<?php echo $data_voiceproject['project_script'][ 'name' ]; ?>

							</span>

						<?php }else{ ?>

							<textarea name="<?php echo $meta_box_script[ 'name' ]; ?>" id="" cols="30" rows="10"><?php echo $chuck_script; ?></textarea>
							
						<?php }
					?>
			</td>
    	</tr>

		<tr>
			<td>
				<label for="<?php echo $meta_box_id[ 'name' ]; ?>"><?php echo $meta_box_id[ 'title' ]; ?>:</label>
			</td>
			<td>
				<span>
					<?php
						if( isset( $data_voiceproject['project_id'] )){
							echo $data_voiceproject['project_id']['name'];
						}
					?>
				</span>
			</td>
		</tr>

	</table>

<?php

}

//* 
function save_project_data($id, $script){

	global $post, $key_voiceproject, $meta_boxes_voiceproject;

	$meta_boxes_voiceproject['project_script']['name'] = $script;
	$meta_boxes_voiceproject['project_id']['name'] = $id;

	update_post_meta($post->ID, $key_voiceproject, $meta_boxes_voiceproject);

}

//* Create the Speedy Project

function create_speedy_project($title, $script){

	global $vbUser, vbToken;

	$voicebunnyUser = $vbUser;
	$voicebunnyToken = $vbToken;
	$url_api = 'https://api.voicebunny.com/projects/addSpeedy';

	$postVars = array(
		'title' => $title,
		'script' => $script,
		'test' => 1
	);

	$vars = http_build_query($postVars);

	$opts = array(
		CURLOPT_URL => $url_api,
		CURLOPT_RETURNTRANSFER => TRUE,
		CURLOPT_INFILESIZE => -1,
		CURLOPT_TIMEOUT => 60,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_POST => TRUE,
		CURLOPT_POSTFIELDS => $vars,
		CURLOPT_USERPWD => $voicebunnyUser . ':' . $voicebunnyToken,
	);
	$curl = curl_init();
	curl_setopt_array($curl, $opts);
	$response = curl_exec($curl);
	$response = json_decode($response, true);
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);

	$project_id = $response['project']['id'];
	$project_script = $response['project']['script']['part001'];

	save_project_data($project_id, $project_script);

}

//* Send speedy project request creation
function send_speedy_project_request(){

	global $post, $chuck_script;

	$title = $post->post_title;

	create_speedy_project($title, $chuck_script);

}

add_action( 'admin_menu', 'create_meta_box_voiceproject' );
add_action( 'publish_voiceproject', 'send_speedy_project_request');
	
?>