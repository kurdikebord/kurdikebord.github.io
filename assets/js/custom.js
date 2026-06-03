document.addEventListener("DOMContentLoaded", function () {
  
  

  const sections = document.querySelectorAll(".section");
  const navLinks = document.querySelectorAll(".fbs__net-navbar .scroll-link");

  function removeActiveClasses() {
    if (navLinks) {
      navLinks.forEach((link) => link.classList.remove("active"));
    }
  }

  function addActiveClass(currentSectionId) {
    const activeLink = document.querySelector(
      `.fbs__net-navbar .scroll-link[href="#${currentSectionId}"]`
    );
    if (activeLink) {
      activeLink.classList.add("active");
    }
  }

  function getCurrentSection() {
    let currentSection = null;
    let minDistance = Infinity;
    if (sections) {
      sections.forEach((section) => {
        const rect = section.getBoundingClientRect();
        const distance = Math.abs(rect.top - window.innerHeight / 4);

        if (distance < minDistance && rect.top < window.innerHeight) {
          minDistance = distance;
          currentSection = section.getAttribute("id");
        }
      });
    }

    return currentSection;
  }

  function updateActiveLink() {
    const currentSectionId = getCurrentSection();
    if (currentSectionId) {
      removeActiveClasses();
      addActiveClass(currentSectionId);
    }
  }

  window.addEventListener("scroll", updateActiveLink);

  const portfolioGrid = document.querySelector('#portfolio-grid');
  if (portfolioGrid) {
    var iso = new Isotope("#portfolio-grid", {
      itemSelector: ".portfolio-item",
      layoutMode: "masonry",
    });

    if (iso) {
      iso.on("layoutComplete", updateActiveLink);

      imagesLoaded("#portfolio-grid", function () {
        iso.layout();
        updateActiveLink();
      });
    }

    var filterButtons = document.querySelectorAll(".filter-button");
    if (filterButtons) {
      filterButtons.forEach(function (button) {
        button.addEventListener("click", function (e) {
          e.preventDefault();
          var filterValue = button.getAttribute("data-filter");
          iso.arrange({ filter: filterValue });

          filterButtons.forEach(function (btn) {
            btn.classList.remove("active");
          });
          button.classList.add("active");
          updateActiveLink();
        });
      });
    }

    updateActiveLink();
  }
});

const navbarScrollInit = () => {
  var navbar = document.querySelector(".fbs__net-navbar");

  var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
  if (navbar) {
    if (scrollTop > 0) {
      navbar.classList.add("active");
    } else {
      navbar.classList.remove("active");
    }
  }
};

const navbarInit = () => {
  document.querySelectorAll('.dropdown-toggle[href="#"]').forEach(function (el, index) {
    el.addEventListener("click", function (event) {
      event.stopPropagation();
    });
  });
};

