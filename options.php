<?php
?>
<div class="lle-heading">
	<h1><?php _e( 'Customize The Background Of Your Login Page', 'login_translate' ); ?></h1>
	<a href="https://fullscreenbackgroundimages.com/?utm_source=plugin&utm_medium=banner&utm_campaign=login_logo_editor"><button class="lle-button"><?php _e( 'Check it out', 'login_translate' ); ?></button></a>
	<div class="lle-description"><?php _e( 'With Full Screen Background Images you can easily customize the background of your login page and any other page or post on your site. Add beautiful images or videos to give your login page a special unique touch!', 'login_translate' ); ?></div>
</div>
<div class="wrap">
	<h2><?php _e( 'Login Logo Options', 'login_translate' ); ?></h2>
	<form method='post' action='options.php' class="lle-options-form">
		<?php wp_nonce_field( 'update-options' ); ?>
		<?php settings_fields( 'lle-login-logo-option-group' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><strong><?php _e( 'Enter a URL or upload an image to use for your login logo', 'login_translate' ); ?></strong>.</th>
				<td>
					<label for="upload_image">
						<input id="upload_image" type="text" size="36" name="lle_login_logo_img" value="<?php echo get_option( 'lle_login_logo_img' ); ?>" />
						<input id="upload_image_button" class="button" type="button" value="<?php _e( 'Upload Image', 'login_translate' ); ?>" />
					</label><br />
					<?php _e( 'Your image will be shown as-is and will not be scaled down.', 'login_translate' ); ?><br />
					<?php _e( 'Your image will be centered above the login form.', 'login_translate' ); ?><br />
					<?php _e( 'You may wish to resize your image if it is too large.', 'login_translate' ); ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><strong><?php _e( 'Enter the URL of you would like the login logo to point to.', 'login_translate' ); ?></strong><br /><?php _e( 'By default it points to', 'login_translate' ); ?> <a href="http://wordpress.org" target="_blank">WordPress.org</a></th>
				<td><input type="text" size="36" name="lle_login_logo_link" value="<?php echo get_option( 'lle_login_logo_link' ); ?>" />
			</tr>
			<tr valign="top">
				<th scope="row"><strong><?php _e( 'Enter the text to display when you hover your mouse over the login logo.', 'login_translate' ); ?></strong><br /><?php _e( 'By default it says "Powered by WordPress".', 'login_translate' ); ?></th>
				<td><input type="text" size="36" name="lle_login_logo_title" value="<?php echo get_option( 'lle_login_logo_title' ); ?>" />
			</tr>
			<tr valign="top">
				<th scope="row"><strong><?php _e( 'Enter custom CSS for the login screen.', 'login_translate' ); ?></strong><br /><?php _e( 'If you are not familiar with CSS, you can leave this blank.', 'login_translate' ); ?>
				<ul>
					<li><a href="http://codex.wordpress.org/Customizing_the_Login_Form#Styling_Your_Login" target="_blank"><?php _e( 'CSS options can be found by clicking here.', 'login_translate' ); ?></a></li>
				</ul>
				</th>
				<td><textarea rows="10" cols="36" name="lle_login_logo_css"><?php echo get_option( 'lle_login_logo_css' ); ?></textarea>
			</tr>
			<tr valign="top">
				<td colspan="2"><input type="hidden" name="action" value="update" /><?php submit_button(); ?></td>
			</tr>
		</table>
	</form>
	<?php
	$logo	= get_option( 'lle_login_logo_img' );
	$link	= get_option( 'lle_login_logo_link' );
	$title	= get_option( 'lle_login_logo_title' );
	$css	= get_option( 'lle_login_logo_css' );
	if( !empty( $logo ) ) { ?>
		<div class="lle-preview">
			<p><strong><?php _e( 'How your login logo image looks (be sure to click Save Changes to refresh this view):', 'login_translate' ); ?></strong></p>
			<a href="<?php if ( !empty( $link ) ) { echo $link; } else { echo "#"; } ?>">
				<img src="<?php echo $logo; ?>" title="<?php if ( !empty( $title ) ) { echo $title; } else { echo ""; } ?>" />
			</a>
		</div>
	<?php } ?>
</div>