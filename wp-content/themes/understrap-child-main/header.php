<?php
/**
 * The header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$bootstrap_version = get_theme_mod( 'understrap_bootstrap_version', 'bootstrap4' );
$navbar_type       = get_theme_mod( 'understrap_navbar_type', 'collapse' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#04273e">
	<meta name="apple-mobile-web-app-title" content="Enfants Terribles">
	<meta name="application-name" content="Enfants Terribles">
	<meta name="msapplication-TileColor" content="#04273e">
	<meta name="theme-color" content="#ffffff">
	<?php wp_head(); ?>

  <!-- Matomo Analytics Tracking - Production only -->
  <?php
    // Matomo Enhanced Setup for Enfants Terribles
    if (defined('WP_ENV') && WP_ENV === 'production') :
  ?>
    <script>
      var _paq = window._paq = window._paq || [];
      _paq.push(['trackPageView']);
      _paq.push(['enableLinkTracking']);
      _paq.push(['setTrackerUrl', '//www.enfants-terribles.de/analytics/matomo/matomo.php']);
      _paq.push(['setSiteId', '1']);
      (function() {
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.async=true; g.src='//www.enfants-terribles.de/analytics/matomo/matomo.js'; s.parentNode.insertBefore(g,s);
      })();
    </script>
  <?php endif; ?>
  <!-- Matomo Tag Manager (noscript fallback) -->
  <noscript><iframe src="//www.enfants-terribles.de/analytics/matomo/matomo.php"
  style="border:0; height:0; width:0; display:none; visibility:hidden" aria-hidden="true"></iframe></noscript>

	<?php
    if (defined('WP_ENV')) {
        if (WP_ENV === 'local') {

        } elseif (WP_ENV === 'staging') {

        } elseif (WP_ENV === 'production') {
          
            // Matomo
            ?>
            <script>
                var _paq = window._paq = window._paq || [];
                _paq.push(['trackPageView']);
                _paq.push(['enableLinkTracking']);
                (function() {
                    var u="//www.enfants-terribles.de/analytics/matomo/";
                    _paq.push(['setTrackerUrl', u+'matomo.php']);
                    _paq.push(['setSiteId', '1']);
                    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
                    g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
                })();
            </script>
            <!-- End Matomo Code -->
            <?php
        }
    } else {
        ?>
        <!-- Matomo -->
        <script>
            var _paq = window._paq = window._paq || [];
            _paq.push(['trackPageView']);
            _paq.push(['enableLinkTracking']);
            (function() {
                var u="//www.enfants.de/analytics/matomo/";
                _paq.push(['setTrackerUrl', u+'matomo.php']);
                _paq.push(['setSiteId', '1']);
                var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
                g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
            })();
        </script>
        <!-- End Matomo Code -->
        <!-- Google Tag Manager -->
        <script>
            (function(w,d,s,l,i){
                w[l]=w[l]||[];
                w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});
                var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';
                j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-TFGJJ4LQ');
        </script>
        <!-- End Google Tag Manager -->
        <!-- Hotjar Tracking Code -->
        <script>
            (function(h,o,t,j,a,r){
                h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                h._hjSettings={hjid:3541898,hjsv:6};
                a=o.getElementsByTagName('head')[0];
                r=o.createElement('script');r.async=1;
                r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                a.appendChild(r);
            })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
        </script>
        <?php
    }
    if (get_field('lyris_show', 'option')) {
        ?>
        <script id="cdc-lyris-integration" src="https://lyris.ai/assets/public/scripts/useLyris.js" data-api-key="Wi7UOYrzR6hCs9yRnZr7b210WmrVFhe9VYuaYNLzh8QvG7SOYMkvyJiqFvsAvsOrWW0GuO0z3vAOuGU2ZWc0A2JbFDy9OawgHw4T"></script>
        <?php
    }
?>
<style>
    .unicorn-embed {
      // max-width: 100%;
      height: <?php the_field('unicorn_width') ?> !important;
      overflow: hidden;
      @media (min-width: 576px) {
        height: <?php the_field('unicorn_width_sm') ?> !important;
      }
      @media (min-width: 768px)  {
        height: <?php the_field('unicorn_width_md') ?> !important;
      }
      @media (min-width: 992px)  {
        height: <?php the_field('unicorn_width_lg') ?> !important;
      }
    }
</style>

</head>

<body <?php body_class(); ?> <?php understrap_body_attributes(); ?>>
<a class="visually-hidden-custom" href="#content"><?php esc_html_e( 'Skip to content', 'understrap' ); ?></a>

<?php do_action( 'wp_body_open' ); ?>
<div class="site" id="page">


	<!-- ******************* The Navbar Area ******************* -->
	<header id="wrapper-navbar">


		<?php get_template_part( 'global-templates/navbar', $navbar_type . '-' . $bootstrap_version ); ?>

	</header><!-- #wrapper-navbar -->
