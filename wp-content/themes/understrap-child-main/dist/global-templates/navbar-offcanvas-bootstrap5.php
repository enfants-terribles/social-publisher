<?php
/**
 * Header Navbar (bootstrap5)
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );
?>

<nav id="main-nav" class="navbar navbar-expand-md navbar-light bg-white justify-content-end" aria-labelledby="main-nav-label">

	<div id="main-nav-label" class="screen-reader-text">
		<?php esc_html_e( 'Main Navigation', 'understrap' ); ?>
</div>


	<div class="<?php echo esc_attr( $container ); ?>">

		<!-- Your site title as branding in the menu -->
		<?php if ( ! has_custom_logo() ) { ?>

<?php if ( is_front_page() && is_home() ) : ?>

	<h1 class="navbar-brand mb-0"><a rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" itemprop="url"><?php bloginfo( 'name' ); ?></a></h1>

<?php else : ?>

<a class="navbar-brand" aria-label="Link zur Homepape" rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" itemprop="url">
	<svg width="169" height="52" viewBox="0 0 169 52" xmlns="http://www.w3.org/2000/svg">
	<title>Enfants Terribles Home</title>
			<defs>
					<filter x="-.8%" y="-25%" width="101.7%" height="149%" filterUnits="objectBoundingBox" id="ooknt4h42a">
							<feOffset dy="-2" in="SourceAlpha" result="shadowOffsetOuter1"/>
							<feGaussianBlur stdDeviation="2" in="shadowOffsetOuter1" result="shadowBlurOuter1"/>
							<feColorMatrix values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.0623018569 0" in="shadowBlurOuter1" result="shadowMatrixOuter1"/>
							<feMerge>
									<feMergeNode in="shadowMatrixOuter1"/>
									<feMergeNode in="SourceGraphic"/>
							</feMerge>
					</filter>
			</defs>
			<g filter="url(#ooknt4h42a)" transform="translate(-1240 1)" fill="none" fill-rule="evenodd">
					<path d="M1327.895 18.376c3.096 0 5.359.675 6.79 2.025 1.46 1.379 2.19 3.667 2.19 6.866 0 .176-.007.476-.022.902l-.022.682-.262 6.25-.01.217c-.043 1.557.354 2.336 1.192 2.336.555 0 .964-.264 1.227-.793h.088l-.008.248a3.415 3.415 0 0 1-.343 1.337c-.788 1.614-2.22 2.42-4.293 2.42-1.314 0-2.424-.373-3.33-1.122-.904-.748-1.386-1.797-1.445-3.147-.438 1.291-1.19 2.326-2.256 3.103-1.066.778-2.3 1.167-3.701 1.167-1.694 0-3.11-.55-4.25-1.65-1.139-1.101-1.708-2.62-1.708-4.556 0-2.054.737-3.675 2.212-4.864 1.475-1.188 3.19-1.782 5.147-1.782 1.986 0 3.534.396 4.644 1.188l.087-4.71v-.22l-.002-.301c-.063-3.173-1.128-4.76-3.195-4.76-.438 0-.709.03-.81.088-.103.059-.227.206-.373.44l-3.548 6.602h-.088l-2.76-6.293.579-.203c2.885-.98 5.642-1.47 8.27-1.47zm48.187-7.042v7.394h5.388v.88h-5.388v14.7l.005.267c.034.87.236 1.55.608 2.044.409.543.978.815 1.709.815 1.11 0 1.956-.543 2.54-1.629l.088.044-.062.299a6.324 6.324 0 0 1-1.887 3.266c-1.037.969-2.52 1.453-4.447 1.453-3.796 0-5.695-2.01-5.695-6.03V19.608h-2.409v-.88h1.971l.199-.006c.513-.032.9-.191 1.16-.478l6.132-6.91h.088zm15.639 7.042c1.139 0 2.274.132 3.409.348l.001 1.028c-.574-.236-1.463-.43-2.666-.584h-.613c-1.022 0-1.869.264-2.54.793-.672.528-1.008 1.29-1.008 2.288 0 .91.314 1.643.942 2.2.627.558 1.657 1.16 3.088 1.805l2.804 1.233c1.577.675 2.781 1.467 3.614 2.376.832.91 1.248 2.142 1.248 3.698 0 2.318-.89 4.115-2.672 5.391-1.782 1.276-4.162 1.915-7.14 1.915-2.804 0-5.491-.455-8.06-1.365l2.408-6.822h.088l3.505 6.822c.175.382.481.572.92.572h1.007c1.256 0 2.256-.293 3-.88.745-.587 1.118-1.364 1.118-2.333 0-.85-.3-1.525-.898-2.024-.599-.499-1.701-1.115-3.308-1.849l-2.978-1.408c-2.833-1.35-4.25-3.433-4.25-6.25 0-2.113.803-3.8 2.41-5.061 1.606-1.262 3.796-1.893 6.57-1.893zm-119.24-8.803v10.652h-.088l-5.651-9.067-.092-.134a1.347 1.347 0 0 0-1.135-.57h-4.512l-.13.003c-.247.012-.408.063-.483.15-.088.103-.132.346-.132.727v13.248h2.979l.162-.02c.37-.064.696-.263.977-.596l3.943-4.578h.087v11.224h-.087l-3.943-4.578-.133-.134c-.317-.292-.666-.438-1.05-.438h-2.935v13.292l.003.155c.01.293.054.483.129.571.087.103.292.154.613.154h5.038l.186-.006c.542-.037.918-.24 1.128-.61l5.432-9.33h.087v10.827H1249v-.088l2.935-2.773.092-.103c.202-.25.302-.568.302-.954V13.491l-.006-.161c-.029-.366-.158-.665-.388-.896L1249 9.661v-.088h23.48zm20.019 8.803c2.19 0 3.876.792 5.06 2.377 1.182 1.584 1.773 3.888 1.773 6.91 0 1.056-.022 2.81-.065 5.26-.044 2.45-.066 3.924-.066 4.423 0 .44.131.792.394 1.056l2.19 2.025v.088h-12.31v-.088l2.104-2.025.104-.118c.193-.244.29-.542.29-.894v-.054l.066-3.753c.043-2.45.065-4.086.065-4.908 0-2.2-.233-3.858-.7-4.973-.468-1.115-1.344-1.673-2.629-1.673s-2.365.646-3.242 1.937v13.38l.007.17c.028.385.158.68.388.886l2.146 2.025v.088h-12.266v-.088l2.147-2.025.092-.094c.202-.23.302-.552.302-.962V22.117l-.008-.184a1.54 1.54 0 0 0-.386-.916l-2.147-2.2v-.089h9.725v4.666l.109-.291c1.226-3.151 3.512-4.727 6.857-4.727zm64.438 0c2.19 0 3.877.792 5.06 2.377 1.183 1.584 1.774 3.888 1.774 6.91 0 1.056-.022 2.81-.065 5.26-.044 2.45-.066 3.924-.066 4.423 0 .44.131.792.394 1.056l2.19 2.025v.088h-12.31v-.088l2.104-2.025.104-.118c.193-.244.29-.542.29-.894v-.054l.066-3.753c.043-2.45.065-4.086.065-4.908 0-2.2-.233-3.858-.7-4.973-.468-1.115-1.344-1.673-2.629-1.673s-2.366.646-3.242 1.937v13.38l.006.17c.03.385.159.68.389.886l2.146 2.025v.088h-12.266v-.088l2.147-2.025.092-.094c.201-.23.302-.552.302-.962V22.117l-.008-.184a1.54 1.54 0 0 0-.386-.916l-2.147-2.2v-.089h9.725v4.666l.109-.291c1.226-3.151 3.512-4.727 6.856-4.727zm-51.572-9.243c2.774 0 4.877.756 6.308 2.267 1.374 1.45 2.088 3.76 2.143 6.928l.003.4h2.322v.88h-2.322v17.738c0 .385.1.702.302.953l.093.103 2.146 2.025v.088h-13.58v-.088l3.242-2.245c.292-.206.474-.367.547-.484.061-.098.097-.257.107-.477l.003-.14V19.609h-5.344v-.88h5.344v-4.71c0-2.728-.526-4.092-1.577-4.092-.205 0-.35.029-.438.088-.073.048-.156.169-.25.36l-.057.124-3.066 6.206h-.088l-2.76-6.602c2.337-.646 4.644-.969 6.922-.969zm22.748 20.246c-1.11 0-1.979.426-2.606 1.277-.628.85-.942 1.936-.942 3.257 0 1.173.24 2.09.723 2.75.481.66 1.102.99 1.861.99.993 0 1.826-.513 2.497-1.54v-.044l.088-6.426-.264-.08a5.067 5.067 0 0 0-1.357-.184z" fill="#04273E" fill-rule="nonzero"/>
			</g>
	</svg>
</a>

<?php endif; ?>


<?php
} else {
	the_custom_logo();
}
?>
		<div class="offcanvas offcanvas-end skew vertical" tabindex="-1" id="navbarNavOffcanvas">

			<div class="offcanvas-header justify-content-end" style="height: 100px;">
			
			</div>
			<!-- The WordPress Menu goes here -->
			<?php
			wp_nav_menu(
				array(
					'theme_location'  => 'primary',
					'container_class' => 'offcanvas-body',
					'container_id'    => '',
					'menu_class'      => 'navbar-nav flex-grow-1 pe-2 ',
					'fallback_cb'     => '',
					'menu_id'         => 'main-menu',
					'depth'           => 2,
					'walker'          => new Understrap_WP_Bootstrap_Navwalker(),
				)
			);
			?>

			<div class="d-inline-block text-end">
			</div>
		</div>

	<div class="fast-contact">
			<a class="phone" href="tel:+491773165739" aria-label="Anruf tätigen">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M21 16.42V19.9561C21 20.4811 20.5941 20.9167 20.0705 20.9537C19.6331 20.9846 19.2763 21 19 21C10.1634 21 3 13.8366 3 5C3 4.72371 3.01545 4.36687 3.04635 3.9295C3.08337 3.40588 3.51894 3 4.04386 3H7.5801C7.83678 3 8.05176 3.19442 8.07753 3.4498C8.10067 3.67907 8.12218 3.86314 8.14207 4.00202C8.34435 5.41472 8.75753 6.75936 9.3487 8.00303C9.44359 8.20265 9.38171 8.44159 9.20185 8.57006L7.04355 10.1118C8.35752 13.1811 10.8189 15.6425 13.8882 16.9565L15.4271 14.8019C15.5572 14.6199 15.799 14.5573 16.001 14.6532C17.2446 15.2439 18.5891 15.6566 20.0016 15.8584C20.1396 15.8782 20.3225 15.8995 20.5502 15.9225C20.8056 15.9483 21 16.1633 21 16.42Z" fill=""/>
				</svg>
			</a>
			<a class="mail" href="mailto:hello@enfants.de" aria-label="E-Mail schreiben">
				<svg width="22" height="18" viewBox="0 0 22 18" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M21 0C21.5523 0 22 0.44772 22 1V17.0066C22 17.5552 21.5447 18 21.0082 18H2.9918C2.44405 18 2 17.5551 2 17.0066V16H20V4.3L12 11.5L2 2.5V1C2 0.44772 2.44772 0 3 0H21ZM8 12V14H0V12H8ZM5 7V9H0V7H5ZM19.5659 2H4.43414L12 8.8093L19.5659 2Z" fill=""/>
				</svg>			
			</a>
			<div class="dark-light d-none d-sm-block invisible">
				<i class='bx bx-moon moon'>
					<svg height="20" viewBox="0 0 20 20" width="20" xmlns="http://www.w3.org/2000/svg"><path d="m10.25 20c-2.738 0-5.312-1.066-7.248-3.002s-3.002-4.51-3.002-7.248c0-2.251.723-4.375 2.09-6.143.655-.847 1.439-1.585 2.331-2.194.899-.614 1.888-1.083 2.938-1.392.192-.057.399.007.527.161s.151.369.06.547c-.645 1.257-.945 2.455-.945 3.772 0 4.687 3.813 8.5 8.5 8.5 1.317 0 2.515-.3 3.772-.945.178-.091.393-.068.547.06s.217.335.161.527c-.31 1.05-.778 2.039-1.392 2.938-.609.892-1.347 1.676-2.194 2.331-1.768 1.367-3.893 2.09-6.143 2.09zm-3.68-18.635c-3.374 1.445-5.57 4.689-5.57 8.385 0 5.1 4.15 9.25 9.25 9.25 3.696 0 6.94-2.197 8.385-5.57-1.024.383-2.058.57-3.135.57-2.538 0-4.923-.988-6.717-2.782s-2.783-4.18-2.783-6.718c0-1.077.188-2.111.57-3.135z"/></svg>
				</i>
				<i class='bx bx-sun sun'>
					<svg height="20" viewBox="0 0 20 20" width="20" xmlns="http://www.w3.org/2000/svg"><path d="m12.5 18c-3.03756612 0-5.5-2.4624339-5.5-5.5 0-3.03756612 2.46243388-5.5 5.5-5.5 3.0375661 0 5.5 2.46243388 5.5 5.5 0 3.0375661-2.4624339 5.5-5.5 5.5zm0-1c2.4852814 0 4.5-2.0147186 4.5-4.5s-2.0147186-4.5-4.5-4.5-4.5 2.0147186-4.5 4.5 2.0147186 4.5 4.5 4.5zm.5-11.5c0 .27614237-.2238576.5-.5.5s-.5-.22385763-.5-.5v-3c0-.27614237.2238576-.5.5-.5s.5.22385763.5.5zm0 17c0 .2761424-.2238576.5-.5.5s-.5-.2238576-.5-.5v-3c0-.2761424.2238576-.5.5-.5s.5.2238576.5.5zm4.8033009-14.59619408c-.1952622.19526215-.5118447.19526215-.7071068 0-.1952622-.19526214-.1952622-.51184463 0-.70710678l2.0502525-2.05025253c.1952622-.19526215.5118446-.19526215.7071068 0 .1952621.19526215.1952621.51184463 0 .70710678zm-11.94974751 11.94974748c-.19526215.1952621-.51184463.1952621-.70710678 0-.19526215-.1952622-.19526215-.5118446 0-.7071068l2.05025253-2.0502525c.19526215-.1952622.51184464-.1952622.70710678 0 .19526215.1952621.19526215.5118446 0 .7071068zm13.64644661-6.8535534c-.2761424 0-.5-.2238576-.5-.5s.2238576-.5.5-.5h3c.2761424 0 .5.2238576.5.5s-.2238576.5-.5.5zm-17 0c-.27614237 0-.5-.2238576-.5-.5s.22385763-.5.5-.5h3c.27614237 0 .5.2238576.5.5s-.22385763.5-.5.5zm14.5961941 4.8033009c-.1952622-.1952622-.1952622-.5118447 0-.7071068.1952621-.1952622.5118446-.1952622.7071068 0l2.0502525 2.0502525c.1952621.1952622.1952621.5118446 0 .7071068-.1952622.1952621-.5118446.1952621-.7071068 0zm-11.94974749-11.94974751c-.19526215-.19526215-.19526215-.51184463 0-.70710678s.51184463-.19526215.70710678 0l2.05025253 2.05025253c.19526215.19526215.19526215.51184464 0 .70710678-.19526214.19526215-.51184463.19526215-.70710678 0z"/></svg>
				</i>
			</div>
		</div>

		<button class="menu-btn-3" data-bs-toggle="offcanvas" data-bs-target="#navbarNavOffcanvas"
				aria-controls="navbarNavOffcanvas" aria-expanded="false"
				aria-label="<?php esc_attr_e( 'Toggle navigation', 'understrap' ); ?>"
				onclick="menuBtnFunction(this)">
			<span></span>
		</button>
		
		<div class="splash"></div>
	</div>

</nav>
<script>
function menuBtnFunction(menuBtn) {
    menuBtn.classList.toggle("active");
}

function getDivInnerHeight(myDiv) {
  var style = getComputedStyle(myDiv);
  return myDiv.clientHeight - parseFloat(style.paddingTop) - parseFloat(style.paddingBottom);
}

document.addEventListener("DOMContentLoaded", () => {
  var myOffcanvas = document.getElementById("navbarNavOffcanvas");
  var myDiv = document.getElementById("main-menu");
  var fastContactElement = document.querySelector('.fast-contact');

  var setFastContactTop = function() {
    fastContactElement.style.top = (getDivInnerHeight(myDiv) + 120) + "px";
  }

  myOffcanvas.addEventListener("show.bs.offcanvas", function () {    
    setFastContactTop();  // Set the CSS top property for the .fast-contact when the offcanvas is shown

    jQuery('.fast-contact').addClass('move');
    jQuery('#main-menu').addClass('showtext');
    jQuery('#cdc-lyris-root').css('z-index', '0');
  });

  myOffcanvas.addEventListener("hide.bs.offcanvas", function () {
    fastContactElement.style.top = "";  // This will effectively remove the inline style, thus reverting to any CSS defined styles

    jQuery('.fast-contact').removeClass('move');
    jQuery('#main-menu').removeClass('showtext');
    jQuery('#cdc-lyris-root').css('z-index', '9500');
  });

  // Handle window resizing
  window.addEventListener("resize", function() {
    if (jQuery('#main-menu').hasClass('showtext')) {  // Check if the offcanvas is shown
      setFastContactTop();  // Update the top property for the .fast-contact
    }
  });
    // 👉 HIER kommt das Matomo Tracking dazu:
	document.querySelectorAll('#main-menu a').forEach(function(link) {
    link.addEventListener('click', function() {
      var label = link.textContent.trim();
      if (window._paq) {
        _paq.push(['trackEvent', 'Navigation', 'Klick', label]);
      }
    });
  });
});


</script>