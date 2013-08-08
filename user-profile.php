<?php

/*
*
* Template Name: User Profile
*
* Allow users to update their profiles from Frontend.
*
*/

add_filter('show_admin_bar', '__return_false');

/* Get user info. */
global $current_user, $wp_roles;
get_currentuserinfo();

/* Load the registration file. */
require_once( ABSPATH . WPINC . '/registration.php' );
$error = array();    
/* If profile was saved, update profile. */
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' ) {

    /* Update user password. */
    if ( !empty($_POST['pass1'] ) && !empty( $_POST['pass2'] ) ) {
        if ( $_POST['pass1'] == $_POST['pass2'] )
            wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => esc_attr( $_POST['pass1'] ) ) );
        else
            $error[] = __('The passwords you entered do not match.  Your password was not updated.', 'profile');
    }

    /* Update user information. */
    if ( !empty( $_POST['url'] ) )
        update_user_meta( $current_user->ID, 'user_url', esc_url( $_POST['url'] ) );
    if ( !empty( $_POST['email'] ) ){
        if (!is_email(esc_attr( $_POST['email'] )))
            $error[] = __('The Email you entered is not valid.  please try again.', 'profile');
        elseif(email_exists(esc_attr( $_POST['email'] )) != $current_user->id )
            $error[] = __('This email is already used by another user.  try a different one.', 'profile');
        else{
            wp_update_user( array ('ID' => $current_user->ID, 'user_email' => esc_attr( $_POST['email'] )));
        }
    }

    if ( !empty( $_POST['first-name'] ) )
        update_user_meta( $current_user->ID, 'first_name', esc_attr( $_POST['first-name'] ) );
    if ( !empty( $_POST['last-name'] ) )
        update_user_meta($current_user->ID, 'last_name', esc_attr( $_POST['last-name'] ) );
    if ( !empty( $_POST['description'] ) )
        update_user_meta( $current_user->ID, 'description', esc_attr( $_POST['description'] ) );

  	/* Code added by Chris MacKay for Declare Brands */
		
		$all_meta_for_user = get_user_meta($current_user->ID);
		
		if ( !empty( $_POST['wpcf-email2'] ) ){
        update_user_meta($current_user->ID, 'wpcf-email2', esc_attr( $_POST['wpcf-email2'] ) );
		} else {
		    if (!empty($all_meta_for_user['wpcf-email2'][0])){
				    update_user_meta($current_user->ID, 'wpcf-email2', '');
				}
		}
				
		if ( !empty( $_POST['wpcf-phone1'] ) ){
        update_user_meta($current_user->ID, 'wpcf-phone1', esc_attr( $_POST['wpcf-phone1'] ) );
		} else {
		    if (!empty($all_meta_for_user['wpcf-phone1'][0])){
				    update_user_meta($current_user->ID, 'wpcf-phone1', '');
				}
		}
				
		if ( !empty( $_POST['wpcf-phone2'] ) ){
        update_user_meta($current_user->ID, 'wpcf-phone2', esc_attr( $_POST['wpcf-phone2'] ) );
		} else {
		    if (!empty($all_meta_for_user['wpcf-phone2'][0])){
				    update_user_meta($current_user->ID, 'wpcf-phone2', '');
				}
		}
		
		if ( !empty( $_POST['wpcf-mobile'] ) ){
        update_user_meta($current_user->ID, 'wpcf-mobile', esc_attr( $_POST['wpcf-mobile'] ) );
		} else {
		    if (!empty($all_meta_for_user['wpcf-mobile'][0])){
				    update_user_meta($current_user->ID, 'wpcf-mobile', '');
				}
		}
		
		if ( !empty( $_POST['wpcf-fax'] ) ){
        update_user_meta($current_user->ID, 'wpcf-fax', esc_attr( $_POST['wpcf-fax'] ) );
		} else {
		    if (!empty($all_meta_for_user['wpcf-fax'][0])){
				    update_user_meta($current_user->ID, 'wpcf-fax', '');
				}
		}
			
		if ( !empty( $_POST['wpcf-streetaddress'] ) ){
        update_user_meta($current_user->ID, 'wpcf-streetaddress', esc_attr( $_POST['wpcf-streetaddress'] ) );
		} else {
		    if (!empty($all_meta_for_user['wpcf-streetaddress'][0])){
				    update_user_meta($current_user->ID, 'wpcf-streetaddress', '');
				}
		}
		
		if ( !empty( $_POST['wpcf-city'] ) ){
        update_user_meta($current_user->ID, 'wpcf-city', esc_attr( $_POST['wpcf-city'] ) );
		} else {
		    if (!empty($all_meta_for_user['wpcf-city'][0])){
				    update_user_meta($current_user->ID, 'wpcf-city', '');
				}
		}
				
		if ( !empty( $_POST['wpcf-province'] ) ){
        update_user_meta($current_user->ID, 'wpcf-province', esc_attr( $_POST['wpcf-province'] ) );
		} else {
		    if (!empty($all_meta_for_user['wpcf-province'][0])){
				    update_user_meta($current_user->ID, 'wpcf-province', '');
				}
		}
			
		if ( !empty( $_POST['wpcf-user_zip'] ) ){
        update_user_meta($current_user->ID, 'wpcf-user_zip', esc_attr( $_POST['wpcf-user_zip'] ) );
		} else {
		    if (!empty($all_meta_for_user['wpcf-user_zip'][0])){
				    update_user_meta($current_user->ID, 'wpcf-user_zip', '');
				}
		}
		
		/* */
		
    /* Redirect so the page will show updated info.*/
    if ( count($error) == 0 ) {
        wp_redirect( get_permalink() );
        exit;
    }
}

