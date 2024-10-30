<?php
/*
Plugin Name: KV Alexa Dashboard
Plugin URI: http://kvcodes.com
Description: A simple wordpress plugin to get your Wordpres CMS ALexa Rank(global and Local), Backlink, and reach. <a href="http://www.kvcodes.com" target="_blank" > Read more </a> 
Version: 1.2
Author: kvvaradha
Author URI: http://profiles.wordpress.org/kvvaradha
*/


function kv_alexa_rank($kv_site) { 
	$xml = simplexml_load_file('http://data.alexa.com/data?cli=10&dat=snbamz&url='.$kv_site);
	return isset($xml->SD[1]->POPULARITY)?$xml->SD[1]->POPULARITY->attributes()->TEXT:0; 
}
function kv_alexa_local_rank($kv_site) { 
	$xml = simplexml_load_file('http://data.alexa.com/data?cli=10&dat=snbamz&url='.$kv_site);
	$kv_local_rank =  isset($xml->SD[1]->COUNTRY)?$xml->SD[1]->COUNTRY->attributes()->NAME:0 .'   ' . isset($xml->SD[1]->COUNTRY)?$xml->SD[1]->COUNTRY->attributes()->RANK:0; 
	return $kv_local_rank; 
}
function kv_alexa_backlinks($kv_site) { 
	$xml = simplexml_load_file('http://data.alexa.com/data?cli=10&dat=snbamz&url='.$kv_site);
	return isset($xml->SD[0]->LINKSIN)?$xml->SD[0]->LINKSIN->attributes()->NUM:0; 
}
function kv_alexa_reach($kv_site) { 
	$xml = simplexml_load_file('http://data.alexa.com/data?cli=10&dat=snbamz&url='.$kv_site);
	return isset($xml->SD[1]->REACH)?$xml->SD[1]->REACH->attributes()->RANK:0; 
}
function kv_dashboard_widgets() {
	wp_add_dashboard_widget('dashboard_widget', 'Your Alexa Status', 'kv_print_backlink_result');
}

add_action('wp_dashboard_setup', 'kv_dashboard_widgets' );

function kv_print_backlink_result() {
$kv_domain = site_url();  
 ?>
<table width="100%" > 			<tr><td colspan="2" > <img src="http://pcache.alexa.com/images/logos/alexalogo-header.920f192e91775bdb6af0d01f18454360.png" > </td> </tr> 					
								<tr> <td> Alexa Rank : </td> <td> <?php echo kv_alexa_rank($kv_domain); ?>( Global) <br> <?php echo kv_alexa_local_rank($kv_domain); ?> (Local) </td> </tr>
								<tr> <td> Alexa Backlinks : </td> <td> <?php echo kv_alexa_backlinks($kv_domain); ?> </td> </tr>
								<tr> <td> Alexa Reach : </td> <td> <?php echo kv_alexa_reach($kv_domain); ?> </td> </tr>
							</table> <?php }

//Front end Widget Display: 
add_action(   'widgets_init',   create_function('','return register_widget("Alexa_Rank_Widget");'));
class Alexa_Rank_Widget extends WP_Widget {    
	function Alexa_Rank_Widget() {                          
		$this->WP_Widget(                
			'Alexa Rank',                
			'Alexa Rank', // kv name your widget here.                
			array(    'classname' => 'Alexa_Rank',  'description' => 'Showing Alexa Details on widget.' ) //description to know about your widget.           
		);	
	}  
	function widget($args, $instance) { // kv widget sidebar output        
		extract($args, EXTR_SKIP);        
		echo $before_widget; // kv pre-widget code from theme       
		kv_print_backlink_result() ; 	
		echo $after_widget; // kv  post-widget code from theme    
	}
} // Class ends here. 	
?>