<?php 
/*
Template Name: Custom Blog Template
*/
?>
<?php get_header(); ?>
<!-- Html to show filter dropdown -->
<form id="year-filter">
    <label for="year">Filter by year:</label>
    <select name="year" id="year">
        <?php foreach ( custom_year_filter_get_years() as $year ): ?>
            <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
        <?php endforeach; ?>
    </select>
</form>

<!-- Query to filter post according to year with ajax -->
<div id="post-list">
    <?php
    $year = $_POST['year'];
    // $year = date( 'Y' );
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
    // $posts = get_posts( $args );
   $query = new WP_Query( $args );
   ?>
   <!-- <div id="post-list"> -->
   <?php
   if( $query->have_posts() ) :
      while( $query->have_posts() ): $query->the_post();
         echo '<h2>' . get_the_title() . '</h2>';
      endwhile;
      wp_reset_postdata();
   else :
      echo 'No posts found';
   endif;

    ?>
    </div>

<!-- Ajax code -->
<script>
jQuery(function($) {
//   ajax url should match with your live domain
    var ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>".replace('your_site_url', '/');

    $('#year').on('change', function() {
        var year = $(this).val();
        console.log('Selected Year:', year);
        $.ajax({
            url: ajax_url,
            type: 'post',
            data: {
                action: 'custom_year_filter',
                year: year,
            },
            beforeSend: function() {
                console.log('Before sending the AJAX request');
                $('#post-list').html('<p>Loading...</p>');
            },
            success: function(response) {
                console.log('Received response:', response);
                if (response.success) {
                    console.log(response.success);
                    console.log(response.data.data);
                    $('#post-list').html(response.data.data); 
                } else {
                    $('#post-list').html('<p>No posts found</p>');
                }
            },
            error: function() {
                console.log('Error occurred while sending the AJAX request');
                $('#post-list').html('<p>There was an error retrieving the posts.</p>');
            }
        });
    });
});

</script>
<?php
 get_footer();
