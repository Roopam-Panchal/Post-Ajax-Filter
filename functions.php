// This Code for only show filter top of the template
// =======================================================

function custom_year_filter_get_years() {
    global $wpdb;

    $years = $wpdb->get_col( "
        SELECT DISTINCT YEAR( post_date )
        FROM {$wpdb->posts}
        WHERE post_type = 'post' AND post_status = 'publish'
        ORDER BY post_date DESC
    " );

    return $years;
}

// This code for the custom template
add_action( 'wp_ajax_nopriv_custom_year_filter', 'custom_year_filter' );
add_action( 'wp_ajax_custom_year_filter', 'custom_year_filter' );
function custom_year_filter() {
    $year = $_POST['year'];
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'date_query' => array(
            array(
                'year' => $year
            )
        )
    );
    $query = new WP_Query( $args );
    $output = '';
    if( $query->have_posts() ) :
        while( $query->have_posts() ): $query->the_post();
            $output .= '<h2>' . get_the_title() . '</h2>';
        endwhile;
        wp_reset_postdata();
        wp_send_json_success( array( 'data' => $output ) );
    else :
        wp_send_json_error();
    endif;
}