// ======= Marquee =======
const logoMarqueeInit = () => {
  const wrapper = document.querySelector(".logo-wrapper");
  const boxes = gsap.utils.toArray(".logo-item");
  
  if (boxes.length > 0) {
    const loop = horizontalLoop(boxes, {
      paused: false,
      repeat: -1,
      speed: 0.25,
      reversed: false,
    });
    
    function horizontalLoop(items, config) {
      items = gsap.utils.toArray(items);
      config = config || {};
      let tl = gsap.timeline({
          repeat: config.repeat,
          paused: config.paused,
          defaults: { ease: "none" },
          onReverseComplete: () =>
            tl.totalTime(tl.rawTime() + tl.duration() * 100),
        }),
        length = items.length,
        startX = items[0].offsetLeft,
        times = [],
        widths = [],
        xPercents = [],
        curIndex = 0,
        pixelsPerSecond = (config.speed || 1) * 100,
        snap =
          config.snap === false ? (v) => v : gsap.utils.snap(config.snap || 1), // some browsers shift by a pixel to accommodate flex layouts, so for example if width is 20% the first element's width might be 242px, and the next 243px, alternating back and forth. So we snap to 5 percentage points to make things look more natural
        totalWidth,
        curX,
        distanceToStart,
        distanceToLoop,
        item,
        i;
      gsap.set(items, {
        // convert "x" to "xPercent" to make things responsive, and populate the widths/xPercents Arrays to make lookups faster.
        xPercent: (i, el) => {
          let w = (widths[i] = parseFloat(gsap.getProperty(el, "width", "px")));
          xPercents[i] = snap(
            (parseFloat(gsap.getProperty(el, "x", "px")) / w) * 100 +
              gsap.getProperty(el, "xPercent")
          );
          return xPercents[i];
        },
      });
      gsap.set(items, { x: 0 });
      totalWidth =
        items[length - 1].offsetLeft +
        (xPercents[length - 1] / 100) * widths[length - 1] -
        startX +
        items[length - 1].offsetWidth *
          gsap.getProperty(items[length - 1], "scaleX") +
        (parseFloat(config.paddingRight) || 0);
      for (i = 0; i < length; i++) {
        item = items[i];
        curX = (xPercents[i] / 100) * widths[i];
        distanceToStart = item.offsetLeft + curX - startX;
        distanceToLoop =
          distanceToStart + widths[i] * gsap.getProperty(item, "scaleX");
        tl.to(
          item,
          {
            xPercent: snap(((curX - distanceToLoop) / widths[i]) * 100),
            duration: distanceToLoop / pixelsPerSecond,
          },
          0
        )
          .fromTo(
            item,
            {
              xPercent: snap(
                ((curX - distanceToLoop + totalWidth) / widths[i]) * 100
              ),
            },
            {
              xPercent: xPercents[i],
              duration:
                (curX - distanceToLoop + totalWidth - curX) / pixelsPerSecond,
              immediateRender: false,
            },
            distanceToLoop / pixelsPerSecond
          )
          .add("label" + i, distanceToStart / pixelsPerSecond);
        times[i] = distanceToStart / pixelsPerSecond;
      }
      function toIndex(index, vars) {
        vars = vars || {};
        Math.abs(index - curIndex) > length / 2 &&
          (index += index > curIndex ? -length : length); // always go in the shortest direction
        let newIndex = gsap.utils.wrap(0, length, index),
          time = times[newIndex];
        if (time > tl.time() !== index > curIndex) {
          // if we're wrapping the timeline's playhead, make the proper adjustments
          vars.modifiers = { time: gsap.utils.wrap(0, tl.duration()) };
          time += tl.duration() * (index > curIndex ? 1 : -1);
        }
        curIndex = newIndex;
        vars.overwrite = true;
        return tl.tweenTo(time, vars);
      }
      tl.next = (vars) => toIndex(curIndex + 1, vars);
      tl.previous = (vars) => toIndex(curIndex - 1, vars);
      tl.current = () => curIndex;
      tl.toIndex = (index, vars) => toIndex(index, vars);
      tl.times = times;
      tl.progress(1, true).progress(0, true); // pre-render for performance
      if (config.reversed) {
        tl.vars.onReverseComplete();
        tl.reverse();
      }
      return tl;
    }
  }
};

document.addEventListener("DOMContentLoaded", logoMarqueeInit);

// ======= Navbar Scroll =======
document.addEventListener("DOMContentLoaded", function () {
  logoMarqueeInit();
  navbarInit();
  window.addEventListener("scroll", navbarScrollInit);
});

