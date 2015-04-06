
<?php

class ImagePriceCalcAdmin {

//Admin Panel Functions
    


	public function __construct() {
	
		add_action('init', array($this, 'image_admin_panel'));
		add_action('add_meta_boxes', array( $this, 'admin_panel_meta'));
		add_action('save_post', array( $this, 'metasave' ) );
		add_filter('manage_edit-image_price_calc_columns', array( $this, 'admin_table_columns' ) );
		add_action('manage_image_price_calc_posts_custom_column', array( $this, 'admin_table_columns_data'), 10, 2 );
		add_action('edit_form_after_title', array($this,'default_form_content'));
		add_filter('post_updated_messages', array($this,'admin_updated_messages' ));		
	}

  function image_admin_panel() {
		register_post_type('image_price_calc', array(
				'labels' => array(
				'name' => 'Image Price Calculator Forms' ,
				'singular_name' =>  'Image Price Calculator Form',
				'add_new_item' => 'Add New Form' ,
				'edit_item' => 'Edit Form'
				),
				'public' => false,
				'rewrite' => false,
				'has_archive' => true,
				'menu_position' => 100,
				'menu_icon' => 'dashicons-media-text',
				'show_ui' => true
			)
		);
	}



	function admin_panel_meta() {
		add_meta_box( 'jj', 'WooCommerce', array($this,'admin_form_render'), 'image_price_calc', 'normal', 'default');			
	}
	
	
	
	function metasave($post_id) {
		
		$mydata = sanitize_text_field( $_POST['activarwoo'] );

		update_post_meta( $post_id, 'activarwoo', $mydata );

	}

	function admin_form_render($post) {


		
				wp_nonce_field( 'myplugin_activarwoo_box', 'myplugin_activarwoo_box_nonce' );

				$value = get_post_meta( $post->ID, 'activarwoo', true );

			    echo "Do you need WooCommerce?   ";
			    
			    if ($value == 'on') {
			    	echo '<input type="checkbox" name="activarwoo" id="woo" checked>Activate ';
			    } else {
			    	echo '<input type="checkbox" name="activarwoo" id="woo">Activate';
			    }
                 
	}

	
	function admin_table_columns($columns) {
		$columns['shortcode'] = 'Shortcode';
		$columns['email'] = 'Email';

		
		return $columns;
	}
	
	function admin_table_columns_data($column,$post_id) {
	
		switch($column){
			case 'shortcode':			
			if($post_id)			
			echo "[spc-form id=" . $post_id . "]";
			break;		
			
			case 'email':			
			$savedemail='No e-mail specified';
			echo $savedemail;
			break;		

			default:
			echo $column . $post_id;			
		}
	}



	
	
	
	//Displays default form content if post is empty
	
	function default_form_content() {
		global $post;
		
		if ($post->post_type == 'image_price_calc'  && $post->post_content == '') {
			
			$sampformcontent='
            

			
			<h4>Select Master</h4>
			
			<select id="selmaster">
				<option id="selnone" value="0">Choose Option Type</option>
				<option id="Basic" value="10">Basic Type</option>
				<option id="Advance" value="20">Advanced Type</option>
			</select>
			<h4>Select Extra1</h4>
				<select id="selextra1">
					<option id="selnone2" value="0">Choose Option Type</option>
					<option id="nameImagextra1kind1" value="10">Option Extra1 Kind1</option>
					<option id="nameImagextra1kind2" value="20">Option Extra1 Kind2</option>
				</select>
			<h4>Select Extra2</h4>
				<select id="selextra2">
					<option id="selnone3" value="0">Choose Option Type</option>
					<option id="nameImagextra2kind1" value="10">Option Extra2 Kind1</option>
					<option id="nameImagextra2kind2" value="20">Option Extra2 Kind2</option>
				</select>
			<h4>Sample Checkbox Settings</h4>
			    <input id="nameImageCbox" type="checkbox" value="10" /><label for="Sample Checkbox">Sample Checkbox</label>
			<h4>Sample Radio Settings</h4>
			    <input id="noneradio" name="css" type="radio" value="0" /> None
				<input id="nameImageRadio1" name="css" type="radio" value="5" /><label for="Radio Setting1">Radio Setting1</label>
				<input id="nameImageRadio2" name="css" type="radio" value="7" /><label for="Radio Setting2">Radio Setting2</label>
			<div id="spinner"><img src="wp-content/plugins/Image-price-calculator/ajax-loader.gif" alt="spinner"></div><br>
			<button class="button add_to_cart_button product_type_simple" id="btnCarro" type="button">Add to cart</button>';
			
			
			$post->post_content = $sampformcontent;
					
		}
    
	}



	function admin_updated_messages( $messages ) {
		$messages['image_price_calc'] = array(
			1  => sprintf(__( 'Form updated. <a href="%s">View Shortcode</a>' ), esc_url(admin_url('edit.php?post_type=image_price_calc') ) ) ,
			6  => sprintf(__( 'Form published. <a href="%s">View Shortcode</a>' ), esc_url(admin_url('edit.php?post_type=image_price_calc') ) ),
			7  => __ ('Form saved.' ),
			10  => __ ('Form draft updated.' )
		);
		return $messages;
	}
	
}	

$imagepricecalcadmin = new ImagePriceCalcAdmin();


