<?php 
/*
Plugin Name: Conversational forms
Plugin URI: https://twitter.com/a_paul21
Description: Transform forms into chats. From an idea of Space10 https://github.com/space10-community/conversational-form/
Version: 1.0
Author: Arnaud PAUL
Text Domain: conversational-form
Domain Path: /lang
*/


// Defining current folder as a constant
define('CF_BASENAME', plugin_basename(__FILE__));
define('CF_DIR_URL', plugins_url('', CF_BASENAME));



// Defining DB version and table name as globals

$cf_db_version = '1.0';
$table_name = $wpdb->prefix . 'conversational_form';

global $cf_db_version;
global $table_name;

// Installing the plugin
function cf_install() {
	// ob_start();
	

	global $wpdb;
	global $cf_db_version;
	global $table_name;
	
	
	$charset_collate = $wpdb->get_charset_collate();


		$sql = "CREATE TABLE ".$table_name." (
			id int(9) primary key NOT NULL AUTO_INCREMENT,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			name varchar(255) NOT NULL,
			slug varchar(55) DEFAULT '' NOT NULL,
			redirect_link varchar(255) NULL,
			send_data_to varchar(255) NULL,
			confirmation_message varchar(255) NULL,
			mailto varchar(255) NULL,
			user_image varchar(255) NULL,
			robot_image varchar(255) NULL
		) ".$charset_collate.";";

		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		$sql = "CREATE TABLE ".$table_name."_inputs (
			id int(9) primary key NOT NULL AUTO_INCREMENT,
			form_id int(9) DEFAULT 0 NOT NULL,
			questions text NOT NULL,
			label text NOT NULL,
			name varchar(255) DEFAULT '' NOT NULL,
			type varchar(55) DEFAULT '' NOT NULL,
			pattern varchar(255) DEFAULT '' NOT NULL,
			errors varchar(255) DEFAULT '' NOT NULL,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			input_order int(9) DEFAULT 0 NOT NULL
		) ".$charset_collate.";";


		dbDelta( $sql );

	add_option( 'cf_db_version', $cf_db_version );
	cf_install_data();

	// ob_clean();
}

// register_activation_hook( __FILE__, 'cf_install' );

// Setting default datas for example form
function cf_install_data() {

	global $wpdb;
	global $table_name;
	
	// try {
		$wpdb->insert( 
			$table_name, 
			array( 
				'time' => 	current_time( 'mysql' ), 
				'name' => 	__('Example form','conversational-form'), 
				'slug'=>	__('example-form','conversational-form'),
			) 
		);
		$firstformid = $wpdb->insert_id;
		$wpdb->insert( 
			$table_name."_inputs", 
			array( 
				'form_id'=> $firstformid,
				'questions'=>'Bonjour, quel est votre prénom?|Bonjour, je suis le robot du Social Food Menu. Quel est votre prénom?' ,
				'name' => 'first_name',
				'type'=> 'text', 
				'errors'=> 'Erreur de saisie',
				'time' => current_time( 'mysql' ), 
				'input_order' => 0,
			) 
		);
		$wpdb->insert( 
			$table_name."_inputs", 
			array( 
				'form_id'=> $firstformid,
				'questions'=>'Enchanté {previous-answer}, j\'aurai besoin de votre nom maintenant.' ,
				'name' => 'last_name',
				'type'=> 'text', 
				'errors'=> 'Erreur de saisie',
				'time' => current_time( 'mysql' ), 
				'input_order' => 1,
			) 
		);
		$wpdb->insert( 
			$table_name."_inputs", 
			array( 
				'form_id'=> $firstformid,
				'questions'=>'Merci. Pour quelle société travaillez vous ?' ,
				'name' => 'company',
				'type'=> 'text', 
				'errors'=> 'Erreur de saisie',
				'time' => current_time( 'mysql' ), 
				'input_order' => 2,
			) 
		);
		$wpdb->insert( 
			$table_name."_inputs", 
			array( 
				'form_id'=> $firstformid,
				'questions'=>'Et quel est votre poste au sein de {previous-answer}' ,
				'label' => 'Responsable marketing|Responsable des ventes|Chef de projet',
				'name' => 'poste',
				'type'=> 'select', 
				'errors'=> 'Erreur de saisie',
				'time' => current_time( 'mysql' ), 
				'input_order' => 3,
			) 
		);

		$wpdb->insert( 
			$table_name."_inputs", 
			array( 
				'form_id'=> $firstformid,
				'questions'=>'Un téléphone pour vous joindre ?' ,
				'name' => 'phone',
				'type'=> 'tel', 
				'pattern' => '^(?:0|\(?\+33\)?\s?|0033\s?)[1-79](?:[\.\-\s]?\d\d){4}$' ,
				'errors'=> 'Erreur de saisie',
				'time' => current_time( 'mysql' ), 
				'input_order' => 4,
			) 
		);
		$wpdb->insert( 
			$table_name."_inputs", 
			array( 
				'form_id'=> $firstformid,
				'questions'=>'Dites moi en plus sur vous.' ,
				'name' => 'tellUsMore',
				'type'=> 'textarea', 
				'errors'=> 'Erreur de saisie',
				'time' => current_time( 'mysql' ), 
				'input_order' => 5,
			) 
		);
	// } catch (Exception $e) {
	// 	print_r($e);
	// }
}

