// Main JavaScript - Caxias Tem Turismo
// Otimizado para performance com carregamento diferido

document.addEventListener('DOMContentLoaded', function() {
    // Inicialização de componentes após DOM carregar
    initScrollProgress();
    initAOS();
    initToast();
    initBackToTop();
    initCounters();
    initFavorites();
    initNewsletter();
    initServiceWorker();
    
    // Carregar componentes pesados após interação do usuário
    loadHeavyComponents();
});

// Barra de progresso de scroll
function initScrollProgress() {
    const scrollProgress = document.getElementById('scrollProgress');
    if (!scrollProgress) return;
    
    window.addEventListener('scroll', () => {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        scrollProgress.style.width = scrolled + '%';
    }, { passive: true });
}

// Sistema de notificações toast
function initToast() {
    window.showToast = function(message) {
        const toast = document.getElementById('toastNotification');
        const toastMessage = document.getElementById('toastMessage');
        
        if (!toast || !toastMessage) return;
        
        toastMessage.textContent = message;
        toast.classList.add('show');
        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    };
}

// Inicializar AOS (Animate On Scroll)
function initAOS() {
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });
    }
}

// Botão Voltar ao Topo
function initBackToTop() {
    const backToTopButton = document.getElementById('backToTop');
    if (!backToTopButton) return;
    
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            backToTopButton.classList.add('show');
        } else {
            backToTopButton.classList.remove('show');
        }
    }, { passive: true });

    backToTopButton.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Animação de Contadores
function initCounters() {
    const animateCounter = (element) => {
        const target = parseInt(element.getAttribute('data-target'));
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        
        const updateCounter = () => {
            current += step;
            if (current < target) {
                element.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target;
            }
        };
        
        updateCounter();
    };

    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px'
    };

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                entry.target.classList.add('animated');
                animateCounter(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.stat-number').forEach(counter => {
        counterObserver.observe(counter);
    });
}

// Sistema de Favoritos
function initFavorites() {
    const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
    favorites.forEach(id => {
        const button = document.querySelector(`[onclick*="${id}"]`);
        if (button) {
            button.classList.add('active');
            button.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/>
            </svg>`;
        }
    });
}

window.toggleFavorite = function(button, destinationId) {
    event.stopPropagation();
    event.preventDefault();
    
    button.classList.toggle('active');
    let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
    
    if (button.classList.contains('active')) {
        if (!favorites.includes(destinationId)) {
            favorites.push(destinationId);
        }
        button.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/>
        </svg>`;
        showToast('✨ Adicionado aos favoritos!');
    } else {
        favorites = favorites.filter(id => id !== destinationId);
        button.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
        </svg>`;
        showToast('Removido dos favoritos');
    }
    
    localStorage.setItem('favorites', JSON.stringify(favorites));
};

// Função de Compartilhamento
window.shareDestination = function(title, url) {
    event.stopPropagation();
    event.preventDefault();
    
    const fullUrl = window.location.origin + '/' + url;
    
    if (navigator.share) {
        navigator.share({
            title: title + ' - Caxias Tem Turismo',
            text: 'Conheça ' + title + ' em Caxias do Sul!',
            url: fullUrl
        }).catch((error) => console.log('Erro ao compartilhar:', error));
    } else {
        navigator.clipboard.writeText(fullUrl).then(() => {
            showToast('Link copiado para a área de transferência!');
        });
    }
};

// Newsletter
function initNewsletter() {
    const form = document.getElementById('newsletterForm');
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        showToast('📧 Inscrição realizada com sucesso!');
        this.reset();
    });
}

// Service Worker
function initServiceWorker() {
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/js/sw.js')
                .then(registration => {
                    console.log('ServiceWorker registrado com sucesso:', registration.scope);
                })
                .catch(error => {
                    console.log('Falha ao registrar ServiceWorker:', error);
                });
        });
    }
}

// Carregar componentes pesados sob demanda
function loadHeavyComponents() {
    // Carregar mapa apenas quando usuário rolar até a seção
    const mapSection = document.getElementById('destinosMap');
    if (mapSection) {
        const mapObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    loadMapComponent();
                    mapObserver.unobserve(entry.target);
                }
            });
        }, { rootMargin: '100px' });
        
        mapObserver.observe(mapSection);
    }
    
    // Carregar Swiper apenas quando necessário
    const swiperContainer = document.querySelector('.testimonialSwiper');
    if (swiperContainer) {
        const swiperObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    loadSwiperComponent();
                    swiperObserver.unobserve(entry.target);
                }
            });
        }, { rootMargin: '100px' });
        
        swiperObserver.observe(swiperContainer);
    }
    
    // PhotoSwipe para galeria
    const gallerySection = document.getElementById('galeria');
    if (gallerySection) {
        const galleryObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    loadPhotoSwipe();
                    galleryObserver.unobserve(entry.target);
                }
            });
        }, { rootMargin: '100px' });
        
        galleryObserver.observe(gallerySection);
    }
}

// Carregar Leaflet Map
function loadMapComponent() {
    if (typeof L === 'undefined') return;
    
    const map = L.map('destinosMap').setView([-29.1678, -51.1794], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18
    }).addTo(map);

    const destinos = [
        {
            nome: 'Santa Lúcia e Vila Oliva',
            coords: [-29.065, -51.125],
            descricao: 'Agroturismo e belezas naturais',
            link: 'destinos/santa-lucia.php'
        },
        {
            nome: 'Fazenda Souza e Vila Seca',
            coords: [-29.230, -51.120],
            descricao: 'Cultura italiana e vinícolas',
            link: 'destinos/fazenda-souza.php'
        },
        {
            nome: 'Terceira Légua',
            coords: [-29.100, -51.250],
            descricao: 'Gastronomia típica italiana',
            link: 'destinos/terceira-legua.php'
        },
        {
            nome: 'Galópolis',
            coords: [-29.036, -51.080],
            descricao: 'Patrimônio histórico e natureza',
            link: 'destinos/galopolis.php'
        },
        {
            nome: 'Turismo Religioso',
            coords: [-29.168, -51.179],
            descricao: 'Templos e fé na serra',
            link: 'destinos/turismo-religioso.php'
        }
    ];

    const customIcon = L.divIcon({
        html: '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#A14C3A" viewBox="0 0 16 16"><path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/></svg>',
        className: 'custom-marker',
        iconSize: [32, 32],
        iconAnchor: [16, 32]
    });

    destinos.forEach(destino => {
        const marker = L.marker(destino.coords, { icon: customIcon }).addTo(map);
        marker.bindPopup(`
            <div style="text-align: center;">
                <strong>${destino.nome}</strong><br>
                <p style="margin: 5px 0;">${destino.descricao}</p>
                <a href="${destino.link}" class="btn btn-sm" style="background: var(--accent); color: white; text-decoration: none; padding: 5px 15px; border-radius: 5px; display: inline-block; margin-top: 5px;">Ver Detalhes</a>
            </div>
        `);
    });
}

// Carregar Swiper
function loadSwiperComponent() {
    if (typeof Swiper === 'undefined') return;
    
    new Swiper('.testimonialSwiper', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            }
        }
    });
}

// Carregar PhotoSwipe
function loadPhotoSwipe() {
    if (typeof PhotoSwipeLightbox === 'undefined') return;
    
    const lightbox = new PhotoSwipeLightbox({
        gallery: '#galeria',
        children: 'a',
        pswpModule: PhotoSwipe
    });
    lightbox.init();
}
