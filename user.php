<?php

set_time_limit( 1800 );
require_once 'config.php';

$joomdb = new wpdb( JOOM_USER, JOOM_PASS, JOOM_DB, JOOM_HOST );
$users = $joomdb->get_results( 'select * from ' . JOOM_PREFIX . 'users WHERE username != "admin"' );

if ( $users ) {
    foreach ($users as $user) {
        $user_data = array(
            //'ID' => $user->id,
            'user_pass' => $user->password,
            'user_login' => $user->username,
            'user_email' => $user->email,
            'display_name' => $user->name,
            'user_registered' => $user->registerDate,
        );

        //var_dump( $user_data );
        //get_user_by('user_email', $user->email);
        if ( !email_exists( $user->email ) ) {
            $user_id = wp_insert_user( $user_data );
            var_dump( $user_id );
        }
    }
}