get_header();

?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>">
        <div class="entry-content entry">
            <?php the_content(); ?>
            <?php if ( !is_user_logged_in() ) : ?>
                    <p class="warning">
                        <?php _e('You must be logged in to edit your profile.', 'profile'); ?>
                    </p><!-- .warning -->
            <?php else : ?>
                <?php if ( count($error) > 0 ) echo '<p class="error">' . implode("<br />", $error) . '</p>'; ?>
                <form method="post" id="adduser" action="<?php the_permalink(); ?>">
								  <h2>Personal Details</h2>
								  <table>
									  <tr>
                      <td width="25%">
                        <label for="first-name"><?php _e('First Name', 'profile'); ?></label>
											</td>
											<td>
                        <input class="text-input" name="first-name" type="text" id="first-name" value="<?php the_author_meta( 'first_name', $current_user->ID ); ?>" />
											</td>
										</tr>
										<tr>
										  <td>
                        <label for="last-name"><?php _e('Last Name', 'profile'); ?></label>
											</td>
											<td>
                        <input class="text-input" name="last-name" type="text" id="last-name" value="<?php the_author_meta( 'last_name', $current_user->ID ); ?>" />
											</td>
										</tr>
										<tr>
										  <td>
                        <label for="email"><?php _e('E-mail *', 'profile'); ?></label>
											</td>
											<td>
                        <input class="text-input" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" disabled="disabled" />
											</td>
									  </tr>
										<?php
										  /* Code added by Chris MacKay for Declare Brands */
										  print '<tr>';
											  print '<td>';
										      print '<label for="wpcf-email2">Secondary E-mail</label>';
												print '</td>';
												print '<td>';
                          print '<input class="text-input" name="wpcf-email2" type="text" id="wpcf-email2" value="'.types_render_usermeta_field( "email2", array("raw" => true)).'"/>';
												print '</td>';
											print '</tr>';
											/* */
										?>
										<tr>
										  <td>
                        <label for="description"><?php _e('Biographical Info', 'profile') ?></label>
											</td>
											<td>
                        <textarea name="description" id="description" rows="3" cols="50"><?php the_author_meta( 'description', $current_user->ID ); ?></textarea>
											</td>
										</tr>
									</table>
									<h2>Contact Details</h2>
									<table>
										<?php
										  /* Code added by Chris MacKay for Declare Brands */
											print '<tr>';
											  print '<td width="25%">';
										      print '<label for="wpcf-phone1">Phone 1</label>';
												print '</td>';
												print '<td>';
                          print '<input class="text-input" name="wpcf-phone1" type="text" id="wpcf-phone1" value="'.types_render_usermeta_field( "phone1", array("raw" => true)).'"/>';
											  print '</td>';
											print '</tr>';
											print '<tr>';
											  print '<td>';
										      print '<label for="wpcf-phone2">Phone 2</label>';
												print '</td>';
												print '<td>';
                          print '<input class="text-input" name="wpcf-phone2" type="text" id="wpcf-phone2" value="'.types_render_usermeta_field( "phone2", array("raw" => true)).'"/>';
												print '</td>';
											print '</tr>';
											print '<tr>';
											  print '<td>';
										      print '<label for="wpcf-mobile">Mobile</label>';
												print '</td>';
												print '<td>';
                          print '<input class="text-input" name="wpcf-mobile" type="text" id="wpcf-mobile" value="'.types_render_usermeta_field( "mobile", array("raw" => true)).'"/>';
												print '</td>';
											print '</tr>';
											print '<tr>';
											  print '<td>';
										      print '<label for="wpcf-fax">Fax</label>';
												print '</td>';
												print '<td>';
                          print '<input class="text-input" name="wpcf-fax" type="text" id="wpcf-fax" value="'.types_render_usermeta_field( "fax", array("raw" => true)).'"/>';
												print '</td>';
											print '</tr>';
											print '<tr>';
											  print '<td>';
										      print '<label for="wpcf-streetaddress">Street Address</label>';
												print '</td>';
												print '<td>';
                          print '<input class="text-input" name="wpcf-streetaddress" type="text" id="wpcf-streetaddress" value="'.types_render_usermeta_field( "streetaddress", array("raw" => true)).'"/>';
												print '</td>';
											print '</tr>';
											print '<tr>';
											  print '<td>';
										      print '<label for="wpcf-city">City</label>';
												print '</td>';
												print '<td>';
                          print '<input class="text-input" name="wpcf-city" type="text" id="wpcf-city" value="'.types_render_usermeta_field( "city", array("raw" => true)).'"/>';
												print '</td>';
											print '</tr>';
											print '<tr>';
											  print '<td>';
										      print '<label for="wpcf-province">Province</label>';
												print '</td>';
												print '<td>';
                          print '<input class="text-input" name="wpcf-province" type="text" id="wpcf-province" value="'.types_render_usermeta_field( "province", array("raw" => true)).'"/>';
												print '</td>';
											print '</tr>';
											print '<tr>';
											  print '<td>';
										      print '<label for="wpcf-user_zip">Zip/Postal Code</label>';
												print '</td>';
												print '<td>';
                          print '<input class="text-input" name="wpcf-user_zip" type="text" id="wpcf-user_zip" value="'.types_render_usermeta_field( "user_zip", array("raw" => true)).'"/>';
												print '</td>';
											print '</tr>';
											/* */								
										?>
									</table>
									<h2>Security</h2>
									<table>
                    <tr>
										  <td width="25%">
                        <label for="pass1"><?php _e('Password *', 'profile'); ?> </label>
											</td>
											<td>
                        <input class="text-input" name="pass1" type="password" id="pass1" />
											</td>
										</tr>
										<tr>
										  <td>
                        <label for="pass2"><?php _e('Repeat Password *', 'profile'); ?></label>
											</td>
											<td>
                        <input class="text-input" name="pass2" type="password" id="pass2" />
											</td>
										</tr>
									</table>
                  <p class="form-submit">
                      <?php echo $referer; ?>
                      <input name="updateuser" type="submit" id="updateuser" class="submit button" value="<?php _e('Update', 'profile'); ?>" />
                      <?php wp_nonce_field( 'update-user' ) ?>
                      <input name="action" type="hidden" id="action" value="update-user" />
                  </p><!-- .form-submit -->
                </form><!-- #adduser -->
            <?php endif; ?>
        </div><!-- .entry-content -->
    </div><!-- .hentry .post -->
    <?php endwhile; ?>
<?php else: ?>
    <p class="no-data">
        <?php _e('Sorry, no page matched your criteria.', 'profile'); ?>
    </p><!-- .no-data -->
<?php endif; ?>

<?php get_footer(); ?>