// register_activation_hook( __FILE__, 'cf_install_data' );

// // // Checking for updates of db version
// function cf_update_db_check() {
//     global $cf_db_version;
//     if ( get_site_option( 'cf_db_version' ) != $cf_db_version ) {
//         cf_install();
//     }
// }
// add_action( 'plugins_loaded', 'cf_update_db_check' );


// Loading front end scripts
function cf_load_scripts() {    
    wp_register_script( 'cf', CF_DIR_URL. '/js/conversational-form.js' , dirname(__FILE__),'0.1' );
    wp_enqueue_script('cf');
    wp_register_script( 'cf-main', CF_DIR_URL. '/js/main.js' , dirname(__FILE__),'1.0.0' );
    wp_enqueue_script('cf-main');
}

// Loading admin scripts
function cf_load_admin_scripts() {    
    wp_enqueue_script('jquery');
	wp_register_script( 'jquery-ui-sortableOnly', CF_DIR_URL. '/js/jquery-ui-sortableOnly.min.js' , array('jquery'));
    wp_enqueue_script('jquery-ui-sortableOnly');
    wp_register_script( 'cf-admin-js', CF_DIR_URL. '/js/admin.js' , array('jquery'),'1.0.4');
    wp_enqueue_script('cf-admin-js');
    
	wp_register_style('cf-admin-style', CF_DIR_URL. '/css/admin.css' , dirname(__FILE__),'1.1.9' );
    wp_enqueue_style('cf-admin-style');


    wp_localize_script('cf-admin-js','cf_datas',array(
		    	'input_labels'=> array(
		        	"input_type"=> __('Input type ','conversational-form'),
		        	"input_value"=> __('Input value ','conversational-form'),
		        	"input_select_placeholder"=> __('eg : Project manager | Web designer ','conversational-form'),
		        	"input_questions_label"=> __('Questions ','conversational-form'),
		        	"input_questions_placeholder"=> __('Your question here (place | between each question if multiples) ','conversational-form'),
		        	"input_name"=> __('Input name ','conversational-form'),
		        	"input_eg"=> __('eg:','conversational-form'),
		        	"input_validation_pattern"=> __('Validation pattern ','conversational-form'),
		        	"input_error_label"=> __('Errors ','conversational-form'),
		        	"input_error_placeholder"=> __('Your error message here ( place | between each message if multiple','conversational-form'),
		        	"input_register"=> __('Register','conversational-form')
		         )
				
		    ));
}

