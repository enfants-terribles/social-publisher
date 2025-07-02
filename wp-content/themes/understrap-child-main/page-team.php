<?php
/**
 * Template Name: ET: Team
 *
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();
$container = get_theme_mod('understrap_container_type');
?>

<?php if (is_front_page()) : ?>
    <?php get_template_part('global-templates/hero'); ?>
<?php endif; ?>

<div class="wrapper bg-blue" id="full-width-page-wrapper">

    <div class="container-fluid bg-blue ps-0 pe-0">
        <div class="row position-relative gx-0">
        <?php echo get_the_post_thumbnail( $post->ID ); ?>
            <div class="sm d-none d-sm-block">
                <?php
                    $partner_1 = get_field('team_partner_1');
                    if( $partner_1 ): ?>
                        <div class="name"><?php echo $partner_1['name']; ?></div>
                        <div class="position"><?php echo $partner_1['position']; ?></div>
                <?php endif; ?>
            </div>
            <div class="as d-none d-sm-block">
                <?php
                    $partner_2 = get_field('team_partner_2');
                    if( $partner_2 ): ?>
                        <div class="name"><?php echo $partner_2['name']; ?></div>
                        <div class="position"><?php echo $partner_2['position']; ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="container-fluid content">
        <div class="row max-1360">
            <div class="col-lg-1"></div>
            <div class="col-lg-6">
                <h1 class="h1"><?php the_field('team_headline'); ?></h1>
                <div class="text">
                    <?php the_field('team_content'); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid team">
        <div class="row max-1360">
            <div class="col-lg-1"></div>
            <?php include('loop-templates/team-single.php'); ?>
            <div class="col-lg-1"></div>
        </div>
    </div>

    <?php if( get_field('team_sofa_headline') ): ?>
        <div class="sofa bubble"></div>
    <?php endif; ?>

    <?php if( get_field('team_sofa_headline') ): ?>
        <div class="container-fluid sofa">
            <div class="row max-1360">
            <div class="bubbles"></div>
                <div class="col-lg-1"></div>
                <div class="col-lg-6">
                    <h2><?php the_field('team_sofa_headline'); ?></h2>
                    <div class="text"><?php the_field('team_sofa_text'); ?></div>
                    <?php 
                        $link = get_field('team_sofa_link');
                        if( $link ): 
                            $link_url = $link['url'];
                            $link_title = $link['title'];
                            $link_target = $link['target'] ? $link['target'] : '_self';
                            ?>
                            <a class="btn icon arrow-right transparent" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row max-1360">
            <div class="col-lg-3"></div>
            <div class="col-lg-8">
            <?php 
                $image = get_field('team_sofa_bild');
                if( !empty( $image ) ): ?>
                                    <?php 
                        $link = get_field('team_sofa_link');
                        if( $link ): 
                            $link_url = $link['url'];
                            $link_title = $link['title'];
                            $link_target = $link['target'] ? $link['target'] : '_self';
                            ?>
                            <a href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>">
                                <img class="img-fluid" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                            </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            </div>
        </div>
    <?php endif; ?>

    <?php
    if( get_field('show_contact_footer') ) { 
        include('page-templates/footer.php');	
    } elseif ( get_field('show_contact_footer_small') ) { 
        include('page-templates/footer-small.php');	
    }
    ?>

	<?php get_footer(); ?>

</div>
