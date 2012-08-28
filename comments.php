<?php

set_time_limit( 1800 );
require_once 'config.php';

$joomdb = new wpdb( JOOM_USER, JOOM_PASS, JOOM_DB, JOOM_HOST );
$sql = 'SELECT c.name, c.comment, c.date, c.ip, con.title, c.published FROM ' . JOOM_PREFIX . 'jomcomment c
        LEFT JOIN ' . JOOM_PREFIX . 'content con ON c.contentid = con.id';

//echo $sql;
$comments = $joomdb->get_results( $sql );

//var_dump( $comments );
foreach ($comments as $comment) {
    $post = get_page_by_title( $comment->title, OBJECT, 'post' );
    if ( $post ) {
        $commentdata = array(
            'comment_post_ID' => $post->ID,
            'comment_author' => $comment->name,
            'comment_author_IP' => $comment->ip,
            'comment_date' => $comment->date,
            'comment_content' => $comment->comment,
            'comment_approved' => $comment->published
        );
        //var_dump( $commentdata );
        wp_insert_comment( $commentdata );
    }
}