// ======= Swiper =======
const swiperInit = () => {
  var swiper = new Swiper(".testimonialSwiper", {
    slidesPerView: 1,
    speed: 700,
    spaceBetween: 30,
    loop: true,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      640: {
        slidesPerView: 1.5,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 2.5,
        spaceBetween: 30,
      },
      1024: {
        slidesPerView: 2.5,
        spaceBetween: 30,
      },
    },
    navigation: {
      nextEl: ".custom-button-next",
      prevEl: ".custom-button-prev",
    },
  });
  

  const progressCircle = document.querySelector(".autoplay-progress svg");
  const progressContent = document.querySelector(".autoplay-progress span");
  if (progressCircle && progressContent ) {
    var swiper2 = new Swiper(".sliderSwiper", {
      slidesPerView: 1,
      speed: 700,
      spaceBetween: 0,
      loop: true,
      centeredSlides: true,
      autoplay: {
        delay: 7000,
        disableOnInteraction: false
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      navigation: {
        nextEl: ".custom-button-next",
        prevEl: ".custom-button-prev",
      },

      on: {
        autoplayTimeLeft(s, time, progress) {
          progressCircle.style.setProperty("--progress", 1 - progress);
          progressContent.textContent = `${Math.ceil(time / 1000)}s`;
        }
      }
    });
  }



  // Testimonials 
  // Initialize the second Swiper slider (fade effect)
  var swiperFade = new Swiper(".swiper-fade-container", {
    effect: "fade",
    loop: true,
    speed: 700,
    fadeEffect: {
      crossFade: true,
    },
    allowTouchMove: false,
  });

  var swiperTestimonial = new Swiper(".testimony-swiper", {
    loop: true,
    speed: 700,
    autoplay: {
      delay: 5000,
    },
    pagination: {
      el: ".swiper-pagination",
      type: "progressbar",
    },
    navigation: {
      nextEl: ".custom-next",
      prevEl: ".custom-prev",
    },
    on: {
      slideChange: (swiper) => {
        updateProgressBar(swiper);
        swiperFade.slideTo(swiper.realIndex);
      },
    },
  });

  function updateProgressBar(swiperTestimonial) {
    var progress = ((swiperTestimonial.realIndex + 1) / swiperTestimonial.slides.length) * 100;
    document.querySelector(".custom-progress-bar").style.width = progress + "%";
  }
  const prevButton = document.querySelector(".custom-prev");
  const nextButton = document.querySelector(".custom-next");
  if (prevButton) {

  prevButton.addEventListener("click", function () {
    swiperTestimonial.slidePrev();
  });

  nextButton.addEventListener("click", function () {
    swiperTestimonial.slideNext();
  });
  }
  


  var testimonialV4 = new Swiper(".testimonialV4", {
    
    loop: true,
    spaceBetween: 5,
    centeredSlides: true,
    speed: 700,
    autoplay: {
      delay: 3500,
      disableOnInteraction: false,
    },
    breakpoints: {
      640: {
        slidesPerView: 1,
        spaceBetween: 0,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 5,
      },
      1024: {
        slidesPerView: 3,
        spaceBetween: 5,
      },
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
  });

  var portfolioV5Init = new Swiper(".portfolioV5", {
    loop: true,
    spaceBetween: 5,
    
    speed: 700,
    autoplay: {
      delay: 3500,
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: ".portfolioV5-custom-button-next",
      prevEl: ".portfolioV5-custom-button-prev",
    },

    breakpoints: {
      640: {
        slidesPerView: 1,
        spaceBetween: 0,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      1024: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
    },
    
  });

  var howItWorksSwiper = new Swiper(".howItWorksSwiper", {
    slidesPerView: 1,
    speed: 700,
    spaceBetween: 30,
    loop: true,
    // autoplay: {
    //   delay: 3500,
    //   disableOnInteraction: false,
    // },
    
    breakpoints: {
      640: {
        slidesPerView: 1.5,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 2.5,
        spaceBetween: 30,
      },
      1024: {
        slidesPerView: 2.5,
        spaceBetween: 30,
      },
    },
    navigation: {
      nextEl: ".howitworks__v3-next",
      prevEl: ".howitworks__v3-prev",
    },
  });


  // ======= Testimonials v6 =======
  // Initialize Thumbnail Swiper
  const thumbnailSwiperTestimonialsV6 = new Swiper('.thumbnail-swiperTestimonialsv6', {
    spaceBetween: 10,
    slidesPerView: 3,
    speed: 700,
    watchSlidesProgress: true,
  });

  // Initialize Main Swiper and link with Thumbnail Swiper
  const mainSwiperTestimonialsV6 = new Swiper('.main-swiperTestimonialsv6', {
    spaceBetween: 10,
    thumbs: {
      swiper: thumbnailSwiperTestimonialsV6,
    }
  });



  var logoSwiper = new Swiper(".logoSlider", {
    slidesPerView: 6,
    spaceBetween: 20,
    loop: true,
    speed: 700,
    autoplay: {
      delay: 2000,
      disableOnInteraction: false,
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    navigation: {
      nextEl: '.custom-swiper-button-next',
      prevEl: '.custom-swiper-button-prev',
    },
  });


  var teamSwiper = new Swiper(".teamSlider", {
    spaceBetween: 20,
    slidesPerView: 2,
    loop: true,
    speed: 700,
    autoplay: {
      delay: 2000,
      disableOnInteraction: false,
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    navigation: {
      nextEl: '.custom-swiper-button-next',
      prevEl: '.custom-swiper-button-prev',
    },

    breakpoints: {
      640: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 3,
        spaceBetween: 30,
      },
      1024: {
        slidesPerView: 4,
        spaceBetween: 30,
      },
    },
  });

  




  
  var swiperContainer = document.querySelector(".mobile-screenshots");

  // Only initialize Swiper if the element exists
  if (swiperContainer) {
    
    const swiperContainer = document.querySelector(".mobile-screenshots");
    if (!swiperContainer) return;

    const swiperMobileScreenshot = new Swiper(".mobileScreenshotsSwiper", {
      slidesPerView: 4.5,
      centeredSlides: true,
      spaceBetween: 0,
      loop: true,
      speed: 800,
      autoplay: {
        delay: 3500,
        disableOnInteraction: false,
      },
      pagination: {
        el: ".swiper-pagination",
        type: "fraction",
      },
      breakpoints: {
        320: { slidesPerView: 1.3 },
        480: { slidesPerView: 2.3 },
        768: { slidesPerView: 3.3 },
        1024: { slidesPerView: 4.5 },
        1280: { slidesPerView: 5.5 },
      },
      on: {
        slideChangeTransitionStart() {
          document
            .querySelectorAll(".mobile-screenshots .swiper-slide")
            .forEach(slide => (slide.style.transform = "scale(0.7)"));
          const activeSlide = document.querySelector(".swiper-slide-active");
          if (activeSlide) activeSlide.style.transform = "scale(1)";
        },
      },
    });
  }


  // Content v10 Swiper
  if (document.querySelector(".content_v10Swiper")) {
    const contentV10Swiper = new Swiper(".content_v10Swiper", {
      slidesPerView: 1,
      loop: true,
      allowTouchMove: false,
    });

    const tabs = document.querySelectorAll(".content_10-tab-item");
    const progressBars = document.querySelectorAll(".content_10-progress-bar");

    let activeIndex = 0;
    const progressDuration = 12000; // 12 seconds per slide
    let progressStartTime;
    let progressTimeout;
    let progressPaused = false;
    let elapsedTime = 0;

    function resetProgressBars() {
      progressBars.forEach(bar => {
        bar.style.width = "0";
        bar.style.transition = "none";
      });
    }

    function startProgress(index) {
      clearTimeout(progressTimeout);
      resetProgressBars();
      tabs.forEach((tab, i) => tab.classList.toggle("active", i === index));

      const bar = progressBars[index];
      bar.style.transition = "none";
      bar.style.width = "0";

      requestAnimationFrame(() => {
        bar.style.transition = `width ${progressDuration}ms linear`;
        bar.style.width = "100%";
      });

      progressStartTime = performance.now();
      elapsedTime = 0;
      progressPaused = false;

      progressTimeout = setTimeout(() => {
        activeIndex = (activeIndex + 1) % tabs.length;
        contentV10Swiper.slideToLoop(activeIndex);
        startProgress(activeIndex);
      }, progressDuration);
    }

    function pauseProgress() {
      if (progressPaused) return;
      progressPaused = true;
      elapsedTime += performance.now() - progressStartTime;
      clearTimeout(progressTimeout);

      const bar = progressBars[activeIndex];
      const percent = Math.min((elapsedTime / progressDuration) * 100, 100);
      bar.style.transition = "none";
      bar.style.width = percent + "%";
    }

    function resumeProgress() {
      if (!progressPaused) return;
      progressPaused = false;

      const bar = progressBars[activeIndex];
      const remaining = Math.max(progressDuration - elapsedTime, 0);

      requestAnimationFrame(() => {
        bar.style.transition = `width ${remaining}ms linear`;
        bar.style.width = "100%";
      });

      progressStartTime = performance.now();
      progressTimeout = setTimeout(() => {
        activeIndex = (activeIndex + 1) % tabs.length;
        contentV10Swiper.slideToLoop(activeIndex);
        startProgress(activeIndex);
      }, remaining);
    }

    tabs.forEach((tab, index) => {
      tab.addEventListener("click", () => {
        clearTimeout(progressTimeout);
        activeIndex = index;
        contentV10Swiper.slideToLoop(activeIndex);
        startProgress(activeIndex);
      });
    });

    contentV10Swiper.on("slideChange", () => {
      const realIndex = contentV10Swiper.realIndex;
      activeIndex = realIndex;
      clearTimeout(progressTimeout);
      startProgress(realIndex);
    });

    // Pause/Resume when switching browser tabs
    document.addEventListener("visibilitychange", () => {
      if (document.hidden) {
        pauseProgress();
      } else {
        resumeProgress();
      }
    });

    // Start autoplay on load
    startProgress(activeIndex);
  }
};

document.addEventListener("DOMContentLoaded", swiperInit);

// ======= Glightbox =======
const glightBoxInit = () => {
  const lightbox = GLightbox({
    touchNavigation: true,
    loop: true,
    autoplayVideos: true,
  });
};
document.addEventListener("DOMContentLoaded", glightBoxInit);

// ======= BS OffCanvass =======
const bsOffCanvasInit = () => {
  var offcanvasElement = document.getElementById("fbs__net-navbars");
  if (offcanvasElement) {
    offcanvasElement.addEventListener("show.bs.offcanvas", function () {
      document.body.classList.add("offcanvas-active");
    });

    offcanvasElement.addEventListener("hidden.bs.offcanvas", function () {
      document.body.classList.remove("offcanvas-active");
    });
  }
};
document.addEventListener("DOMContentLoaded", bsOffCanvasInit);

// ======= Get Distance of image =======
const adjustImageBgWidthInit = () => {
  
  // Get the image container
  const container = document.querySelector('.container.position-static');
  const imgBg = document.querySelector('.img-bg');

  if (container && imgBg) {
    // Calculate the distance of the image from the left edge of the screen
    const distanceFromLeftEdge = container.getBoundingClientRect().left + 12;

    // Apply the `calc()` style using the calculated distance
    imgBg.style.width = `calc(100vw - ${distanceFromLeftEdge}px)`;


  }
}
// Run the function on DOM load and on window resize
document.addEventListener('DOMContentLoaded', adjustImageBgWidthInit);
window.addEventListener('resize', adjustImageBgWidthInit);

// ======= Back To Top =======
const backToTopInit = () => {
  const backToTopButton = document.getElementById("back-to-top");
  if (backToTopButton) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 170) {
        backToTopButton.classList.add("show");
      } else {
        backToTopButton.classList.remove("show");
      }
    });
    backToTopButton.addEventListener("click", () => {
      window.scrollTo({
        top: 0,
        behavior: "smooth",
      });
    });
  }
};

