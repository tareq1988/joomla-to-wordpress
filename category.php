<?php

require_once 'config.php';
require_once ABSPATH . '/wp-admin/includes/taxonomy.php';

$joomdb = new wpdb( JOOM_USER, JOOM_PASS, JOOM_DB, JOOM_HOST );
$categories = $joomdb->get_results( 'select * from ' . JOOM_PREFIX . 'categories' );

foreach ($categories as $category) {
    $catarr = array(
        'cat_name' => $category->name,
        'category_description' => $category->description
    );

    //var_dump( $catarr );

    if ( !get_term_by( 'name', $category->name, 'category' ) ) {
        $term_id = wp_insert_category( $catarr );
        var_dump( $term_id );
    }
}