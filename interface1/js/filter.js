        const filterButtons = document.querySelectorAll('.filter-btn');
        const productItems = document.querySelectorAll('.product-item');

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                const filter = button.getAttribute('data-filter');
                filterButtons.forEach(btn => {
                    btn.classList.remove('active', 'bg-black', 'text-white');
                    btn.classList.add('bg-gray-100', 'text-black');
                });
                button.classList.add('active', 'bg-black', 'text-white');
                button.classList.remove('bg-gray-100', 'text-black');
                productItems.forEach(item => {
                    if (filter === 'all' || item.getAttribute('data-category') === filter) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

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

        window.addEventListener('scroll', fadeInOnScroll);
        window.addEventListener('load', fadeInOnScroll);
        setTimeout(fadeInOnScroll, 100);