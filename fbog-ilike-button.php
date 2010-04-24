<?php
/*
Plugin Name: Facebook OpenGraph I Like Button
Plugin URI: http://raduboncea.ro/
Description: Integrates the facebook open graph I Like Button feature.  [<a href="options-general.php?page=fbog-ilike-button.php">Settings</a>]
Version: 1.0
Author: Radu Boncea
Author URI: http://raduboncea.ro/about/
*/


function FB_ILIKE_BUTTON($output_buffering=false) {
	
	if($output_buffering)ob_start();
	$linkurl		= get_permalink($post->ID);
	$linkurl_enc	= rawurlencode($linkurl);
	
	$_fb_layout_style = 'standard';
	$_fb_show_faces = 'true';
	if(get_option('FB_ILIKE_BUTTON_layout_style')!='') $_fb_layout_style = get_option('FB_ILIKE_BUTTON_layout_style');
	if(get_option('FB_ILIKE_BUTTON_show_faces')=='false') $_fb_show_faces = 'false';
	
	if(get_option('FB_ILIKE_BUTTON_width')=='') $_fb_width = '450';
    	else $_fb_width = get_option('FB_ILIKE_BUTTON_width');
	if(get_option('FB_ILIKE_BUTTON_height')=='') $_fb_height = '60';
    	else $_fb_height = get_option('FB_ILIKE_BUTTON_height');
	
    if(get_option('FB_ILIKE_BUTTON_action')=='') $_fb_action = 'like';
    	else $_fb_action = get_option('FB_ILIKE_BUTTON_action');
   	
    if(get_option('FB_ILIKE_BUTTON_colorscheme')=='') $_fb_colorscheme = 'light';
    	else $_fb_colorscheme = get_option('FB_ILIKE_BUTTON_colorscheme');
    
    if(get_option('FB_ILIKE_BUTTON_font')=='') $_fb_font = 'verdana';
    	else $_fb_font = get_option('FB_ILIKE_BUTTON_font');	
   	
	?>
	<iframe src="http://www.facebook.com/plugins/like.php?href=<? echo $linkurl_enc?>&amp;layout=<?=$_fb_layout_style?>&amp;show_faces=<?=$_fb_show_faces?>&amp;width=<?=$_fb_width?>&amp;action=<?=$_fb_action?>&amp;font=<?=$_fb_font?>&amp;colorscheme=<?=$_fb_colorscheme?>" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:<?=$_fb_width?>px; height:<?=$_fb_height?>px"></iframe>
	<?php
	if($output_buffering) {
		$ilike_button = ob_get_contents();
		ob_end_clean();
		return $ilike_button;
	}
}
	

function FB_ILIKE_BUTTON_show($content) {
	if ( 
		( (strpos($content, '<!--fbilike-->')===false) ) && (														// <!--sharesave-->
			( !is_page() && get_option('FB_ILIKE_BUTTON_display_in_posts')=='-1' ) || 								// All posts
			( !is_page() && !is_single() && get_option('FB_ILIKE_BUTTON_display_in_posts_on_front_page')=='-1' ) ||  // Front page posts
			( is_page() && get_option('FB_ILIKE_BUTTON_display_in_pages')=='-1' ) ||									// Pages
			( (strpos($content, '<!--nofbilike-->')!==false ) )												// <!--nosharesave-->
		)
	)	
	return $content;
	
	$_content = '<div style="margin-top:20px">'.FB_ILIKE_BUTTON(true).'</div>';
	if(get_option('FB_ILIKE_BUTTON_disposition')=='top'){
		$content = $_content.$content;
	}else{
		$content .= $_content;
	}
		
	return $content;
}

add_action('the_content', 'FB_ILIKE_BUTTON_show');


/*****************************
		OPTIONS
******************************/


