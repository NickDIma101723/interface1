function fadeInOnScroll() {
    const elements = document.querySelectorAll('.fade-in:not(.visible)');
    
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 100;
        
        if (elementTop < window.innerHeight - elementVisible) {
            element.classList.add('visible');
        }
    });
}

function initAnimations() {
 
    if (window.location.pathname.endsWith('index.html') || window.location.pathname === '/') {
        const heroSection = document.querySelector('section[class*="pt-32"]');
        if (heroSection) {
            const heroContent = heroSection.querySelector('.fade-in, .absolute');
            if (heroContent && !heroContent.classList.contains('fade-in')) {
                heroContent.classList.add('fade-in', 'immediate');
            }
        }
    }
   
    const elementsInView = document.querySelectorAll('.fade-in');
    elementsInView.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        if (elementTop < window.innerHeight - 50) {
            element.classList.add('visible');
        }
    });
    
    window.addEventListener('scroll', fadeInOnScroll);
    window.addEventListener('load', fadeInOnScroll);
    setTimeout(fadeInOnScroll, 100);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAnimations);
} else {
    initAnimations();
}