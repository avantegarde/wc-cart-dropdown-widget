<?php
/**
 * The template for the properties archive
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package spm
 */

get_header(); ?>

<?php
global $wp_query;
$args = array_merge( $wp_query->query, array(
    //'cat' => -14,
    //'post_type'     => 'property',
    'meta_key'     => 'spm_prop_availability',
    'meta_value'   => 'yes',
) );
query_posts( $args );
?>


<section id="page-header" class="bg-repeat-dark" style="background-image:url(<?php echo plugin_dir_url( __DIR__ ) . 'includes/img/bg-repeat-dark.jpg'; ?>);">
    <div class="container header-content">
        <h1 class="page-title">Property Listings</h1>
        <p class="header-subline">Live Better with SPM Property Management.</p>
    </div>
</section>

<div id="page-wrap" class="clearfix">

  <div id="primary" class="content-area clearfix">
    <!-- <section class="search-button-grid">
    <div class="button-wrap">
        <div class="col-md-4 center">
            <a href="/properties/?s=&search-type=property-search&price=&bdrms=&ltype=apt" data-button>Apartments / Condominiums</a>
        </div>
        <div class="col-md-4 center">
            <a href="/properties/?s=&search-type=property-search&price=&bdrms=&ltype=singlefam" data-button>Single-Family Homes</a>
        </div>
        <div class="col-md-4 center">
            <a href="/properties/?s=&search-type=property-search&price=&bdrms=&ltype=student" data-button>Student Properties</a>
        </div>
    </div>
    </section> -->

    <div class="container">
        <?php echo spm_get_property_search(); ?>
    </div>

    <main id="main" class="site-main container" role="main">
        <div class="row posts-grid-wrapper">

            <?php
            if (have_posts()) :
                /* Start the Loop */
                while (have_posts()) : the_post(); ?>

                    <?php
                    /**
                     * Property Fields
                     */
                    $prop_address = get_field( "spm_prop_address" );
                    $prop_city = get_field( "spm_prop_city" );
                    $prop_province = get_field( "spm_prop_province" );
                    $prop_postal_code = get_field( "spm_prop_postal_code" );
                    $prop_bedrooms = get_field( "spm_prop_bedrooms" );
                    $prop_bathrooms = get_field( "spm_prop_bathrooms" );
                    $prop_price_monthly = get_field( "spm_prop_price_monthly" );
                    // Property Image
                    $feat_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
                    $image = $feat_image[0] ? $feat_image[0] : plugin_dir_url( __DIR__ ) . 'includes/img/default.jpg';
                    // parking
                    $prop_parking = get_field_object('spm_prop_parking');
                    $parking_value = $prop_parking['value'];
                    $parking_label = $prop_parking['choices'][ $parking_value ];
                    if ($parking_value == 'yes') {
                        $parking_string = 'Parking Included';
                    } else {
                        $parking_string = 'Parking Not Included';
                    }
                    // Date Available
                    $prop_date_available = get_field( "spm_prop_date_available", false, false);
                    $prop_date = new DateTime($prop_date_available);
                    $prop_av_end_date = $prop_date->format('Ymd');
                    if ( $prop_av_end_date <= date('Ymd') ) {
                        $availability_string = 'Available Now';
                    } else {
                        $availability_string = 'Available ' . $prop_date->format('F j, Y');
                    }
                    // Availability
                    $prop_availability = get_field_object('spm_prop_availability');
                    $availability_value = $prop_availability['value'];
                    $availability_label = $prop_availability['choices'][ $availability_value ];
                    if ($availability_value == 'yes') {
                        $availability = '<span class="available">Available</span>';
                    } else {
                        $availability = '<span class="rented">Rented</span>';
                    }
                    ?>
                    <div class="listing-item col-sm-6 col-md-4">
                        <article id="listing-<?php echo $post->ID; ?>" class="listing-<?php echo $post->ID; ?> listing-card">
                            <header class="listing-header" style="background-image:url(<?php echo $image; ?>);">
                                <a href="<?php the_permalink(); ?>">
                                    <?php echo $availability; ?>
                                    <div class="details">
                                        <h1 class="listing-title"><?php the_title(); ?></h1>
                                        <p class="listing-address"><?php echo $prop_city . ', ' . $prop_province; ?></p>
                                        <p class="price"><?php echo '$' . $prop_price_monthly . '/month'; ?></p>
                                    </div>
                                </a>
                            </header><!-- .entry-header -->

                            <div class="listing-content">
                                <ul class="arrows listing-info">
                                    <li><i class="fa fa-bed" aria-hidden="true"></i> <?php echo $prop_bedrooms; ?> Bedroom</li>
                                    <li><i class="fa fa-bath" aria-hidden="true"></i> <?php echo $prop_bathrooms; ?> Bathroom</li>
                                    <li><i class="fa fa-car" aria-hidden="true"></i> <?php echo $parking_string; ?></li>
                                    <li><i class="fa fa-home" aria-hidden="true"></i> <strong><?php echo $availability_string; ?></strong></li>
                                </ul>
                            </div><!-- .entry-content -->
                            <div class="listing-footer">
                                <a href="<?php the_permalink(); ?>" class="arrow" data-button="black">View Listing</a>
                            </div>
                        </article>
                    </div>
                <?php endwhile;

            else : ?>
                <p class="center">Sorry! Looks like there are no listings found within your criteria. Try broadening your search and try again.</p>
            <?php endif; ?>
        </div><!-- .row -->
    </main><!-- #main -->

    <div class="pagination">
      <?php
      global $wp_query;
      $big = 999999999; // need an unlikely integer
      $translated = __( 'Page', 'mytextdomain' ); // Supply translatable string
      echo paginate_links( array(
        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format' => '?paged=%#%',
        'current' => max( 1, get_query_var('paged') ),
        'total' => $wp_query->max_num_pages,
        'before_page_number' => '<span class="screen-reader-text">'.$translated.' </span>',
        'prev_text' => '<',
        'next_text' => '>'
      ) );
      ?>
    </div>

  </div><!-- #primary -->

    <section class="bg-repeat-light rented-properties">
        <div class="container">
            <div class="row">
                <div class="slideset-listings">
                    <h3 class="section-title center">Rented Properties</h3>
                    <p class="sub-line center"><em>Nobody Does It Better than Us</em></p>
                    <?php echo do_shortcode('[property-list availability="no" posts="-1" slideset="yes"]'); ?>
                </div>
            </div>
        </div>
    </section>

  <?php // get_sidebar(); ?>
