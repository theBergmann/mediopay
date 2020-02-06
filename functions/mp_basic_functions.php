<?php
function uninstall_mediopay() {
	 global $wpdb;
    $table_name = $wpdb->prefix . "mediopay";
     $sql = "DROP TABLE IF EXISTS $table_name;";
     $wpdb->query($sql);
     delete_option("my_plugin_db_version");
}

function mediopaydeactivate() {
	  global $wpdb;
    $table_name = $wpdb->prefix . "mediopay";
     $sql = "DROP TABLE IF EXISTS $table_name;";
     $wpdb->query($sql);
     delete_option("my_plugin_db_version");
}


function mediopayactivate() {
	global $wpdb;
   $table_name = $wpdb->prefix . "mediopay";
   $charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		address tinytext NOT NULL,
		currency tinytext NOT NULL,
		versionNumber tinytext NOT NULL,
		sharingQuote tinytext NOT NULL,
		ref tinytext NOT NULL,
		fixedAmount tinytext NOT NULL,
		fixedTipAmount tinytext NOT NULL,
		fixedThankYou tinytext NOT NULL,
		noMetanet tinytext NOT NULL,
		noEditField tinytext NOT NULL,
		barColor tinytext NOT NULL,
		editableTips tinytext NOT NULL,
		address2 tinytext NOT NULL,
		secondAddressShare tinytext NOT NULL,
		alignLeft tinytext NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

function mediopayactivate_data() {
	global $wpdb;
	$welcome_name = 'none';
	$welcome_address = 'none';
	$version_number = '0.1';
	$sharing_quote = '0.1';
	$ref = '0.1';
	$fixed_amount = 'none';
	$fixed_tip_amount = 'none';
	$fixed_thank_you = 'none';
	$no_metanet = 'no';
	$table_name = $wpdb->prefix . 'mediopay';
	$no_edit_field = 'no';
	$barColor = 'FB9868';
	$editableTips = 'no';
	$address2 = 'none';
	$secondAddressShare = '0.0';
	$alignleft = "no";
	$wpdb->insert(
		$table_name,
		array(
			//'time' => current_time( 'mysql' ),
			'address' => $welcome_name,
			'currency' => $welcome_address,
			'versionNumber' => $version_number,
			'sharingQuote' => $sharing_quote,
			'ref' => $ref,
			'fixedAmount' => $fixed_amount,
			'fixedTipAmount' => $fixed_tip_amount,
			'fixedThankYou' => $fixed_thank_you,
			'noMetanet' => $no_metanet,
			'noEditField' => $no_edit_field,
			'barColor' => $barColor,
			'editableTips' => $editableTips,
			'address2' => $address2,
			'secondAddressShare' => $secondAddressShare,
			'alignLeft' => $alignleft
		)
	);
}



function mediopay_add_scripts() {
	$path = plugin_dir_url( 'mediopay.php');
	$path = $path . "mediopay/lib/";
	wp_enqueue_script('jquery');
	wp_enqueue_style( 'style', $path . 'style.css' );
	wp_enqueue_script( 'moneybutton', $path . 'moneybutton.js', true);
	wp_enqueue_script( 'bsv', $path . 'bsv.min.js', true);
	wp_enqueue_script( 'scripts_pre_paywall', $path . 'scripts_pre_paywall.js', true);
	wp_enqueue_script( 'scripts_create_paywall', $path . 'scripts_create_paywall.js', true);
	wp_enqueue_script( 'scripts_after_paywall', $path . 'scripts_after_paywall.js',  true);
	//wp_enqueue_script( 'ajax-script', get_template_directory_uri() . '/js/my-ajax-script.js', array('jquery') );
	wp_enqueue_script( 'ajax-script', plugin_dir_url( __FILE__ )  . '/lib/scripts_pre_paywall.js', array('jquery') );	
	wp_localize_script( 'ajax-script', 'mp_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

function mediopay_add_admin_scripts() {
	$path = plugin_dir_url( 'mediopay.php');
	$path = $path . "mediopay/lib/";
	//wp_localize_script( 'ajax-script', 'ajax_object',
   //         array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234 ) );
}





?>