// Loading translations
function cf_load_translations(){
	load_plugin_textdomain( 'conversational-form', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}


// Setting up menu
function cf_setup_menu(){
        add_menu_page( 
        	'Conversational form page',
        	'Conversational form',
        	'manage_options',
        	'conversational-form',
        	'cf_admin_init' );
}
add_action('admin_menu', 'cf_setup_menu');

// Init of plugin admin
function cf_admin_init(){
	global $wpdb;
    global $table_name;


   	global $cf_db_version;
   	
   	if ( get_site_option( 'cf_db_version' ) != $cf_db_version ) {
    	cf_install();
   	}

    cf_load_translations();
	cf_load_admin_scripts();

	try {
		// Getting list of forms
	    $formList = $wpdb->get_results("SELECT * FROM ".$table_name);

	    // Getting first form id
	    $currentFormId= $formList[0]->id;

	    // Getting first form inputs
		$inputsList = $wpdb->get_results("SELECT * FROM ".$table_name."_inputs WHERE form_id=".$currentFormId." ORDER BY input_order asc");
	} catch (Exception $e) {
		print_r($e);
	}


	// Display admin
    include_once('views/admin/header.php');
    include_once('views/admin/inputs.php');
    include_once('views/admin/params.php');



	echo $html;

}



// AJAX CALLS
// Input => $_POST
// Output => JSON formatted array $res

function change_form(){
	global $wpdb;
	global $table_name;

	$id = $_POST['id'];
	if(isset($id) && !empty($id)){

		try {
			$form = $wpdb->get_results("SELECT * FROM ".$table_name." WHERE id=".$id);

			$inputsList = $wpdb->get_results("SELECT id,questions,label,name,type,pattern,errors,input_order FROM ".$table_name."_inputs WHERE form_id=".$id." ORDER BY input_order asc");

			$res = array(
				"message"=> "Done",
				"form_id"=>intval($id),
				"form_name"=>$form[0]->name,
				"form_slug"=>$form[0]->slug,
				"inputs"=>$inputsList,
				"params"=>array(
					"redirect_link"=>$form[0]->redirect_link,
					"send_data_to"=>$form[0]->send_data_to,
					"confirmation_message"=>$form[0]->confirmation_message, 
					"mailto"=>$form[0]->mailto, 
					"user_image"=>$form[0]->user_image,
					"robot_image"=>$form[0]->robot_image,
				)
			);
		} catch (Exception $e) {
			$res = array(
				"error_message"=> __('Error : ','conversational-form').$e
			);
		}
		
	}
	else{
		$res = array(
				"error_message"=> __('Error, please check the input format.','conversational-form')
			);
	}
	print_r(json_encode($res));

}

function add_form(){
	global $wpdb;
	global $table_name;

	$form_name = sanitize_text_field($_POST['newFormName']);
	if(isset($form_name) && !empty($form_name)){

		try {
			$slug = sanitize_title($form_name);

			$custom_logo_id = get_theme_mod( 'custom_logo' );
			$logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );

			$wpdb->insert( 
				$table_name, 
				array( 
					'time' => 	current_time( 'mysql' ), 
					'name' => 	$form_name, 
					'slug'=>	$slug,
					'user_image'=> 'https://raw.githubusercontent.com/space10-community/conversational-form/master/src/images/human.png',
					'robot_image'=> $logo[0]
				) 
			);
			$newFormId= $wpdb->insert_id;
			$res = array(
					"message"=> "Done",
					"form_id"=>intval($newFormId),
					"form_name"=>$form_name,
					"form_slug"=>$slug,
				);
		} catch (Exception $e) {
			$res = array(
				"error_message"=> __('Error : ','conversational-form').$e
			);
		}
		
	}
	else{
		$res = array(
				"error_message"=> __('Error, please check the input format.','conversational-form')
			);
	}

	print_r(json_encode($res));
}

function delete_form(){
	global $wpdb;
	global $table_name;
	$id = $_POST['formId'];
	if(isset($id) && !empty($id)){
		try {
			$wpdb->delete($table_name,array('id'=>intval($id)));
			$wpdb->delete($table_name.'_inputs',array('form_id'=>intval($id)));

			$res = array(
					"message"=> "Done",
					"form_id"=>intval($id),
				);
		} catch (Exception $e) {
			$res = array(
				"error_message"=> __('Error : ','conversational-form').$e
			);
		}
		
	}
	else{
		$res = array(
				"error_message"=> __('Error, please check form ID.','conversational-form')
			);
	}

	print_r(json_encode($res));
}




