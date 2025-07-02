<?php
/**
 * Template Name: SEA LinkedIn
 * Template Post Type: post
 */
 
// Your template code goes here.

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
$container = get_theme_mod( 'understrap_container_type' );
?>

<div class="wrapper" id="full-width-page-wrapper">

    <div class="container-fluid hero_cont gx-4 bg-blue">
        <div class="row">
            <div class="col ps-0"><?php if ( function_exists( 'wp_breadcrumb' ) ) { wp_breadcrumb(); } ?></div>
        </div>
        <div class="row max-1800 hero">
            <div class="col-md-9">
                <h1 class="headline"><?php echo wp_kses_post( get_field('hero_headline') ); ?></h1>
            </div>
            <div class="col-md-6 offset-md-6">
                <div class="subline font-26 mt-5"><?php the_field('hero_subline'); ?></div>
                <?php 
                $link = get_field('sea_linkedin_cta');
                if( $link ): 
                    $link_url = $link['url'];
                    $link_title = $link['title'];
                    $link_target = $link['target'] ? $link['target'] : '_self';
                    ?>
                    <a class="btn btn-primary icon arrow-right green mt-3" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>

	<div class="container-fluid gx-4" id="container-aplha">
        <div class="row max-1800 text_cont">
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
                <h4 class="headline">
                    <?php the_field('sea_linkedin_tb1_headline'); ?>
                </h4>
                <div class="w-800 ls--2 mb-5"><?php the_field('sea_linkedin_tb1_text'); ?></div>
            </div>    
            <div class="col-lg-2"></div>
                <?php
                if( have_rows('sea_linkedin_textblocke_one') ): 
                    $count = 0; // Initialize a counter.
                    while( have_rows('sea_linkedin_textblocke_one') ): the_row(); 
                        // Get the content from the ACF field.
                        $icon = wp_kses_post( get_sub_field('icon') );
                        $headline = wp_kses_post( get_sub_field('headline') );
                        $text_block = wp_kses_post( get_sub_field('text') ); ?>
                    <?php if($count % 2 == 0): // If the counter is even, render in the left column. ?>
                        <div class="row max-1800 mb-4">
                            <div class="col-12 col-lg-4 offset-lg-2 ps-0 pe-4">
                                <div class="d-flex align-items-start">
                                    <img src="<?php echo $icon; ?>" alt="">
                                    <div class="copy">
                                        <h5 class="headline font-26 w-800 ls--2 mb-2"><?php echo $headline; ?></h5>
                                        <?php echo $text_block; ?>
                                    </div>
                                </div>
                            </div>
                    <?php else: // If the counter is odd, render in the right column and close the row. ?>
                            <div class="col-12 col-lg-4 ps-0">
                                <div class="d-flex align-items-start">
                                    <img src="<?php echo $icon; ?>" alt="">
                                    <div class="copy">
                                        <h5 class="headline font-26 w-800 ls--2 mb-2"><?php echo $headline; ?></h5>
                                        <?php echo $text_block; ?>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- Closing the row -->
                    <?php endif; ?>
                <?php 
                    $count++; // Increment the counter.
                    endwhile; 
                endif; 

                // If the text_blocks ends with an odd number, close the last opened row.
                if($count % 2 != 0) echo '</div>'; 
                ?>
        </div>
    </div>
    
    <div class="container-fluid gx-4 hero_cont" id="container-beta">
        <div class="row max-1800 text_cont">
            <div class="col-md-9">
                <h1 class="headline"><?php the_field('sea_linkedin_textblocke_two_headline'); ?></h1>
            </div>
            <div class="col-md-6 offset-md-6">
                <div class="subline font-26 mt-5"><?php the_field('sea_linkedin_textblocke_two_subline'); ?></div>
                <?php 
                $link = get_field('sea_linkedin_textblocke_two_cta');
                if( $link ): 
                    $link_url = $link['url'];
                    $link_title = $link['title'];
                    $link_target = $link['target'] ? $link['target'] : '_self';
                    ?>
                    <a class="btn btn-primary icon arrow-right green mt-3" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
                <?php endif; ?>
            </div>
        </div>
        <div class="row max-1800 ps-3 pe-2 info_box-headline">
            <div class="col-lg-12 ps-0">
                <div class="headline w-800 ls--2 pb-4">Unser Check umfasst:</div>
            </div>
        </div>
        <div class="row max-1800 ps-lg-3 pe-lg-2">
            <div class="col-lg-12 info_box">
                <!-- <div class="row max-1180"> -->
                <?php
                if( have_rows('sea_linkedin_textblocke_three') ): 
                    $count = 0; // Initialize a counter.
                    while( have_rows('sea_linkedin_textblocke_three') ): the_row(); 
                        // Get the content from the ACF field.
                        $icon = wp_kses_post( get_sub_field('icon') );
                        $headline = wp_kses_post( get_sub_field('headline') );
                        $text_block = wp_kses_post( get_sub_field('text') ); ?>
                    <?php if($count % 2 == 0): // If the counter is even, render in the left column. ?>
                        <div class="row max-1800 mb-4 p-0">
                            <div class="col-12 col-lg-6 ps-lg-0 pe-5">
                                <div class="d-flex align-items-start">
                                    <img src="<?php echo $icon; ?>" alt="">
                                    <div class="copy">
                                        <h5 class="headline font-26 w-800 ls--2 mb-2"><?php echo $headline; ?></h5>
                                        <?php echo $text_block; ?>
                                    </div>
                                </div>
                            </div>
                    <?php else: // If the counter is odd, render in the right column and close the row. ?>
                            <div class="col-12 col-lg-6 ps-lg-3">
                                <div class="d-flex align-items-start">
                                    <img src="<?php echo $icon; ?>" alt="">
                                    <div class="copy">
                                        <h5 class="headline font-26 w-800 ls--2 mb-2"><?php echo $headline; ?></h5>
                                        <?php echo $text_block; ?>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- Closing the row -->
                    <?php endif; ?>
                <?php 
                    $count++; // Increment the counter.
                    endwhile; 
                endif; 

                // If the text_blocks ends with an odd number, close the last opened row.
                if($count % 2 != 0) echo '</div>'; 
                ?>
                <!-- </div> -->
            </div>
        </div>
    </div>

    <div class="container-fluid gx-4" id="container-gamma">
        <div class="row max-1800 text_cont">
            <div class="col-lg-1 col-xl-1"></div>
            <div class="col-lg-4 col-xl-4 left_content">
                <div class="subline"><?php the_field('sea_linkedin_textblocke_four_subline_links'); ?></div>
                <div class="headline"><?php the_field('sea_linkedin_textblocke_four_headline'); ?></div>
            </div>
            <div class="col-lg-1"></div>
            <div class="col-lg-5 right_content mt-5 mt-lg-0">
                <?php if( have_rows('sea_linkedin_textblocke_four_rep') ): ?>
                    <?php while( have_rows('sea_linkedin_textblocke_four_rep') ): the_row(); 
                        $image = get_sub_field('image');
                        $preline = get_sub_field('preline');
                        $headline = get_sub_field('headline');
                        $text = get_sub_field('text');
                        ?>
                        <div class="d-flex">
                            <img src="<?php echo $image; ?>" alt="">
                            <div class="copy">
                                <div class="preline"><?php echo $preline; ?></div>
                                <div class="headline"><?php echo $headline; ?></div>
                            </div>
                        </div>
                        <?php echo $text; ?>

                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
            <div class="col-lg-1"></div>
        </div>
    </div>

    <div class="container-fluid bg-blue" id="container-delta">
        <div class="row max-1800">
            <div class="col-lg-1"></div>
            <div class="col-lg-4 left">
                <div class="headline"><?php the_field('sea_linkedin_textblocke_five_headline');?></div>
                <div class="text"><?php the_field('sea_linkedin_textblocke_five_text');?></div>
                <?php 
                $link = get_field('sea_linkedin_textblocke_five_cta');
                if( $link ): 
                    $link_url = $link['url'];
                    $link_title = $link['title'];
                    $link_target = $link['target'] ? $link['target'] : '_self';
                    ?>
                    <a class="btn btn-primary icon arrow-right green mt-3" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
                <?php endif; ?>
            </div>
            <div class="col-lg-6">
                <img src="<?php the_field('sea_linkedin_textblocke_five_image');?>" alt="">
            </div>
            <div class="col-lg-1"></div>
        </div>
    </div>

    <?php if( have_rows('before_after_sections') ): ?>
        <div class="container-fluid bg-white" id="container-epsilon">
            <div class="row max-1800 intro">
                <div class="col-lg-1"></div>
                <div class="col-lg-5">
                    <h4><?php the_field('before_after_headline') ?></h4>
                    <p><?php the_field('before_after_text') ?></p>
                </div>
            </div>
            <div class="swiper">
                <div class="swiper-wrapper">
                    <?php while( have_rows('before_after_sections') ): the_row(); ?>
                        <div class="row max-1800 slider_cont wrap_<?php echo get_row_index(); ?> swiper-slide">
                            <div class="headlines p-0">
                                <div class="label-before"><?php the_sub_field('projekt'); ?><span>vor und nach Relaunch</span></div>
                            </div>
                            <div class="container-slider">

                                <div class="image-container">
                                    <img
                                        class="image-before slider-image"
                                    src="<?php the_sub_field('before'); ?>"
                                        alt="color photo"
                                    />
                                    <img
                                        class="image-after slider-image"
                                        src="<?php the_sub_field('after'); ?>"
                                        alt="black and white"
                                    />
                                </div>
                                <!-- step="10" -->
                                <input
                                type="range"
                                min="0"
                                max="100"
                                value="50"
                                aria-label="Percentage of before photo shown"
                                class="slider-<?php echo get_row_index(); ?>"
                                />
                                <div class="slider-line" aria-hidden="true"></div>
                                <div class="slider-button" aria-hidden="true">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16.0503 12.0498L21 16.9996L16.0503 21.9493L14.636 20.5351L17.172 17.9988L4 17.9996V15.9996L17.172 15.9988L14.636 13.464L16.0503 12.0498ZM7.94975 2.0498L9.36396 3.46402L6.828 5.9988L20 5.99955V7.99955L6.828 7.9988L9.36396 10.5351L7.94975 11.9493L3 6.99955L7.94975 2.0498Z" fill="white"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <script>
                            // const container = document.querySelector("#container-epsilon wrap_<?php echo get_row_index(); ?>");
                            document.querySelector("#container-epsilon .wrap_<?php echo get_row_index(); ?>")
                            .addEventListener("input", (e) => {
                                document.querySelector(".wrap_<?php echo get_row_index(); ?>").style.setProperty("--position", `${e.target.value}%`);
                            });
                        </script>
                        <style>
                                #container-epsilon .slider-<?php echo get_row_index(); ?> {
                                position: absolute;
                                inset: 0;
                                cursor: pointer;
                                opacity: 0;
                                /* for Firefox */
                                width: 100%;
                                height: 100%;
                                }
                        </style>
                    <?php endwhile; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    <?php endif; ?>                

    <div class="container-fluid bg-blue" id="container-zeta">
        <div class="row max-1800">
            <div class="col-lg-3"></div>
            <div class="col-lg-9">
                <div class="d-flex flex-column flex-lg-row">
                    <div class="headline">
                        <?php the_field('sea_linkedin_textblocke_six_headline'); ?>
                    </div>
                    <?php 
                    $link = get_field('sea_linkedin_textblocke_six_link');
                    if( $link ): 
                        $link_url = $link['url'];
                        $link_title = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                        ?>
                        <a class="btn btn-primary icon arrow-right green mt-5 mt-lg-0 ms-lg-5" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
if( get_field('show_contact_footer') ) { 
    include('page-templates/footer.php');	
} elseif ( get_field('show_contact_footer_small') ) { 
    include('page-templates/footer-small.php');	
}
?>

<?php get_footer(); ?>