document.addEventListener("DOMContentLoaded", backToTopInit);

// ======= Floating Download Button =======
const floatingDownloadInit = () => {
  const wrapper = document.getElementById("floatingDownload");
  const toggle = document.getElementById("floatingDownloadToggle");
  if (!wrapper || !toggle) return;

  window.addEventListener("scroll", () => {
    if (window.scrollY > 300) {
      wrapper.classList.add("show");
    } else {
      wrapper.classList.remove("show");
      wrapper.classList.remove("open");
    }
  });

  toggle.addEventListener("click", (e) => {
    e.preventDefault();
    wrapper.classList.toggle("open");
  });

  document.addEventListener("click", (e) => {
    if (!wrapper.contains(e.target)) {
      wrapper.classList.remove("open");
    }
  });
};

document.addEventListener("DOMContentLoaded", floatingDownloadInit);



//   // ======= Dark Toggle =======
// const themeDarkToggleInit = () => {
//   const html = document.documentElement;
//   const toggleBtn = document.getElementById("themeToggle");
//   if (!toggleBtn) return;

//   // Disable thumb animation during initial sync
//   toggleBtn.classList.add("no-transition");

//   function applyTheme(isDark) {
//     html.setAttribute("data-bs-theme", isDark ? "dark" : "light");
//     toggleBtn.classList.toggle("is-dark", isDark);
//     toggleBtn.setAttribute("aria-pressed", isDark ? "true" : "false");
//     localStorage.setItem("theme", isDark ? "dark" : "light");
//   }

