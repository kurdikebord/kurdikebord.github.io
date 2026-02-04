const openMenu = document.getElementById("open-menu");
const closeMenu = document.getElementById("close-menu");
const navlinks = document.getElementById("mobile-navlinks");

const openMenuHandler = () => {
    navlinks.classList.remove("-translate-x-full")
    navlinks.classList.add("translate-x-0")
}

const closeMenuHandler = () => {
    navlinks.classList.remove("translate-x-0")
    navlinks.classList.add("-translate-x-full")
}

openMenu.addEventListener("click", openMenuHandler);
closeMenu.addEventListener("click", closeMenuHandler);


// Testimonials Section Marquee
const cardsData = [
    {
        image: 'https://images.unsplash.com/photo-1633332755192-727a05c4013d?q=80&w=200',
        name: 'Sophia Carter',
        handle: '@sophiacodes',
        date: 'February 14, 2025',
        quote: 'This SaaS app has completely streamlined our onboarding process. What used to take hours now takes minutes!',
    },
    {
        image: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?q=80&w=200',
        name: 'Ethan Walker',
        handle: '@ethanwrites',
        date: 'March 3, 2025',
        quote: 'We’ve tried several tools, but nothing comes close in terms of speed and simplicity. Absolute game-changer.',
    },
    {
        image: 'https://images.unsplash.com/photo-1527980965255-d3b416303d12?w=200&auto=format&fit=crop&q=60',
        name: 'Maya Patel',
        handle: '@mayapatel',
        date: 'April 22, 2025',
        quote: 'The automation features alone have saved our team countless hours every week. Worth every penny.',
    },
    {
        image: 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?w=200&auto=format&fit=crop&q=60',
        name: 'Liam Brooks',
        handle: '@liambrooks',
        date: 'May 18, 2025',
        quote: 'Setup was ridiculously easy. Within 10 minutes, we were running live and onboarding our first customers.',
    },
];

const row1 = document.getElementById('row1');
const row2 = document.getElementById('row2');

const createCard = (card) => `
        <div class="p-4 rounded-lg mx-4 w-72 shrink-0 bg-pink-950/30 border border-pink-950">
        <div class="flex gap-2">
            <img class="size-11 rounded-full" alt="${card.name}" src="${card.image}">
            <div class="flex flex-col">
                <div class="flex items-center gap-1">
                    <p>${card.name}</p>
                    <svg class="mt-0.5" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M4.555.72a4 4 0 0 1-.297.24c-.179.12-.38.202-.59.244a4 4 0 0 1-.38.041c-.48.039-.721.058-.922.129a1.63 1.63 0 0 0-.992.992c-.071.2-.09.441-.129.922a4 4 0 0 1-.041.38 1.6 1.6 0 0 1-.245.59 3 3 0 0 1-.239.297c-.313.368-.47.551-.56.743-.213.444-.213.96 0 1.404.09.192.247.375.56.743.125.146.187.219.24.297.12.179.202.38.244.59.018.093.026.189.041.38.039.48.058.721.129.922.163.464.528.829.992.992.2.071.441.09.922.129.191.015.287.023.38.041.21.042.411.125.59.245.078.052.151.114.297.239.368.313.551.47.743.56.444.213.96.213 1.404 0 .192-.09.375-.247.743-.56.146-.125.219-.187.297-.24.179-.12.38-.202.59-.244a4 4 0 0 1 .38-.041c.48-.039.721-.058.922-.129.464-.163.829-.528.992-.992.071-.2.09-.441.129-.922a4 4 0 0 1 .041-.38c.042-.21.125-.411.245-.59.052-.078.114-.151.239-.297.313-.368.47-.551.56-.743.213-.444.213-.96 0-1.404-.09-.192-.247-.375-.56-.743a4 4 0 0 1-.24-.297 1.6 1.6 0 0 1-.244-.59 3 3 0 0 1-.041-.38c-.039-.48-.058-.721-.129-.922a1.63 1.63 0 0 0-.992-.992c-.2-.071-.441-.09-.922-.129a4 4 0 0 1-.38-.041 1.6 1.6 0 0 1-.59-.245A3 3 0 0 1 7.445.72C7.077.407 6.894.25 6.702.16a1.63 1.63 0 0 0-1.404 0c-.192.09-.375.247-.743.56m4.07 3.998a.488.488 0 0 0-.691-.69l-2.91 2.91-.958-.957a.488.488 0 0 0-.69.69l1.302 1.302c.19.191.5.191.69 0z"
                            fill="#2196F3"></path>
                    </svg>
                </div>
                <span class="text-xs text-slate-500">${card.handle}</span>
            </div>
        </div>
        <p class="text-sm pt-4 text-slate-500 line-clamp-2">
            ${card.quote}
        </p>
    </div>
    `;

const renderCards = (target) => {
    const doubled = [...cardsData, ...cardsData];
    doubled.forEach(card => target.insertAdjacentHTML('beforeend', createCard(card)));
};

renderCards(row1);
renderCards(row2);