function add_input(){
	global $wpdb;
	global $table_name;

	$formId = $_POST['form_id'];
	$inputType = $_POST['input_type'];
	$order = $_POST['order'];
	if(isset($formId) && !empty($formId) && isset($inputType) && !empty($inputType) && isset($order) && !empty($order)){		
		try {
			$wpdb->insert( 
				$table_name.'_inputs', 
				array( 
					'form_id'=>$formId,
					'type'=>$inputType,
					'time' => 	current_time( 'mysql' ),
					'input_order'=>$order
				) 
			);
			$newInputId= $wpdb->insert_id;
			$res = array(
				"message"=> "Done",
				"input_id"=>intval($newInputId)
			);
		} catch (Exception $e) {
			$res = array(
				"error_message"=> __('Error : ','conversational-form').$e
			);
		}
		
	}
	else{
		$res = array(
				"error_message"=> __('Error, please check the input format.','conversational-form')
			);
	}
	print_r(json_encode($res));
}
function update_input(){
	global $wpdb;
	global $table_name;

	$id = $_POST['input_id'];
	if(isset($id) && !empty($id)){

		try {
			$label = (isset($_POST['label']) && !empty($_POST['label']))?$_POST['label']:'';
			$name = (isset($_POST['name']) && !empty($_POST['name']))?$_POST['name']:'';
			$pattern = (isset($_POST['pattern']) && !empty($_POST['pattern']))?$_POST['pattern']:'';
			$questions = (isset($_POST['questions']) && !empty($_POST['questions']))?$_POST['questions']:'';
			$errors = (isset($_POST['errors']) && !empty($_POST['errors']))?$_POST['errors']:'';
		
			$wpdb->update(
				$table_name.'_inputs',
				array(
					'questions'=> htmlspecialchars(stripslashes($questions),ENT_QUOTES),
					'label'=> htmlspecialchars(stripslashes($label),ENT_QUOTES),
					'name'=> htmlspecialchars(stripslashes($name),ENT_QUOTES),
					'pattern'=> $pattern,
					'errors'=> htmlspecialchars(stripslashes($errors),ENT_QUOTES)
					),
				array(
					'id'=>intval($id)
					)
				);
			
			$res = array(
				"message"=> "Done",
				"input_id"=>intval($id)
			);
		} catch (Exception $e) {
			$res = array(
				"error_message"=> __('Error : ','conversational-form').$e
			);
		}
		
		
	}
	else{
		$res = array(
				"error_message"=> __('Error, please check form ID.','conversational-form')
			);
	}
	print_r(json_encode($res));
}

function delete_input(){
	global $wpdb;
	global $table_name;

	$id = $_POST['input_id'];
	if(isset($id) && !empty($id)){
		try {
			$wpdb->delete($table_name.'_inputs',array('id'=>intval($id)));
		
			$res = array(
				"message"=> "Done",
				"input_id"=>intval($id),
			);
		} catch (Exception $e) {
			$res = array(
				"error_message"=> __('Error : ','conversational-form').$e
			);
		}
	}
	else{
		$res = array(
				"error_message"=> __('Error, please check form ID.','conversational-form')
			);
	}


	print_r(json_encode($res));
}


function update_input_order(){
	global $wpdb;
	global $table_name;

	$inputs = $_POST['inputs'];
	if(isset($inputs) && !empty($inputs)){
		try {
			foreach ($inputs as $input) {
				$wpdb->update(
					$table_name.'_inputs',
					array(
						'input_order'=> intval($input['order'])
					),
					array(
						'id'=>intval($input['inputId'])
					)
				);
			}
			$res = array(
				"message"=> "Done"
			);
		} catch (Exception $e) {
			$res = array(
				"error_message"=> __('Error : ','conversational-form').$e
			);
		}
		
		
	}
	else{
		$res = array(
			"error_message"=> __('Error, please check form ID.','conversational-form')
		);
	}


	print_r(json_encode($res));
}


