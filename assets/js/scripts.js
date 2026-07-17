/* Navigation*/
/* Color theme */
const themeToggle = document.querySelector('.theme-toggle');

function setTheme(theme) {
	document.documentElement.dataset.theme = theme;
	localStorage.setItem('ioniq-theme', theme);

	if (themeToggle) {
		const isDark = theme === 'dark';
		themeToggle.setAttribute('aria-pressed', isDark);
		themeToggle.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
	}
}

const savedTheme = localStorage.getItem('ioniq-theme');
const preferredTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
setTheme(savedTheme || preferredTheme);

if (themeToggle) {
	themeToggle.addEventListener('click', () => {
		setTheme(document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark');
	});
}

// Collapse the navbar by adding the top-nav-collapse class
window.onscroll = function () {
	scrollFunction();
	scrollFunctionBTT(); // back to top button
};

window.onload = function () {
	scrollFunction();
};

function scrollFunction() {
	if (document.documentElement.scrollTop > 30) {
		document.getElementById("navbarExample").classList.add("top-nav-collapse");
	} else if ( document.documentElement.scrollTop < 30 ) {
		document.getElementById("navbarExample").classList.remove("top-nav-collapse");
	}
}

// Navbar on mobile
let elements = document.querySelectorAll(".nav-link:not(.dropdown-toggle)");

for (let i = 0; i < elements.length; i++) {
	elements[i].addEventListener("click", () => {
		document.querySelector(".offcanvas-collapse").classList.toggle("open");
		document.querySelector(".navbar-toggler").classList.remove("is-open");
		document.querySelector(".navbar-toggler").setAttribute("aria-expanded", "false");
	});
}

document.querySelector(".navbar-toggler").addEventListener("click", () => {
	const mobileMenu = document.querySelector(".offcanvas-collapse");
	mobileMenu.classList.toggle("open");

	const isOpen = mobileMenu.classList.contains("open");
	document.querySelector(".navbar-toggler").classList.toggle("is-open", isOpen);
	document.querySelector(".navbar-toggler").setAttribute("aria-expanded", isOpen);
});

// Hover on desktop
function toggleDropdown(e) {
	const _d = e.target.closest(".dropdown");
	let _m = document.querySelector(".dropdown-menu", _d);

	setTimeout(
		function () {
		const shouldOpen = _d.matches(":hover");
		_m.classList.toggle("show", shouldOpen);
		_d.classList.toggle("show", shouldOpen);

		_d.setAttribute("aria-expanded", shouldOpen);
		},
		e.type === "mouseleave" ? 300 : 0
	);
}

// On hover
const dropdownCheck = document.querySelector('.dropdown');

if (dropdownCheck !== null) { 
	document.querySelector(".dropdown").addEventListener("mouseleave", toggleDropdown);
	document.querySelector(".dropdown").addEventListener("mouseover", toggleDropdown);

	// On click
	document.querySelector(".dropdown").addEventListener("click", (e) => {
		const _d = e.target.closest(".dropdown");
		let _m = document.querySelector(".dropdown-menu", _d);
		if (_d.classList.contains("show")) {
			_m.classList.remove("show");
			_d.classList.remove("show");
		} else {
			_m.classList.add("show");
			_d.classList.add("show");
		}
	});
}


/* Header typing text */
const typingText = document.querySelector('.typing-text');
if (typingText) {
	const phrases = [
		'وەرگێڕانی ڕاستەوخۆ',
		'دەیان زیکر و ئایەت',
		'گۆڕینی شێوەی ئیمۆجی',
		'پێشنیارکردنی ووشە و ئاماژەکان',
		'چەندین جوانکاری نووسین',
		'سەدان ڕووکاری هەمەڕەنگ',
		'دروستکردنی کیبۆردی تایبەت',
		'گۆڕینی شێوەی فۆنت و قەبارە',
		'دروستکردنی ڕووکار و باکگراوند',
		'بوونی دەنگی تایبەت لەکاتی نووسین'


	];
	let phraseIndex = 0;
	let characterIndex = 0;
	let isDeleting = false;

	function typeHeaderText() {
		const phrase = phrases[phraseIndex];
		typingText.textContent = phrase.slice(0, characterIndex);

		if (!isDeleting && characterIndex < phrase.length) {
			characterIndex++;
			setTimeout(typeHeaderText, 110);
		} else if (!isDeleting) {
			isDeleting = true;
			setTimeout(typeHeaderText, 1700);
		} else if (characterIndex > 0) {
			characterIndex--;
			setTimeout(typeHeaderText, 55);
		} else {
			isDeleting = false;
			phraseIndex = (phraseIndex + 1) % phrases.length;
			setTimeout(typeHeaderText, 280);
		}
	}

	typeHeaderText();
}
  

/* Card Slider - Swiper */
var cardSlider = new Swiper('.card-slider', {
	autoplay: {
		delay: 4000,
		disableOnInteraction: false
	},
	loop: true,
	navigation: {
		nextEl: '.swiper-button-next',
		prevEl: '.swiper-button-prev'
	},
	slidesPerView: 3,
	spaceBetween: 70,
	breakpoints: {
		// when window is <= 767px
		767: {
			slidesPerView: 1
		},
		// when window is <= 991px
		991: {
			slidesPerView: 2,
			spaceBetween: 40
		}
	}
});

var phoneSlider = new Swiper('.phone-slider', {
	autoplay: {
		delay: 5000,
		disableOnInteraction: false
	},
	loop: true,
	slidesPerView: 2,
	centeredSlides: true,
	spaceBetween: 10
});

/* Back To Top Button */
// Get the buttons
myButton = document.getElementById("myBtn");
downloadBtn = document.getElementById("downloadBtn");

// When the user scrolls down 20px from the top of the document, show the buttons
function scrollFunctionBTT() {
	if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
		myButton.style.display = "block";
		if (downloadBtn) downloadBtn.style.display = "flex";
	} else {
		myButton.style.display = "none";
		if (downloadBtn) downloadBtn.style.display = "none";
	}
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
	document.body.scrollTop = 0; // for Safari
	document.documentElement.scrollTop = 0; // for Chrome, Firefox, IE and Opera
}

