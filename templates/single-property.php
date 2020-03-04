<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Ferus_Core
 */

get_header(); ?>

<?php
$feat_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
$image = $feat_image[0] ? $feat_image[0] : plugin_dir_url( __DIR__ ) . 'includes/img/default.jpg';
$bg_class = $feat_image[0] ? '' : 'bg-repeat-dark';
?>
<section id="page-header" class="bg-repeat-dark" style="background-image:url(<?php echo plugin_dir_url( __DIR__ ) . 'includes/img/bg-repeat-dark.jpg'; ?>);"></section>


    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <?php while (have_posts()) : the_post(); ?>

                <?php
                $prop_address = get_field( "spm_prop_address" );
                $prop_city = get_field( "spm_prop_city" );
                $prop_province = get_field( "spm_prop_state_prov" );
                $prop_postal_code = get_field( "spm_prop_postal_code" );
                $prop_bedrooms = get_field( "spm_prop_bedrooms" );
                $prop_bathrooms = get_field( "spm_prop_bathrooms" );
                $prop_price_monthly = get_field( "spm_prop_price_monthly" );
                $prop_description = get_field( "spm_prop_description" );
                // parking
                $prop_parking = get_field_object('spm_prop_parking');
                $parking_value = $prop_parking['value'];
                $parking_label = $prop_parking['choices'][ $parking_value ];
                if ($parking_value == 'yes') {
                    $parking_string = 'Parking Included';
                } else {
                    $parking_string = 'Parking Not Included';
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
                // Date Available
                $prop_date_available = get_field( "spm_prop_date_available", false, false);
                $prop_date = new DateTime($prop_date_available);
                $prop_av_end_date = $prop_date->format('Ymd');
                if ( $prop_av_end_date <= date('Ymd') && $availability_value != 'yes' ) {
                    $availability_string = 'Rented';
                } else if ( $prop_av_end_date <= date('Ymd') && $availability_value === 'yes' ) {
                    $availability_string = 'Available Now';
                } else {
                    $availability_string = 'Available ' . $prop_date->format('F j, Y');
                }
                ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
                    <div class="container">
                        <div class="row">
                            <!-- START Gallery -->
                            <aside id="listing-gallery" class="col-md-8">
                                <?php $images = get_field('spm_prop_gallery'); ?>
                                <div id="gallery-view" style="background-image:url(<?php echo $image; ?>);"></div>
                                <div id="gallery-nav" class="prop-gallery-nav">
                                    <div class="thumb-item" data-image="<?php echo $image; ?>" style="background-image:url(<?php echo $image; ?>);"></div>
                                    <?php if ($images): ?>
                                        <?php foreach ($images as $image): ?>
                                            <div class="thumb-item" data-image="<?php echo $image['url']; ?>" data-caption="<?php echo $image['caption']; ?>" style="background-image:url(<?php echo $image['sizes']['thumbnail']; ?>);"></div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </aside>
                            <!-- END Gallery -->
                            <div class="col-md-4 listing-details">
                                <div class="panel">
                                    <div class="panel-content">
                                        <header class="listing-header">
                                            <h1 class="listing-title"><?php the_title(); ?></h1>
                                            <p class="listing-address"><?php echo $prop_address . '<br>' .  $prop_city . ', ' . $prop_province; ?></p>
                                            <p class="price"><?php echo '$' . $prop_price_monthly . '/month'; ?></p>
                                        </header><!-- .entry-header -->

                                        <div class="entry-content">
                                            <ul class="spm-icon-list listing-info">
                                                <li data-icon="bed"><?php echo $prop_bedrooms; ?> Bedroom</li>
                                                <li data-icon="bath"><?php echo $prop_bathrooms; ?> Bathroom</li>
                                                <li data-icon="car"><?php echo $parking_string; ?></li>
                                                <li data-icon="house"><?php echo $availability_string; ?></li>
                                            </ul>
                                            <?php if( have_rows('spm_prop_amenities') ): ?>
                                                <ul class="spm-icon-list listing-amenities">
                                                <?php while ( have_rows('spm_prop_amenities') ) : the_row(); ?>

                                                    <li><?php the_sub_field('spm_prop_amenity_item'); ?></li>

                                                <?php endwhile; ?>
                                                </ul>
                                            <?php else : ?>
                                                <!-- no items found -->
                                            <?php endif; ?>
                                            <p class="center"><a href="https://limestoneresidential.ca/apply/" target="blank" data-button="arrow">Apply Now!</a></p>
                                            <p class="center"><a href="mailto:rentals@limestonepm.ca?subject=Seeking%20information%20about%20property%20listing%20-%20<?php echo urlencode(the_title()); ?>" data-button>Seek More Info</a></p>

                                            <?php // the_content(); ?>

                                        </div><!-- .entry-content -->
                                    </div>
                                </div>
                            </div>

                        </div><!-- .row -->
                    </div><!-- .container -->
                    <?php if ($prop_description != '') : ?>
                    <section>
                        <div class="container">
                            <div class="panel">
                                <div class="panel-content">
                                    <?php echo $prop_description; ?>
                                </div>
                            </div>
                        </div>
                    </section>
                    <?php endif; ?>
                </article><!-- #post-## -->


            <?php endwhile; // End of the loop. ?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();