function FB_ILIKE_BUTTON_options_page() {
	if( isset($_POST[ 'FB_ILIKE_BUTTON_saveit' ]) ) {
		 update_option( 'FB_ILIKE_BUTTON_display_in_posts_on_front_page', ($_POST['FB_ILIKE_BUTTON_display_in_posts_on_front_page']=='1') ? '1':'-1' );
		 update_option( 'FB_ILIKE_BUTTON_display_in_posts', ($_POST['FB_ILIKE_BUTTON_display_in_posts']=='1') ? '1':'-1' );
		 update_option( 'FB_ILIKE_BUTTON_display_in_pages', ($_POST['FB_ILIKE_BUTTON_display_in_pages']=='1') ? '1':'-1' );
		 update_option( 'FB_ILIKE_BUTTON_disposition', $_POST['FB_ILIKE_BUTTON_disposition'] );
		 update_option( 'FB_ILIKE_BUTTON_layout_style', $_POST['FB_ILIKE_BUTTON_layout_style'] );
		 update_option( 'FB_ILIKE_BUTTON_show_faces', ($_POST['FB_ILIKE_BUTTON_show_faces']=='true') ? 'true':'false' );
		 update_option( 'FB_ILIKE_BUTTON_height', trim($_POST['FB_ILIKE_BUTTON_height']) );
		 update_option( 'FB_ILIKE_BUTTON_width', trim($_POST['FB_ILIKE_BUTTON_width']) );
		 update_option( 'FB_ILIKE_BUTTON_action', $_POST['FB_ILIKE_BUTTON_action'] );
		 update_option( 'FB_ILIKE_BUTTON_colorscheme', $_POST['FB_ILIKE_BUTTON_colorscheme'] );
		 update_option( 'FB_ILIKE_BUTTON_font', $_POST['FB_ILIKE_BUTTON_font'] );
		 
		 
	?>
    <div class="updated fade"><p><strong><?php _e('Settings saved.', 'FB_ILIKE_BUTTON_trans_domain' ); ?></strong></p></div>
	<?php } ?>
	
	<div class="wrap">
		<h2><?php echo __( 'Facebook Open Graph: I Like Button Settings', 'FB_ILIKE_BUTTON_trans_domain' ); ?></h2>
	    <form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<?php wp_nonce_field('update-options'); ?>
		<input type="hidden" name="FB_ILIKE_BUTTON_saveit" />
		<table class="form-table">
			<tr valign="top">
	            <th scope="row">Button Placement</th>
	            <td>
	            	<fieldset>
		                <label>
		                	<input name="FB_ILIKE_BUTTON_display_in_posts" 
		                    	onclick="e=getElementsByName('FB_ILIKE_BUTTON_display_in_posts_on_front_page')[0];if(!this.checked){e.checked=false;e.disabled=true}else{e.checked=true;e.disabled=false}"
		                        onchange="e=getElementsByName('FB_ILIKE_BUTTON_display_in_posts_on_front_page')[0];if(!this.checked){e.checked=false;e.disabled=true}else{e.checked=true;e.disabled=false}"
		                        type="checkbox"<?php if(get_option('FB_ILIKE_BUTTON_display_in_posts')!='-1') echo ' checked="checked"'; ?> value="1"/>
		                	Display I Like button at the bottom of posts
		                </label><br/>
		                <label>
		                	<input name="FB_ILIKE_BUTTON_display_in_posts_on_front_page" type="checkbox"<?php 
								if(get_option('FB_ILIKE_BUTTON_display_in_posts_on_front_page')!='-1') echo ' checked="checked"';
								if(get_option('FB_ILIKE_BUTTON_display_in_posts')=='-1') echo ' disabled="disabled"';
								?> value="1"/>
		                    Display I Like button at the bottom of posts on the front page
						</label><br/>
		                <label>
		                	<input name="FB_ILIKE_BUTTON_display_in_pages" type="checkbox"<?php if(get_option('FB_ILIKE_BUTTON_display_in_pages')!='-1') echo ' checked="checked"'; ?> value="1"/>
		                    Display I Like button at the bottom of pages
						</label>
	            	</fieldset>
	            </td>
	         </tr>
	         <tr valign="top">
	            <th scope="row">Disposition</th>
	            <td>
	            	<fieldset>
		                <label>
		                	<input name="FB_ILIKE_BUTTON_disposition" type="radio" <?php if(get_option('FB_ILIKE_BUTTON_disposition')=='top') echo ' checked="checked"'; ?> value="top"/>
		                	Display at the top of the page/post
		                </label><br/>
		                <label>
		                	<input name="FB_ILIKE_BUTTON_disposition" type="radio" <?php if(get_option('FB_ILIKE_BUTTON_disposition')=='bottom' || get_option('FB_ILIKE_BUTTON_disposition')!='top') echo ' checked="checked"'; ?> value="bottom"/>
		                	Display at the bottom of the page/post
		                </label>
	            	</fieldset>
	            </td>
	         </tr>
	         <tr valign="top">
	            <th scope="row">Layout Options</th>
	            <td>
	            	<fieldset>
	            		<label>
	            		<select name="FB_ILIKE_BUTTON_layout_style">
	            			<option value="standard" <?php if(get_option('FB_ILIKE_BUTTON_layout_style')=='standard' || get_option('FB_ILIKE_BUTTON_layout_style')!='button_count') echo ' selected'; ?>>standard</option>
	            			<option value="button_count" <?php if(get_option('FB_ILIKE_BUTTON_layout_style')=='button_count') echo ' selected'; ?>>button count</option>
	            		</select>
	            		Style
	            		</label><br/>
	            		<label>
	            		<input name="FB_ILIKE_BUTTON_show_faces" 
		                        type="checkbox"<?php if(get_option('FB_ILIKE_BUTTON_show_faces')!='false') echo ' checked="checked"'; ?> value="true"/>
		                	Show faces
		                </label><br/>
		                <label>
		                	<?php 
		                	if(get_option('FB_ILIKE_BUTTON_width')=='') $_fb_width = '450';
		                		else $_fb_width = get_option('FB_ILIKE_BUTTON_width');
							
		                	if(get_option('FB_ILIKE_BUTTON_height')=='') $_fb_height = '60';
		                		else $_fb_height = get_option('FB_ILIKE_BUTTON_height');
		                	?>
		               		<input size="4" name="FB_ILIKE_BUTTON_width" type="text" value="<?=$_fb_width?>" />px
		               		&nbsp;&nbsp;X&nbsp;&nbsp;
		               		<input size="4" name="FB_ILIKE_BUTTON_height" type="text" value="<?=$_fb_height?>" />px
		               		&nbsp;&nbsp;&nbsp;&nbsp; Width x Height
		                </label><br/>
		                <label>
	            		<select name="FB_ILIKE_BUTTON_action">
	            			<option value="like" <?php if(get_option('FB_ILIKE_BUTTON_action')=='like' || get_option('FB_ILIKE_BUTTON_action')!='recommend') echo ' selected'; ?>>like</option>
	            			<option value="recommend" <?php if(get_option('FB_ILIKE_BUTTON_action')=='recommend') echo ' selected'; ?>>recommend</option>
	            		</select>
	            		Verb to display
	            		</label><br/>
	            		<label>
	            		<select name="FB_ILIKE_BUTTON_colorscheme">
	            			<option value="light" <?php if(get_option('FB_ILIKE_BUTTON_colorscheme')=='light' || get_option('FB_ILIKE_BUTTON_colorscheme')!='dark') echo ' selected'; ?>>light</option>
	            			<option value="dark" <?php if(get_option('FB_ILIKE_BUTTON_colorscheme')=='dark') echo ' selected'; ?>>dark</option>
	            		</select>
	            		Color Scheme
	            		</label><br/>
	            		<?php 
	            		$_fb_fonts = array(
	            			'tahoma'=>'tahoma',
	            			'arial'=>'arial',
	            			'verdana'=>'verdana',
	            			'lucida+grande'=>'lucida grande',
	            			'segoe+ui'=>'segoe ui',
	            			'trebuchet+ms'=>'trebuchet ms'
	            			);
	            			if(get_option('FB_ILIKE_BUTTON_font')=='') $_fb_font = 'verdana';
	            			else $_fb_font = get_option('FB_ILIKE_BUTTON_font');
	            		?>
	            		<label>
	            		<select name="FB_ILIKE_BUTTON_font">
	            			<?php 
	            			foreach ($_fb_fonts as $k=>$v) {
	            				?>
	            				<option value="<?=$k?>" <?php if($_fb_font==$k) echo ' selected'; ?>><?=$v?></option>
	            				<?php
	            			}
	            			?>
	            		</select>
	            		Font
	            		</label><br/>
	            	</fieldset>
	            </td>
	         </tr>
	         
	         </table>
	        <p class="submit">
	            <input type="submit" name="Submit" value="<?php _e('Save Changes', 'FB_ILIKE_BUTTON_trans_domain' ) ?>" />
	        </p>
	    </form>
    </div>
<?php
}


function FB_ILIKE_BUTTON_plugin_menu() {
	if( current_user_can('manage_options') ) {
		add_options_page(
			'Facebook OpenGraph: I Like Button Settings'
			, 'FB I Like Button'
			, 'administrator' 
			, basename(__FILE__)
			, 'FB_ILIKE_BUTTON_options_page'
		);
	}
}

add_action('admin_menu', 'FB_ILIKE_BUTTON_plugin_menu');
?>