/* Modal - Auto show after 5 seconds (once per session), closes only via زانیاری زیاتر */
const modalOverlay = document.getElementById('infoModal');
const modalDetails = document.getElementById('modalDetails');

function openModal() {
	modalOverlay.classList.add('show');
	document.body.style.overflow = 'hidden';
}

function closeModal() {
	modalOverlay.classList.remove('show');
	document.body.style.overflow = '';
}

if (!sessionStorage.getItem('ioniq-modal-shown')) {
	setTimeout(() => {
		openModal();
		sessionStorage.setItem('ioniq-modal-shown', 'true');
	}, 5000);
}

if (modalDetails) {
	modalDetails.addEventListener('click', () => {
		closeModal();
		const target = document.getElementById('details');
		if (target) {
			const offset = 80;
			const top = target.getBoundingClientRect().top + window.pageYOffset - offset;
			window.scrollTo({ top, behavior: 'smooth' });
		}
	});
}

/* Pricing toggle monthly/yearly */
const pricingSwitch = document.getElementById('pricingSwitch');
const proPrice = document.getElementById('proPrice');
const freePrice = document.getElementById('freePrice');

if (pricingSwitch && proPrice && freePrice) {
	function updatePrices(isYearly) {
		proPrice.textContent = isYearly ? proPrice.dataset.yearly : proPrice.dataset.monthly;
		freePrice.textContent = isYearly ? freePrice.dataset.yearly : freePrice.dataset.monthly;
	}
	pricingSwitch.addEventListener('change', function () {
		updatePrices(this.checked);
	});
}

/* Pricing PRO card expand/collapse */
const proCard = document.querySelector('.pricing-inner.recommended');
if (proCard) {
	proCard.addEventListener('click', function (e) {
		if (e.target.closest('.btn-light')) return;
		this.classList.toggle('expanded');
	});
}
