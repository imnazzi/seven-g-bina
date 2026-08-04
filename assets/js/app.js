(() => {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const supportsFinePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

    // Animation section: scroll reveal variants are triggered by IntersectionObserver, not scroll listeners.
    const revealElements = document.querySelectorAll('.reveal');
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.16,
        rootMargin: '0px 0px -8% 0px'
    });

    revealElements.forEach((element) => revealObserver.observe(element));

    // Animation section: navbar material changes when the hero top sentinel leaves the viewport.
    const sentinel = document.querySelector('.top-sentinel');
    if (sentinel) {
        const navObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                document.body.classList.toggle('nav-solid', !entry.isIntersecting);
            });
        }, { threshold: 0 });

        navObserver.observe(sentinel);
    }

    // Animation section: active navigation indicator follows the visible section.
    const navLinks = Array.from(document.querySelectorAll('.nav-link'));
    const sectionIds = navLinks
        .map((link) => link.getAttribute('href'))
        .filter((href) => href && href.startsWith('#'))
        .map((href) => href.slice(1));

    const sectionObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }

            navLinks.forEach((link) => {
                link.classList.toggle('active', link.getAttribute('href') === `#${entry.target.id}`);
            });
        });
    }, {
        threshold: 0.34,
        rootMargin: '-20% 0px -48% 0px'
    });

    sectionIds.forEach((id) => {
        const section = document.getElementById(id);
        if (section) {
            sectionObserver.observe(section);
        }
    });

    // Animation section: counters animate once when visible, using real company facts only.
    const counters = document.querySelectorAll('[data-counter]');
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }

            const counter = entry.target;
            const target = Number(counter.dataset.target || '0');

            if (reduceMotion) {
                counter.textContent = target.toLocaleString('en-MY');
                counterObserver.unobserve(counter);
                return;
            }

            let frame = 0;
            const totalFrames = 58;

            const tick = () => {
                frame += 1;
                const progress = Math.min(frame / totalFrames, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                counter.textContent = Math.round(target * eased).toLocaleString('en-MY');

                if (progress < 1) {
                    requestAnimationFrame(tick);
                }
            };

            requestAnimationFrame(tick);
            counterObserver.unobserve(counter);
        });
    }, { threshold: 0.6 });

    counters.forEach((counter) => counterObserver.observe(counter));

    // Animation section: magnetic buttons move toward the pointer using transform only.
    if (!reduceMotion && supportsFinePointer) {
        document.querySelectorAll('.magnetic-target').forEach((target) => {
            target.addEventListener('pointermove', (event) => {
                const rect = target.getBoundingClientRect();
                const x = event.clientX - rect.left - rect.width / 2;
                const y = event.clientY - rect.top - rect.height / 2;
                target.style.transform = `translate3d(${x * 0.14}px, ${y * 0.18}px, 0)`;
            });

            target.addEventListener('pointerleave', () => {
                target.style.transform = 'translate3d(0, 0, 0)';
            });
        });
    }

    // Animation section: click ripple gives buttons tactile feedback without changing layout.
    document.querySelectorAll('.btn').forEach((button) => {
        button.addEventListener('click', (event) => {
            if (reduceMotion) {
                return;
            }

            const rect = button.getBoundingClientRect();
            const ripple = document.createElement('span');
            ripple.className = 'ripple';
            ripple.style.left = `${event.clientX - rect.left}px`;
            ripple.style.top = `${event.clientY - rect.top}px`;
            button.appendChild(ripple);

            ripple.addEventListener('animationend', () => ripple.remove(), { once: true });
        });
    });

    // Animation section: parallax-ready elements receive a CSS variable during idle animation frames only after pointer movement.
    if (!reduceMotion) {
        const parallaxTargets = document.querySelectorAll('[data-parallax]');
        let pointerY = 0;

        window.addEventListener('pointermove', (event) => {
            pointerY = event.clientY - window.innerHeight / 2;
        }, { passive: true });

        const animateDepth = () => {
            parallaxTargets.forEach((target) => {
                const strength = Number(target.dataset.parallax || '0.04');
                target.style.transform = `translate3d(0, ${pointerY * strength}px, 0)`;
            });
            requestAnimationFrame(animateDepth);
        };

        requestAnimationFrame(animateDepth);
    }
})();

