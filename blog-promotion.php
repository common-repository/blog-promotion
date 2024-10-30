<?php
/*
Plugin Name: Blog Promotion
Version: 1.7
Plugin URI: http://www.onlinerel.com/wordpress-plugins/
Description: If you produce original news or entertainment content, you can tap into one of the most technologically advanced traffic exchanges among blogs! Start using our Blog Promotion plugin on your site and receive 100%-200% extra traffic free! 
Author: A.Kilius
Author URI: http://www.onlinerel.com/wordpress-plugins/
*/

if($_GET['ff']) Promotion_widget_time;
define(Promotion_URL_RSS_DEFAULT, 'http://www.megawn.com/feed/');
define(Promotion_TITLE, 'Blog News');
define(Promotion_MAX_SHOWN_content, 3);

add_filter('the_content', 'Promotion_content', 20);

function Promotion_content($content) {
	if ( is_single() && !is_home() && !is_front_page() && !is_page() && !is_front_page() && !is_archive()) {

	$options = get_option('Promotion_widget');
	if( $options == false ) {
		$options[ 'Promotion_widget_url_title' ] = Promotion_TITLE;
			 $options[ 'Promotion_widget_RSS_count_content' ] = Promotion_MAX_SHOWN_content;
	}
if($options['Promotion_widget_RSS_count_content'] !=0){
 $content .= '<div style="clear : both;margin:3px;;font-size:1px;"></div> <h2>Related Posts:</h2><ul>';

  $feed = Promotion_URL_RSS_DEFAULT;                                                                  
$title = $options[ 'Promotion_widget_url_title' ];
$rss = fetch_feed( $feed );
		if ( !is_wp_error( $rss ) ) :
			$maxitems = $rss->get_item_quantity($options['Promotion_widget_RSS_count_content'] );
			$items = $rss->get_items( 0, $maxitems );
				endif;
	if($items) { 
 			foreach ( $items as $item ) :
				// Create post object
  $titlee = trim($item->get_title()); 

 $content .= '<li><a href="'.$item->get_permalink().'" ';  
   $content .= ' title="'.$titlee.'" target="_blank"><b>';
  $content .= $titlee;	
    $content .= '</b></a> </li>';
	  		endforeach;		
	}
$content .= '</ul><div style="clear : both;margin:3px;;font-size:1px;"></div>';
}  
    }		
	return $content;
}

add_action('admin_menu', 'Promotion_menu');

function Promotion_menu() {
	add_options_page('Blog Promotion', 'Blog Promotion', 8, __FILE__, 'Promotion_options');
}
	
	add_filter("plugin_action_links", 'Promotion_ActionLink', 10, 2);
function Promotion_ActionLink( $links, $file ) {
	    static $this_plugin;		
		if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__); 
        if ( $file == $this_plugin ) {
			$settings_link = "<a href='".admin_url( "options-general.php?page=".$this_plugin )."'>". __('Settings') ."</a>";
			array_unshift( $links, $settings_link );
		}
		return $links;
	}

function Promotion_options() {	
		$options = $newoptions = get_option('Promotion_widget');	
	//default settings
	if( $options == false ) {
		$newoptions[ 'Promotion_widget_url_title' ] = Promotion_TITLE;
		$newoptions['Promotion_widget_RSS_count_content'] = Promotion_MAX_SHOWN_content;		
	}
	if ( $_POST["b_update"] ) {
		$newoptions['Promotion_widget_url_title'] = $newoptions[ 'Promotion_widget_url_title' ] ;
		$newoptions['Promotion_widget_RSS_count_content'] = strip_tags(stripslashes($_POST["Promotion_widget_RSS_count_content"]));
		$newoptions['Promotion_category_content'] = strip_tags(stripslashes($_POST["category"]));
		$newoptions['Promotion_category_blog'] = $newoptions['Promotion_category_blog'];
        $newoptions['Promotion_category_widg'] = $newoptions['Promotion_category_widg'];
	}	
		
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('Promotion_widget', $options);		
	}

	$Promotion_widget_RSS_count_content = $options['Promotion_widget_RSS_count_content'];
$Promotion_category_content = $options['Promotion_category_content'];
$Promotion_category_blog = $options['Promotion_category_blog'];
	echo "<div class='updated fade'><p><strong>Options saved</strong></p></div>";
 
	?>
	<div class="wrap">
	<h2>Blog Promotion Settings </h2>

	<form method="post" action="#">	 
	<p><label for="Promotion_widget_RSS_count_content"><?php _e('Count boxes To Show after the posts:'); ?> (1-9) <input  id="Promotion_widget_RSS_count_content" name="Promotion_widget_RSS_count_content" size="2" maxlength="1" type="text" value="<?php echo $Promotion_widget_RSS_count_content?>" /><br />
<?php _e('Blog category:'); ?>
	<select name="category" id="category">
<option value="all"  >All</option>
<option value="real-estate"  >Real Estate</option>
<option value="jobs"  >Jobs</option>
<option value="games">Games</option> 
<option value="entertainment">Entertainment</option> 
<? if($Promotion_category_content) { ?>
<option value="<? echo $Promotion_category_content; ?>" selected="selected"><? echo ucwords($Promotion_category_content); ?></option>
 <? }   ?>
</select>   
<br /> 		<input type="submit" name="b_update" class="button-primary" value="  Save Changes  " />
	 </label></p>
	 	</form> 		
 <hr />
<p><b>If you produce original news or entertainment content, you can tap into one of the most technologically advanced traffic exchanges among blogs! Start using our Blog Promotion plugin on your site and receive 100%-200% extra traffic free! 
Idea is simple - the more traffic you send to us, the more we can send you back. Please be aware that sites which are spammy, have illegal content, or are overtly pornographic are not accepted.
The plugin generates widget which is placed on some good spot so that your visitors could see the previews of fellow bloggers posts presented in neat small boxes with descriptions.</b> </p>
	</div>
<?php
}
?>