function update_params(){
	global $wpdb;
	global $table_name;

	$redirect_link = $_POST['redirect_link'];
	$send_data_to = $_POST['send_data_to'];
	$confirmation_message = $_POST['confirmation_message'];
	$mailto = $_POST['mailto'];
	$user_image = $_POST['user_image'];
	$robot_image = $_POST['robot_image'];
	$formId = $_POST['formId'];

	if(isset($formId) && !empty($formId)){

		try {
			$wpdb->update(
				$table_name,
				array(
					'redirect_link'=> sanitize_text_field($redirect_link),
					'send_data_to'=> sanitize_text_field($send_data_to),
					'confirmation_message'=> sanitize_text_field($confirmation_message),
					'mailto'=> sanitize_text_field($mailto),
					'user_image'=> sanitize_text_field($user_image),
					'robot_image'=> sanitize_text_field($robot_image),
				),
				array(
					'id'=>intval($formId)
				)
			);

		
			$res = array(
				"message"=> "Done"
			);
		} catch (Exception $e) {
			$res = array(
				"error_message"=> __('Error : ','conversational-form').$e
			);
		}
		
	}
	else{
		$res = array(
			"error_message"=> __('Error, please check all parameter are present','conversational-form')
		);
	}


	print_r(json_encode($res));
}

add_action( 'wp_ajax_change_form', 'change_form' );
add_action( 'wp_ajax_add_form', 'add_form' );
add_action( 'wp_ajax_delete_form', 'delete_form' );
add_action( 'wp_ajax_update_params', 'update_params' );


add_action( 'wp_ajax_add_input', 'add_input' );
add_action( 'wp_ajax_update_input', 'update_input' );
add_action( 'wp_ajax_delete_input', 'delete_input' );
add_action( 'wp_ajax_update_input_order', 'update_input_order' );








// Sending email to a designed email address
function mailto(){
	global $wpdb;
	global $table_name;

	$datas= $_POST;
	
	$slug = $_POST['slug'];

	if(isset($slug) && !empty($slug)){
		try {		
			$form = $wpdb->get_results("SELECT * FROM ".$table_name." WHERE slug='".$slug."'");
			$form = $form[0];

			$subject = " ".$form->name." : Nouveau formulaire rempli";
			$to = $form->mailto;
			$message="	<html>
					    	<head>
					    		<title>Nouveau formulaire rempli</title>
					       		<meta charset='utf-8'>
					      	</head>
					      	<body>
					       		<p>".__('A new person filled the form ','conversational-form')." ".$form->name.". <br>".__('Here are his/her infos ','conversational-form').":</p>
					       		<table>
					        		<tr>
					         			<th>".__('Input name','conversational-form')."</th><th>".__('Value','conversational-form')."Valeur</th>
					        		</tr>";
		    foreach ($datas as $key => $value) {
		    	if($key !="action" && $key!="slug"){
		    		$message.= 		"<tr>
		    							<td>".$key."</td>
		    							<td>".$value."</td>
		    						</tr>";
		    	}
		    }
		    $message.="			</table>
					      	</body>
					    </html>";

			// Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
		    $headers  = 'MIME-Version: 1.0' . "\r\n";
		    $headers .= 'Content-type: text/html; charset=utf-8 \r\n';
		    // En-têtes additionnels
		    $headers .= 'From: '.get_bloginfo( "name" ).' <cf@'.get_bloginfo('url').'>\r\n';

			if(mail($to,$subject,$message,$headers)){
				$res = array(
					"message"=> "Done"
				);
			}
			else{
				$res = array(
					"error_message"=> __('Error sending email ','conversational-form')
				);
			}			
		} catch (Exception $e) {
			$res = array(
				"error_message"=> __('Error : ','conversational-form').$e
			);
		}
	}
	else{
		$res = array(
			"error_message"=> __('Error, please check all parameter are present','conversational-form')
		);
	}
	print_r(json_encode($res));
}