//   // Read the theme that was already applied by the inline script
//   const currentTheme = html.getAttribute("data-bs-theme") === "dark" ? "dark" : "light";
//   const isDark = currentTheme === "dark";

//   // Sync button state WITHOUT animation
//   toggleBtn.classList.toggle("is-dark", isDark);
//   toggleBtn.setAttribute("aria-pressed", isDark ? "true" : "false");

//   // Re-enable transitions on the next frame
//   requestAnimationFrame(() => {
//     toggleBtn.classList.remove("no-transition");
//   });

//   // Handle clicks (with animation)
//   toggleBtn.addEventListener("click", () => {
//     const willBeDark = html.getAttribute("data-bs-theme") !== "dark";
//     applyTheme(willBeDark);
//   });
// };

// document.addEventListener("DOMContentLoaded", themeDarkToggleInit);

// ======= Dark Toggle =======
const themeDarkToggleInit = () => {
  const html = document.documentElement;
  const toggleBtns = document.querySelectorAll(".theme-toggle");
  if (!toggleBtns.length) return;

  function syncToggleState(isDark) {
    toggleBtns.forEach((btn) => {
      btn.classList.toggle("is-dark", isDark);
      btn.setAttribute("aria-pressed", isDark ? "true" : "false");
    });
  }

  function applyTheme(isDark, { save = true } = {}) {
    const theme = isDark ? "dark" : "light";
    html.setAttribute("data-bs-theme", theme);
    syncToggleState(isDark);
    if (save) {
      localStorage.setItem("theme", theme);
    }
  }

  // Read the theme that was already applied by the inline <script> in <head>
  const currentTheme = html.getAttribute("data-bs-theme") === "dark" ? "dark" : "light";
  const isDarkInitial = currentTheme === "dark";

  // Disable thumb animation during initial sync
  toggleBtns.forEach((btn) => btn.classList.add("no-transition"));

  // Sync button state
  syncToggleState(isDarkInitial);

  // Re-enable transitions on the next frame
  requestAnimationFrame(() => {
    toggleBtns.forEach((btn) => btn.classList.remove("no-transition"));
  });

  // Handle clicks (with animation)
  toggleBtns.forEach((toggleBtn) => {
    const thumb = toggleBtn.querySelector(".toggle-thumb");

    toggleBtn.addEventListener("click", (e) => {
      e.preventDefault();
      const isCurrentlyDark = html.getAttribute("data-bs-theme") === "dark";
      const willBeDark = !isCurrentlyDark;

      toggleBtn.classList.add("animating");
      toggleBtn.classList.remove("no-transition");
      applyTheme(willBeDark);
    });

    // Remove .animating after the thumb finishes sliding
    if (thumb) {
      thumb.addEventListener("transitionend", (event) => {
        if (event.propertyName === "transform") {
          toggleBtn.classList.remove("animating");
        }
      });
    }
  });
};

