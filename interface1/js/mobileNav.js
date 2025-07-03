if (typeof mobileBtn === 'undefined') {
    var mobileBtn = document.getElementById('mobile-menu-btn');
}
if (typeof mobileMenu === 'undefined') {
    var mobileMenu = document.getElementById('mobile-menu');
}
if (typeof mobileCloseBtn === 'undefined') {
    var mobileCloseBtn = document.getElementById('mobile-close-btn');
}

function openMobileMenu() {
    mobileMenu.classList.remove('opacity-0', 'invisible');
    mobileMenu.classList.add('opacity-100', 'visible');
    const menuPanel = mobileMenu.querySelector('.transform');
    menuPanel.classList.remove('translate-x-full');
    menuPanel.classList.add('translate-x-0');
    document.body.style.overflow = 'hidden';
    
    const spans = mobileBtn.querySelectorAll('span');
    spans[0].style.transform = 'rotate(45deg) translate(6px, 6px)';
    spans[1].style.opacity = '0';
    spans[2].style.transform = 'rotate(-45deg) translate(6px, -6px)';
}

function closeMobileMenu() {
    mobileMenu.classList.remove('opacity-100', 'visible');
    mobileMenu.classList.add('opacity-0', 'invisible');
    const menuPanel = mobileMenu.querySelector('.transform');
    menuPanel.classList.remove('translate-x-0');
    menuPanel.classList.add('translate-x-full');
    document.body.style.overflow = '';
    
    const spans = mobileBtn.querySelectorAll('span');
    spans[0].style.transform = '';
    spans[1].style.opacity = '';
    spans[2].style.transform = '';
}

if (mobileBtn && mobileCloseBtn && mobileMenu) {
    mobileBtn.addEventListener('click', openMobileMenu);
    mobileCloseBtn.addEventListener('click', closeMobileMenu);

   
    mobileMenu.addEventListener('click', (e) => {
        if (e.target === mobileMenu) {
            closeMobileMenu();
        }
    });

    document.querySelectorAll('#mobile-menu a').forEach(item => {
        item.addEventListener('click', closeMobileMenu);
    });
}

let fadeInTriggered = false;

function initFadeInAnimations() {
    const fadeInElements = document.querySelectorAll('.fade-in');
    
    fadeInElements.forEach(element => {
        if (!element.classList.contains('visible')) {
            element.classList.add('immediate');
        }
    });

    const observerOptions = {
        threshold: 0.15,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                entry.target.classList.remove('immediate');
            }
        });
    }, observerOptions);

    fadeInElements.forEach(element => {
        observer.observe(element);
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        if (!fadeInTriggered) {
            initFadeInAnimations();
            fadeInTriggered = true;
        }
    });
} else {
    if (!fadeInTriggered) {
        initFadeInAnimations();
        fadeInTriggered = true;
    }
}

