<?php
/**
 * アイキャッチ画像を使用可能にする
 */
add_theme_support( 'post-thumbnails' );

/**
 * カスタムメニュー機能を使用可能にする
 */
add_theme_support( 'menus' );

/**
 * コメント投稿フォームの「名前」「メールアドレス」「ウェブサイト」の項目を削除する
 */
add_filter('comment_form_default_fields', 'my_comment_form_default_fields');
function my_comment_form_default_fields( $args ) {
    $args['author'] = ''; // 「名前」を削除
    $args['email'] = ''; // 「メールアドレス」を削除
    $args['url'] = ''; // 「ウェブサイト」を削除
    return $args;
}

/**
 * <head>内にRSSのlink要素を出力する
 */
add_theme_support( 'automatic-feed-links' );

/**
 * RSSに配信する文字数を設定する
 */
add_filter('excerpt_mblength', 'my_excerpt_mblength');
function my_excerpt_mblength( $length ) {
     return 50; //抜粋に出力する文字数
}

/**
 * 残りの部分がある旨の表示を変更
 */

add_filter('excerpt_more', 'my_excerpt_more');
function my_excerpt_more( $more ) {
    return  '[....]';
}


/**
 * RSSにアイキャッチ画像を表示
 */
function rss_post_thumbnail( $content) {
    global $post;
    if (has_post_thumbnail( $post->ID)) {
        $content = '<p>' . get_the_post_thumbnail($post->ID) .'</p>' . $content;
    }
    return $content;
}
add_filter( 'the_excerpt_rss',  'rss_post_thumbnail');
add_filter( 'the_content_feed', 'rss_post_thumbnail');


//RSS 2.0を停止
remove_action('do_feed_rss2','do_feed_rss2', 10, 1);

add_action('pre_get_posts', 'my_pre_get_posts');
function my_pre_get_posts($query) {
  //管理画面、メインクエリ以外には設定しない
  if ( is_admin() || ! $query->is_main_query() ){
    return;
  }
  //トップページの場合
  if ($query->is_home()){
    $query->set( 'posts_per_page', 3);
    return;
  }
}