document.addEventListener("DOMContentLoaded", themeDarkToggleInit);





// ======= Inline SVG =======
const inlineSvgInit = () => {
  const imgElements = document.querySelectorAll(".js-img-to-inline-svg");
  if (imgElements) {
    imgElements.forEach((imgElement) => {
      const imgURL = imgElement.getAttribute("src");

      fetch(imgURL)
        .then((response) => response.text())
        .then((svgText) => {
          const parser = new DOMParser();
          const svgDocument = parser.parseFromString(svgText, "image/svg+xml");
          const svgElement = svgDocument.documentElement;

          Array.from(imgElement.attributes).forEach((attr) => {
            if (attr.name !== "class") {
              svgElement.setAttribute(attr.name, attr.value);
            } else {
              const classes = attr.value
                .split(" ")
                .filter((className) => className !== "js-img-to-inline-svg");
              if (classes.length > 0) {
                svgElement.setAttribute("class", classes.join(" "));
  }
}

// ======= Offer Modal =======
const offerModalInit = () => {
  const modal = document.getElementById('offerModal');
  const closeBtn = document.getElementById('offerModalClose');
  if (!modal || !closeBtn) return;

  const show = () => modal.classList.add('show');
  const hide = () => modal.classList.remove('show');

  closeBtn.addEventListener('click', hide);
  modal.addEventListener('click', (e) => { if (e.target === modal) hide(); });

  // Show when clicking any link/button targeting #services (features section)
  document.querySelectorAll('[href="#services"], .btn-appvendor[href*="services"]').forEach(el => {
    el.addEventListener('click', show);
  });

  // Auto show after 4 seconds
  setTimeout(show, 4000);
};
document.addEventListener('DOMContentLoaded', offerModalInit);
          });

          imgElement.replaceWith(svgElement);
        })
        .catch((error) => console.error("Error fetching SVG:", error));
    });
  }
};

document.addEventListener("DOMContentLoaded", inlineSvgInit);

// ======= AOS =======
const aosInit = () => {
  AOS.init({
    duration: 800,
    easing: 'slide',
    once: true
  });
}
document.addEventListener("DOMContentLoaded", aosInit);

// ======= PureCounter =======
const pureCounterInit = () => {
  new PureCounter({
    selector: ".purecounter",
  });
}
document.addEventListener("DOMContentLoaded", pureCounterInit);

// ======= Disable Click Navbar Dropdown =======
const addHoverEvents = (dropdown) => {
  const dropdownToggle = dropdown.querySelector('.dropdown-toggle');

  const preventClick = (event) => event.preventDefault();
  const showDropdown = () => {
    dropdown.classList.add('show');
    dropdownToggle.setAttribute('aria-expanded', 'true');
    const dropdownMenu = dropdown.querySelector('.dropdown-menu');
    dropdownMenu.classList.add('show');
  };
  const hideDropdown = () => {
    dropdown.classList.remove('show');
    dropdownToggle.setAttribute('aria-expanded', 'false');
    const dropdownMenu = dropdown.querySelector('.dropdown-menu');
    dropdownMenu.classList.remove('show');
  };

  // Disable the click event for toggling the dropdown
  dropdownToggle.addEventListener('click', preventClick);

  // Open dropdown on hover
  dropdown.addEventListener('mouseover', showDropdown);

  // Close dropdown when mouse leaves
  dropdown.addEventListener('mouseleave', hideDropdown);

  // Store references to the event listeners for later removal
  dropdown.__events = { preventClick, showDropdown, hideDropdown };
};

