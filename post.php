<?php

set_time_limit( 1800 );
require_once 'config.php';

$joomdb = new wpdb( JOOM_USER, JOOM_PASS, JOOM_DB, JOOM_HOST );
$sql = 'SELECT c.*, cat.name as cat_name, u.username FROM ' . JOOM_PREFIX . 'content c
        LEFT JOIN ' . JOOM_PREFIX . 'categories cat ON cat.id = c.catid
        LEFT JOIN ' . JOOM_PREFIX . 'users u ON u.id = c.created_by
        WHERE c.state = 1';
$posts = $joomdb->get_results( $sql );

//Delete all posts
//$post_query = new WP_Query( 'posts_per_page=-1&post_status=draft' );
//while ($post_query->have_posts()) {
//    $post_query->the_post();
//
//    wp_delete_post( $post->ID, true );
//}
//die();

foreach ($posts as $post) {
    $term = get_term_by( 'name', $post->cat_name, 'category' );
    $author = get_user_by( 'login', $post->username );
    $exists = get_page_by_title( $post->title, OBJECT, 'post' );
    //var_dump( $post, $term, $author );

    $postarr = array(
        'post_title' => $post->title,
        'post_content' => $post->introtext . $post->fulltext,
        'post_category' => array($term->term_id),
        'post_date' => $post->created,
        'post_author' => $author->ID,
        'post_status' => 'publish'
    );

    $postarr['post_content'] = str_replace( '<hr id="system-readmore" />', "<!--more-->", $postarr['post_content'] );

    if ( !$exists ) {
        //var_dump( $postarr );
        $post_id = wp_insert_post( $postarr );
        var_dump( $post_id );
    }
}