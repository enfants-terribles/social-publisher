jQuery(document).ready(function () {
  jQuery("#works-response").imagesLoaded(function () {
    jQuery("#works-response").masonry({
      // options
      itemSelector: ".rectangle",
      columnWidth: 0,
      horizontalOrder: true,
    });
  });

  // Floating labels for CF7
  jQuery(".wpcf7 .form-control")
    .focus(function () {
      jQuery(this).parent().parent().addClass("active");
    })
    .blur(function () {
      var cval = jQuery(this).val();
      if (cval.length < 1) {
        jQuery(this).parent().parent().removeClass("active");
      }
    });

  jQuery("input").each(function () {
    if (jQuery(this).val()) {
      jQuery(this).closest(".form-group").addClass("active");
    }
  });
});

jQuery(".post-template-post-sea-linkedin .hero .headline").each(function () {
  var text = jQuery(this).html();
  text = text.replace(/&amp;/g, '<span class="ampersand">&amp;</span>');
  jQuery(this).html(text);
});

jQuery(".ampersand").css({
  "font-family": "Noe Display",
});

jQuery(document).on("wpcf7mailsent", function () {
  jQuery(".form-group.active").removeClass("active");
});

jQuery(document).on("click", ".filter li span", function (e) {
  e.preventDefault();

  var form = jQuery(this).closest("form");
  var url = form.attr("action");

  // Set the value of the format taxonomy filter
  var filterValue = jQuery(this).data("filter-value");
  var filters = "_sft_portfolio_format_taxonomy=" + filterValue;

  // Add any additional filter parameters to the URL
  var additionalFilters = form.serialize();
  if (additionalFilters) {
    filters += "&" + additionalFilters;
  }

  // Append the filter parameters to the URL
  if (filters) {
    url += "?" + filters;
  }

  window.location.href = url;
});

jQuery(document).on("sf:ajaxfinish", ".searchandfilter", function () {
  var $grid = jQuery("#works-response").masonry({
    itemSelector: ".rectangle",
    columnWidth: 0,
  });
  // layout Masonry after each image loads
  $grid.imagesLoaded().progress(function () {
    $grid.masonry("reloadItems");
    $grid.masonry("layout");
  });
});

const logos = document.querySelectorAll("#customers .media");

logos.forEach((element) => {
  const contents = element.querySelectorAll(".media-item-contents");

  gsap.set(contents, { scale: 0 });

  gsap.to(contents, {
    duration: 1.2,
    autoAlpha: 1,
    scale: 1,
    ease: "slow(0.7, 0.7, false)",
    scrollTrigger: {
      trigger: element,
      start: "top bottom-=100",
      end: "bottom top+=100",
      //toggleActions: "play reverse play reverse"
    },
  });
});
const imgzoom1 = document.querySelectorAll(".image_col");

imgzoom1.forEach((element) => {
  const contents = element.querySelectorAll(".image_col img");

  gsap.set(contents, { scale: 0.5 });

  gsap.to(contents, {
    duration: 1.2,
    autoAlpha: 1,
    scale: 1,
    ease: "circ.out(1, 0.3)",
    scrollTrigger: {
      trigger: element,
      start: "top bottom-=100",
      end: "bottom top+=100",
      // toggleActions: "play reverse play reverse"
    },
  });
});

// 404
if (document.querySelector(".cog1")) {
  let t1 = gsap.timeline();
  let t2 = gsap.timeline();
  let t3 = gsap.timeline();

  t1.to(".cog1", {
    transformOrigin: "50% 50%",
    rotation: "+=360",
    repeat: -1,
    ease: Linear.easeNone,
    duration: 8,
  });

  t2.to(".cog2", {
    transformOrigin: "50% 50%",
    rotation: "-=360",
    repeat: -1,
    ease: Linear.easeNone,
    duration: 8,
  });

  t3.fromTo(
    ".wrong-para",
    {
      opacity: 0,
    },
    {
      opacity: 1,
      duration: 1,
      stagger: {
        repeat: -1,
        yoyo: true,
      },
    }
  );
}
const swiper = new Swiper(".page-template-page-sea .swiper", {
  loop: true,
  spaceBetween: 10,
  freeMode: false,
  slidesPerView: 1,
  centeredSlides: true,
  grabCursor: true,
  autoplay: {
    delay: 5000,
  },
  breakpoints: {
    640: {
      slidesPerView: 1.5,
    },
    1024: {
      slidesPerView: 1.8,
    },
  },
});

const swiperbeforeafter = new Swiper(
  ".post-template-post-sea-linkedin #container-epsilon .swiper",
  {
    loop: false,
    autoplay: false,
    spaceBetween: 50,
    freeMode: false,
    slidesPerView: 1,
    centeredSlides: true,
    grabCursor: false,
    allowTouchMove: false,
    // If we need pagination
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      640: {
        slidesPerView: 1.5,
      },
      1024: {
        slidesPerView: 1.8,
      },
    },
  }
);

const body = document.body;
const triggerMenu = document.querySelector("#wrapper-navbar .navbar");
const nav = document.querySelector("#wrapper-navbar .container-fluid");
const menu = document.querySelector(".page-header .menu");
const scrollUp = "scroll-up";
const scrollDown = "scroll-down";
let lastScroll = 0;

window.addEventListener("scroll", () => {
  const currentScroll = window.scrollY;
  if (currentScroll <= 0) {
    body.classList.remove(scrollUp);
    return;
  }

  if (currentScroll > lastScroll && !body.classList.contains(scrollDown)) {
    // down
    body.classList.remove(scrollUp);
    body.classList.add(scrollDown);
  } else if (
    currentScroll < lastScroll &&
    body.classList.contains(scrollDown)
  ) {
    // up
    body.classList.remove(scrollDown);
    body.classList.add(scrollUp);
  }
  lastScroll = currentScroll;
});

// const container = document.querySelector("#container-epsilon");
// document
//   .querySelector("#container-epsilon .wrap_1 .slider")
//   .addEventListener("input", (e) => {
//     container.style.setProperty("--position", `${e.target.value}%`);
//   });