add_action( 'wp_ajax_mailto', 'mailto' );

add_action( 'wp_ajax_nopriv_mailto', 'mailto' );























function conversational_form_shortcode_func($atts){
		$atts = shortcode_atts(array(
			'id'=>'',
			'height'=>'100%',
			),$atts);


		cf_load_translations();

		$slug = $atts['id'];
		$blocHeight= $atts["height"];
		if(is_int($blocHeight)){
			$blocHeight = $blocHeight.'px';
		}
		$html='';
		global $wpdb;
		global $table_name;


		if($slug != ''){

			wp_enqueue_style('cf-css', plugin_dir_url( __FILE__ ).'css/style.css',array(),'1.2.1');

			

			$inputsList = $wpdb->get_results("SELECT * FROM ".$table_name."_inputs WHERE form_id = (SELECT id FROM ".$table_name." WHERE slug=\"".$slug."\" ) ORDER BY input_order asc");

			$form = $wpdb->get_results("SELECT * FROM ".$table_name." WHERE slug = \"".$slug."\"");
			$form = $form[0];

			include_once('views/front/form.php');
			


			if(isset($form->mailto) && !empty($form->mailto)){
				$mailto = "true";
			}
			else{
				$mailto = "";
			}

			if(isset($form->user_image) && !empty($form->user_image)){
				$user_image = $form->user_image;
			}
			else{
				$user_image = "https://raw.githubusercontent.com/space10-community/conversational-form/master/src/images/human.png";
			}
			if(isset($form->robot_image) && !empty($form->robot_image)){
				$robot_image = $form->robot_image;
			}
			else{
				$custom_logo_id = get_theme_mod( 'custom_logo' );
				$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
				$robot_image = $image[0];
			}
			cf_load_scripts();

			// Setting javascripts variables
			wp_localize_script('cf-main','cf_datas',array(
		    	'dictionaryData'=> array(
		        	"entry-not-found"=> __('No question found','conversational-form'),
		        	"input-placeholder"=> __('Your answer here','conversational-form'),
		        	"input-placeholder-required"=> __('An answer is required to continue','conversational-form'),
		        	"input-placeholder-error"=> __('Incorrect input','conversational-form'),
		        	"input-placeholder-file-error"=> __('File download failed','conversational-form'),
		        	"input-placeholder-file-size-error"=> __('File sire error','conversational-form'),
		        	"input-no-filter"=> __('No result for <strong>{input-value}</strong>','conversational-form'),
		        	"user-reponse-and"=> __(' and ','conversational-form'),
		        	"user-reponse-missing"=> __('Missing input','conversational-form'),
		        	"general"=> __('type1 general|type2 general','conversational-form')
		         ),// empty will throw error
		        'dictionaryRobot'=> array(
		        	"text"=> __('Please enter a text','conversational-form'),
		        	"input"=> __('Please enter a text','conversational-form'),
		        	"name"=> __('What\'s your name ?','conversational-form'),
		        	"email"=> __('Need your email','conversational-form'),
		        	"password"=> __('Please enter a password','conversational-form'),
		        	"tel"=> __('What\'s your phone number','conversational-form'),
		        	"radio"=> __('Please select one of them','conversational-form'),
		        	"checkbox"=> __('Select as much as you want','conversational-form'),
		        	"select"=> __('Select one of these options','conversational-form'),
		        	"general"=> __('Generalities 1|Generalities 2|Generalities 3','conversational-form')
		         ),
		        'slug'=>$slug,
				'send_data_to'=>$form->send_data_to,
				'confirmation_message'=>$form->confirmation_message,
				'redirect_link'=>$form->redirect_link,
				'mailto'=>$mailto,
				'user_image'=>$user_image,
				'robot_image'=>$robot_image,
				'form_sent_message'=> __('Well done, form have been sent','conversational-form'),
				'form_not_sent_message'=> __('Sorry there was an error sending form, try again later','conversational-form')
		    ));


		}



		return $html;



}
add_shortcode('conversational-form','conversational_form_shortcode_func');





?>




			
		
		
		

		


	



