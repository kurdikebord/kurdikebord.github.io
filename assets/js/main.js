(function () {
    //===== Prealoder

    window.onload = function () {
        window.setTimeout(fadeout, 500);
    }

    function fadeout() {
        var preloader = document.querySelector('.preloader');
        if (preloader) {
            preloader.style.opacity = '0';
            preloader.style.display = 'none';
        }
    }

    /*=====================================
    Sticky
    ======================================= */
    window.onscroll = function () {
        var header_navbar = document.querySelector(".navbar-area");
        if (header_navbar) {
            var sticky = header_navbar.offsetTop;
            if (window.pageYOffset > sticky) {
                header_navbar.classList.add("sticky");
            } else {
                header_navbar.classList.remove("sticky");
            }
        }

        // show or hide the back-top-top button
        var backToTo = document.querySelector(".scroll-top");
        if (backToTo) {
            if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
                backToTo.style.display = "flex";
            } else {
                backToTo.style.display = "none";
            }
        }

        // show or hide the get-it-now button
        var getItNow = document.querySelector(".get-it-now-wrapper");
        if (getItNow) {
            if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                getItNow.style.display = "flex";
            } else {
                getItNow.style.display = "none";
                getItNow.classList.remove("open");
            }
        }
    };

    // for menu scroll 
    var pageLink = document.querySelectorAll('.page-scroll');

    if (pageLink.length) {
        pageLink.forEach(elem => {
            elem.addEventListener('click', e => {
                e.preventDefault();
                document.querySelector(elem.getAttribute('href')).scrollIntoView({ behavior: 'smooth' });
            });
        });
    }

    //===== close navbar-collapse when a  clicked
    let navbarToggler = document.querySelector(".navbar-toggler");
    const navbarCollapse = document.querySelector(".navbar-collapse");

    if (navbarToggler && navbarCollapse) {
        document.querySelectorAll(".page-scroll").forEach(e =>
            e.addEventListener("click", () => {
                navbarToggler.classList.remove("active");
                navbarCollapse.classList.remove("show")
            })
        );
        navbarToggler.addEventListener("click", function () {
            navbarToggler.classList.toggle("active");
        });
    }


    //========= glightbox
    try {
        var myGallery = GLightbox({
            'href': 'https://www.youtube.com/watch?v=8RgXp4gPmS4',
            'type': 'video',
            'width': 900,
            'autoplayVideos': true,
        });
    } catch (e) {}


    //======= tiny slider for Testimonial
    try {
        tns({
            container: '.testimonial-slider',
            items: 1,
            slideBy: 'page',
            autoplay: false,
            speed: 800,
            navPosition: 'bottom',
            autoplayTimeout: 8000,
            controls: false,
            nav: true,
            mouseDrag: true,
        });
    } catch (e) {}


    //========= glightbox for portfolio
    try {
        var portfolioImage = GLightbox({
            selector: '.glightbox',
            'touchNavigation': true,
            'loop': false,
            'autoplayVideos': true,
        });
    } catch (e) {}


    //======= Counter Up
    try {
        var cu = new counterUp({
            start: 0,
            duration: 2000,
            intvalues: true,
            interval: 100,
            append: " ",
        });
        cu.start();
    } catch (e) {}

    //===== Typewriter Effect
    const typingText = document.querySelector('.typing-text');
    if (typingText) {
        const words = ['سەدان زیکر و ئایەت', 'چەندین فۆنتی جیاواز', 'شێوەی تایبەتی ئیمۆجی', 'بەشی لەبەرگیراوەکان', 'ڕووکاری هەمەڕەنگ', 'وەرگێڕانی ڕاستەوخۆ'];
        let wordIndex = 0;
        let charIndex = 0;
        let isDeleting = false;

        function type() {
            const currentWord = words[wordIndex];
            if (isDeleting) {
                typingText.textContent = currentWord.substring(0, charIndex - 1);
                charIndex--;
            } else {
                typingText.textContent = currentWord.substring(0, charIndex + 1);
                charIndex++;
            }

            if (!isDeleting && charIndex === currentWord.length) {
                isDeleting = true;
                setTimeout(type, 2000);
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                wordIndex = (wordIndex + 1) % words.length;
                setTimeout(type, 500);
            } else {
                setTimeout(type, isDeleting ? 80 : 120);
            }
        }
        setTimeout(type, 1500);
    }

    //===== Dark/Light Mode Toggle
    const themeSwitchInput = document.getElementById('theme-switch-input');
    const body = document.body;
    const storedTheme = localStorage.getItem('theme');

    if (storedTheme === 'dark') {
        body.classList.add('dark-mode');
        if (themeSwitchInput) themeSwitchInput.checked = true;
    }

    if (themeSwitchInput) {
        themeSwitchInput.addEventListener('change', function () {
            if (this.checked) {
                body.classList.add('dark-mode');
                localStorage.setItem('theme', 'dark');
            } else {
                body.classList.remove('dark-mode');
                localStorage.setItem('theme', 'light');
            }
        });
    }

    //===== WOW
    try {
        new WOW({ mobile: false }).init();
    } catch (e) {}

})();