</div><!-- #page-wrap -->


<?php get_footer(); ?>

<!-- <span id="inifiniteLoader"><i class="fa fa-circle-o-notch"></i> Loading...</span>
<script type="text/javascript">
  jQuery(document).ready(function($) {
      var count = 2;
      var total = <?php // echo $wp_query->max_num_pages; ?>;
      $(window).scroll(function(){
          if  ($(window).scrollTop() == $(document).height() - $(window).height()){
              if (count > total){
                  return false;
              }else{
                  loadArticle(count);
              }
              count++;
          }
      });

      function loadArticle(pageNumber){
          $('span#inifiniteLoader').addClass('active').show('fast');
          $.ajax({
              url: "<?php // bloginfo('wpurl') ?>/wp-admin/admin-ajax.php",
              type:'POST',
              data: "action=infinite_scroll&page_no="+ pageNumber + '&loop_file=loop',
              success: function(html) {
                  $('span#inifiniteLoader').removeClass('active').fadeOut('1000');
                  var $newItems = $(html);
                  var $grid = $('.posts-grid-wrapper');
                  $grid.append($newItems);
                  colMatchHeight();
                  //Use the line below if you have masonry blogroll
                  //$grid.append( $newItems ).masonry( 'appended', $newItems );
              }
          });
          return false;
      }

  });// END document.ready
</script> -->
