<?php

/*

Plugin Name: Image Price Calculator with WooCommerce
Plugin URI:
Description: Image Price Calculator is a WordPress plugin that allows you to create your own price calculation form.
             You can use it to provide instant price quotes or estimates on products and services for your visitors
             and add the product to the shopping WooCommerce
Version: 2.0
Author: JaviRomera, Bernardo Luque
Author URI: 

*/



class ImagePriceCalculator
{ 				
	public function __construct() {			
			
		//Admin functions

		$this->spc_admin();


			
		add_action('init', array($this,'register_scripts'));	
		add_shortcode('spc-form',array($this,'spc_shortcode'));		
							
	}

	private function spc_admin(){
	    if(is_admin())
		require_once('spcadmin.php');
	}


	

    
   function activacionWoocommerce( $post_id) {
        
        global $wpdb;
        
        
        $sql = $wpdb->prepare( "
                SELECT post_id
                FROM $wpdb->postmeta
                WHERE post_id = %s
            ", $post_id);

        $idpost = $wpdb->get_var( $sql );
        

        if ( $idpost ) {
            return get_post_meta( $idpost );
        } else {
            return null;
        }
    }
	



	//Image Price Calculator Shortcode for displaying form on frontend

	function spc_shortcode ($atts) {


		
		extract(shortcode_atts( array (

		'id' => '',		

		),$atts));

		
		$currpost = get_post($id);
		$postmeta = $this->activacionWoocommerce($currpost->ID);

				
		wp_enqueue_script('spc-number');
		wp_enqueue_script('spc-pricecalc');
		wp_enqueue_style('spc-css');

		if($postmeta['activarwoo'][0] == 'on'){
			

			echo'
			<script>
			jQuery(document).ready( function ($) {
				$("#spcquoteform").ImagePriceCalc();
				
			});
			</script>
			';				
			
			echo '<form id="spcquoteform">';   		
				
			echo $currpost->post_content;				
		    
			echo '</form> ';

	        }
	        else{
	        	

	        	echo'
				<script>
				jQuery(document).ready( function ($) {
					$("#spcquoteform").ImagePriceCalc();
					$("#btnCarro").hide();
					
				});
				</script>
				';				
				
				echo '<form id="spcquoteform">';   		
					
				echo $currpost->post_content;				
			    
				echo '</form> ';
	        	
	        }		
				
		
	}
	
	function register_scripts() {
		wp_register_script('spc-number',plugins_url('jquery.number.min.js',__FILE__));
		wp_register_script('spc-pricecalc',plugins_url('Imagepricecalc.js',__FILE__));
		wp_register_style('spc-css',plugins_url('spcstyle.css',__FILE__));
	}


	
	

 }  // End of Image Price Calculator class

$imagepricecalc = new ImagePriceCalculator; 
 
 
?>