<?php 

if ($_GET['email']) {

	$email_address = $_GET['email'];

	$phone = $_GET['phone'];

	$name = $_GET['name'];


	if( null == username_exists( $email_address ) ) {

		$password = wp_generate_password( 12, true );

		$user_id = wp_create_user ( $email_address, $password, $email_address );

		wp_update_user(

	  	array(
		    'ID'       => $user_id,
		    'nickname' => $email_address
		  )
		);




		$user = new WP_User( $user_id );

		$user->set_role( 'wholesaler' );

		$user->set_billing_phone( $phone );

		$user->set_billing_first_name($name);

		$user->save();


		wp_mail( $email_address, 'Welcome!', 'Your password is: ' . $password );

		wp_redirect(get_admin_url().'/user-edit.php?user_id='.$user_id,200);

		die();
	}
}






?>