const removeHoverEvents = (dropdown) => {
  const dropdownToggle = dropdown.querySelector('.dropdown-toggle');
  const { preventClick, showDropdown, hideDropdown } = dropdown.__events || {};

  if (preventClick) {
    // Remove the event listeners
    dropdownToggle.removeEventListener('click', preventClick);
    dropdown.removeEventListener('mouseover', showDropdown);
    dropdown.removeEventListener('mouseleave', hideDropdown);

    // Remove the reference to the stored events
    delete dropdown.__events;
  }
};

const handleNavbarEvents = () => {
  const dropdowns = document.querySelectorAll('.navbar .dropdown');
  const dropstarts = document.querySelectorAll('.navbar .dropstart');
  const dropends = document.querySelectorAll('.navbar .dropend');

  if (window.innerWidth >= 992) {

    // Add hover events to dropdowns
    dropdowns.forEach(addHoverEvents);
    dropstarts.forEach(addHoverEvents);
    dropends.forEach(addHoverEvents);
  } else {

    // Remove hover events from dropdowns
    dropdowns.forEach(removeHoverEvents);
    dropstarts.forEach(removeHoverEvents);
    dropends.forEach(removeHoverEvents);
  }
};

// Function to handle resizing
const handleResize = () => {
  const dropdowns = document.querySelectorAll('.navbar .dropdown');
  const dropstarts = document.querySelectorAll('.navbar .dropstart');
  const dropends = document.querySelectorAll('.navbar .dropend');

  // Remove hover events before rechecking window size
  dropdowns.forEach(removeHoverEvents);
  dropstarts.forEach(removeHoverEvents);
  dropends.forEach(removeHoverEvents);

  // Re-apply hover events based on window size
  handleNavbarEvents();
};

// Call the function on resize event and initially
window.addEventListener('resize', handleResize);
handleNavbarEvents();



// ======= Coming Soon Countdown =======
const countdownInit = () => {

  // Get the current year
  const currentYear = new Date().getFullYear();
  const nextYear = currentYear + 1;
  const launchDate = new Date(`December 31, ${nextYear} 23:59:59`).getTime();

  // Change this "December 31, 2024 23:59:59" to your your website launch date
  // const launchDate = new Date("December 31, 2024 23:59:59").getTime();


  const x = setInterval(function () {

    const now = new Date().getTime();
      
    const distance = launchDate - now;
      
    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
      
    // Output the result in an element with id
    const daysEl = document.getElementById("days");
    const hoursEl = document.getElementById("hours");
    const minutesEl = document.getElementById("minutes");
    const secondsEl = document.getElementById("seconds");
    if (daysEl) {
      daysEl.innerText = days;
    }
    if (hoursEl) {
      hoursEl.innerText = hours;
    }
    if (minutesEl) {
      minutesEl.innerText = minutes;
    }
    if (secondsEl) {
      secondsEl.innerText = seconds;
    }
      
    // If the count down is finished, write some text
    if (distance < 0) {
      clearInterval(x);
      document.querySelector(".countdown").innerText = "Launched!";
    }
  }, 1000);
};
document.addEventListener('DOMContentLoaded', countdownInit);


// Skills 
// Vanilla JavaScript to animate progress bars on scroll
const animateProgressBars = () => {
  var progressBars = document.querySelectorAll('.progress-bar');
  var viewportHeight = window.innerHeight;
  var scrollPosition = window.scrollY;

  progressBars.forEach(function(progressBar) {
    var progressPosition = progressBar.parentElement.getBoundingClientRect().top + window.scrollY;
    var maxVal = progressBar.getAttribute('aria-valuemax');

    // Check if the progress bar has already been animated
    if (!progressBar.classList.contains('animated') && (scrollPosition + viewportHeight >= progressPosition)) {
      progressBar.style.width = maxVal + "%";
      progressBar.setAttribute("aria-valuenow", maxVal);

      // Mark as animated
      progressBar.classList.add('animated');
    }
  });
};

// Trigger the animation on scroll and once the DOM is loaded
document.addEventListener('scroll', animateProgressBars);
document.addEventListener('DOMContentLoaded', animateProgressBars);