// ── Media Gallery Horizontal Slider ──────────────────────────────────────────
(() => {
    const track = document.getElementById('media-slider-track');
    const prevBtn = document.getElementById('media-prev-btn');
    const nextBtn = document.getElementById('media-next-btn');
    const dotsContainer = document.getElementById('media-slider-dots');
    const counterEl = document.getElementById('media-slider-counter');

    if (!track) return;

    const cards = Array.from(track.querySelectorAll('.media-card'));
    if (!cards.length) return;

    // Create pagination dots dynamically
    cards.forEach((_, index) => {
        const dot = document.createElement('button');
        dot.className = `media-slider-dot ${index === 0 ? 'active' : ''}`;
        dot.setAttribute('aria-label', `Go to slide ${index + 1}`);
        dot.addEventListener('click', () => {
            cards[index].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'start' });
        });
        if (dotsContainer) dotsContainer.appendChild(dot);
    });

    const dots = dotsContainer ? Array.from(dotsContainer.children) : [];

    // Scroll button handlers
    const getScrollAmount = () => {
        const cardWidth = cards[0].getBoundingClientRect().width;
        return cardWidth + 20; // card width + gap
    };

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            track.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            track.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
        });
    }

    // Active dot update on scroll
    let scrollTimeout;
    track.addEventListener('scroll', () => {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            const trackLeft = track.getBoundingClientRect().left;
            let activeIdx = 0;
            let minDiff = Infinity;

            cards.forEach((card, idx) => {
                const diff = Math.abs(card.getBoundingClientRect().left - trackLeft);
                if (diff < minDiff) {
                    minDiff = diff;
                    activeIdx = idx;
                }
            });

            dots.forEach((dot, idx) => {
                dot.classList.toggle('active', idx === activeIdx);
            });

            if (counterEl) {
                counterEl.textContent = `Asset ${activeIdx + 1} of ${cards.length}`;
            }
        }, 50);
    }, { passive: true });
})();

// ── Lightbox Modal with Entrance/Exit Animations ────────────────────────────
(() => {
    const lb = document.getElementById('gallery-lightbox');
    const lbImg = document.getElementById('lightbox-img');
    const lbCaption = document.getElementById('lightbox-caption');
    const lbCounter = document.getElementById('lightbox-counter');
    const closeBtn = document.getElementById('lightbox-close-btn');
    const prevBtn = document.getElementById('lightbox-prev-btn');
    const nextBtn = document.getElementById('lightbox-next-btn');
    const backdrop = lb ? lb.querySelector('.lightbox-backdrop') : null;

    if (!lb || !lbImg) return;

    const cards = Array.from(document.querySelectorAll('[data-lightbox-src]'));
    let currentIndex = 0;

    function loadSlide(index) {
        const total = cards.length;
        if (!total) return;
        currentIndex = ((index % total) + total) % total;

        const card = cards[currentIndex];
        const src = card.dataset.lightboxSrc;
        const caption = card.dataset.lightboxCaption || '';

        lbImg.style.opacity = '0.3';
        const tmp = new Image();
        tmp.onload = () => {
            lbImg.src = src;
            lbImg.alt = caption;
            lbImg.style.opacity = '1';
        };
        tmp.onerror = () => {
            lbImg.src = src;
            lbImg.style.opacity = '1';
        };
        tmp.src = src;

        if (lbCaption) lbCaption.textContent = caption;
        if (lbCounter) lbCounter.textContent = `${currentIndex + 1} / ${total}`;
    }

    function openLightbox(index) {
        lb.removeAttribute('hidden');
        document.body.style.overflow = 'hidden';
        loadSlide(index);

        // Trigger entrance animation frame
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                lb.classList.add('is-open');
            });
        });
        if (closeBtn) closeBtn.focus();
    }

    function closeLightbox() {
        lb.classList.remove('is-open');
        document.body.style.overflow = '';

        // Wait for CSS transition (350ms) to finish before setting hidden
        setTimeout(() => {
            if (!lb.classList.contains('is-open')) {
                lb.setAttribute('hidden', '');
            }
        }, 350);
    }

    cards.forEach((card, i) => {
        card.addEventListener('click', () => openLightbox(i));
        card.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                openLightbox(i);
            }
        });
    });

    if (prevBtn) prevBtn.addEventListener('click', (e) => { e.stopPropagation(); loadSlide(currentIndex - 1); });
    if (nextBtn) nextBtn.addEventListener('click', (e) => { e.stopPropagation(); loadSlide(currentIndex + 1); });
    if (closeBtn) closeBtn.addEventListener('click', closeLightbox);
    if (backdrop) backdrop.addEventListener('click', closeLightbox);

    document.addEventListener('keydown', (e) => {
        if (lb.hasAttribute('hidden')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') loadSlide(currentIndex - 1);
        if (e.key === 'ArrowRight') loadSlide(currentIndex + 1);
    });
})();

