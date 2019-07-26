<?php

class AdminTable extends WP_List_Table {

/**
 * Constructor, we override the parent to pass our own arguments
 * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
 */
 function __construct() {
    parent::__construct( array(
   'singular'=> 'wp_list_text_link', //Singular label
   'plural' => 'wp_list_test_links', //plural label, also this well be one of the table css class
   'ajax'   => false //We won't support Ajax for this table
   ) );
 }

 function get_columns() {
    return $columns= array(
       'col_link_posts_total'=>__('Posts Total Likes'),
       'col_link_custom_posts_total'=>__('Custom Posts Total Likes'),
       'col_link_pages_total'=>__('Pages Total Likes'),
    );
 }

 function get_total_counts() {

 }

 function display_columns () {

}
}