// Pricing v5
const pricingSwitchInit = () => {
  const switchToggle = document.querySelector('#switchCheckChecked');
  const counters = document.querySelectorAll('.price-amount');

  if (!switchToggle) {
    return;
  }

  function updatePrices() {
    const isAnnual = switchToggle.checked;

    counters.forEach(counter => {
      const monthly = counter.getAttribute('data-number-monthly');
      const annual = counter.getAttribute('data-number-anually');

      // Update the end value
      const newVal = isAnnual ? annual : monthly;
      console.log(newVal);
      
      counter.setAttribute('data-purecounter-end', newVal);
      counter.setAttribute('data-purecounter-duration', ".5");

      setTimeout(() => {
        counter.setAttribute('data-purecounter-start', newVal);
      }, 7);

    });

    // Force PureCounter to reinitialize
    new PureCounter({
      selector: ".purecounter",
    });
  }

  switchToggle.addEventListener('change', updatePrices);
}
document.addEventListener("DOMContentLoaded", pricingSwitchInit);


const typedInit = () => {
  const typedElements = document.querySelectorAll(".typed_entry");

  typedElements.forEach((typedEl) => {
    const strings = JSON.parse(typedEl.dataset.strings || '[]');
    const loop = typedEl.dataset.loop === "1";
    const showCursor = typedEl.dataset.cursor === "1";
    const cursorChar = typedEl.dataset.cursorChar || "|";
    const speed = parseInt(typedEl.dataset.speed) || 100;
    const delay = parseInt(typedEl.dataset.delay) || 1000;

    const cursorEl = typedEl.nextElementSibling?.classList.contains("typed-cursor")
      ? typedEl.nextElementSibling
      : null;

    if (cursorEl && showCursor) {
      cursorEl.textContent = cursorChar;
    } else if (cursorEl) {
      cursorEl.style.display = "none";
    }

    let currentStr = 0;
    let currentChar = 0;
    let isDeleting = false;

    function type() {
      const text = strings[currentStr];
      if (!text) return;

      const chars = Array.from(text);

      if (!isDeleting) {
        typedEl.textContent = chars.slice(0, currentChar + 1).join('');
        currentChar++;
        if (currentChar === chars.length) {
          isDeleting = true;
          setTimeout(type, delay);
          return;
        }
      } else {
        currentChar--;
        typedEl.textContent = chars.slice(0, currentChar).join('');
        if (currentChar === 0) {
          isDeleting = false;
          currentStr = (currentStr + 1) % strings.length;
          if (!loop && currentStr === 0) return;
        }
      }
      setTimeout(type, isDeleting ? speed / 2 : speed);
    }

    type();
  });
}
document.addEventListener("DOMContentLoaded", typedInit);




// seeLessMoreInit commented out — testimonials section hidden
// const seeLessMoreInit = () => { ... };
// document.addEventListener('DOMContentLoaded', seeLessMoreInit);

// Pricing PRO card click to expand features
const pricingCardToggle = () => {
  const cards = document.querySelectorAll('.card-clickable');
  cards.forEach(card => {
    card.addEventListener('click', (e) => {
      if (e.target.closest('.btn-light')) return;
      card.classList.toggle('expanded');
    });
  });
};
document.addEventListener('DOMContentLoaded', pricingCardToggle);

function copyNumber(num, btn) {
  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard.writeText(num).then(function() {
      var orig = btn.textContent;
      btn.textContent = '✅';
      setTimeout(function() { btn.textContent = orig; }, 2000);
    });
  } else {
    var ta = document.createElement('textarea');
    ta.value = num;
    ta.style.position = 'fixed';
    ta.style.opacity = '0';
    document.body.appendChild(ta);
    ta.select();
    document.execCommand('copy');
    document.body.removeChild(ta);
    var orig = btn.textContent;
    btn.textContent = '✅';
    setTimeout(function() { btn.textContent = orig; }, 2000);
  }
}

// ======= Offer Modal =======
const offerModalInit = () => {
  var modal = document.getElementById('offerModal');
  var closeBtn = document.getElementById('offerModalClose');
  if (!modal || !closeBtn) return;

  function show() { modal.classList.add('show'); }
  function hide() { modal.classList.remove('show'); }

  closeBtn.addEventListener('click', hide);

  document.querySelectorAll('[href="#services"]').forEach(function(el) {
    el.addEventListener('click', show);
  });

  setTimeout(show, 4000);
};
document.addEventListener('DOMContentLoaded', offerModalInit);
