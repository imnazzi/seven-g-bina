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

    // Active nav indicator follows whichever section a reference line (just below
    // the fixed nav, ~28% down the viewport) is currently passing through. This
    // works for sections of any height — the old threshold-based spy got stuck on
    // tall sections like Services/Projects.
    const navLinks = Array.from(document.querySelectorAll('.nav-link'));
    const linkFor = new Map();
    navLinks.forEach((link) => {
        const href = link.getAttribute('href') || '';
        if (href.startsWith('#') && href.length > 1) linkFor.set(href.slice(1), link);
    });

    const spySections = [...linkFor.keys()]
        .map((id) => document.getElementById(id))
        .filter(Boolean);

    if (spySections.length) {
        const applyActiveNav = () => {
            const line = window.innerHeight * 0.28;
            let currentId = null;
            for (const el of spySections) {
                const r = el.getBoundingClientRect();
                if (r.top <= line && r.bottom > line) { currentId = el.id; break; }
            }
            if (!currentId) return; // between page top / past last section — keep last state
            navLinks.forEach((l) => l.classList.toggle('active', l === linkFor.get(currentId)));
        };

        // Fire on section-boundary crossings via a 1px band at the reference line…
        const spyObserver = new IntersectionObserver(applyActiveNav, {
            threshold: 0,
            rootMargin: '-28% 0px -71% 0px'
        });
        spySections.forEach((el) => spyObserver.observe(el));

        // …plus a rAF-throttled scroll/resize fallback so it never lags or sticks.
        let spyTick = false;
        const scheduleSpy = () => {
            if (spyTick) return;
            spyTick = true;
            requestAnimationFrame(() => { spyTick = false; applyActiveNav(); });
        };
        window.addEventListener('scroll', scheduleSpy, { passive: true });
        window.addEventListener('resize', scheduleSpy, { passive: true });
        applyActiveNav();
    }



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


})();

// ── Services carousel: pause the CSS marquee while a touch is held ────────────
// Auto-motion and hover-pause are pure CSS. This only keeps the animation from
// fighting a finger on mobile, then resumes shortly after the touch ends.
(() => {
    const marquee = document.querySelector('[data-service-marquee]');
    if (!marquee) return;

    let resumeTimer;
    const pause = () => {
        clearTimeout(resumeTimer);
        marquee.classList.add('is-paused');
    };
    const resumeSoon = () => {
        clearTimeout(resumeTimer);
        resumeTimer = setTimeout(() => marquee.classList.remove('is-paused'), 1600);
    };

    marquee.addEventListener('touchstart', pause, { passive: true });
    marquee.addEventListener('touchend', resumeSoon, { passive: true });
    marquee.addEventListener('touchcancel', resumeSoon, { passive: true });
})();

// ── Executive Project Portfolio Filter Tabs & Interactive Detail Modal + Gallery ──
(function initProjectPortfolio() {
    // app.js is loaded with `defer`, but stay safe if the DOM is not parsed yet.
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initProjectPortfolio, { once: true });
        return;
    }

    const modal = document.getElementById('project-modal');
    if (!modal) {
        console.error('Project modal (#project-modal) not found in DOM.');
        return;
    }

    const modalTitle = document.getElementById('modal-title');
    const modalCategory = document.getElementById('modal-category');
    const modalClient = document.getElementById('modal-client');
    const modalDescription = document.getElementById('modal-description');
    const modalImage = document.getElementById('modal-image');
    const galleryPrev = document.getElementById('modal-prev');
    const galleryNext = document.getElementById('modal-next');
    const galleryCounter = document.getElementById('modal-image-counter');
    const galleryRoot = document.getElementById('modal-gallery');
    const closeModalBtn = document.getElementById('close-modal');

    // ── Category filter tabs (only wired up if the markup exists) ───────────
    const filterBtns = document.querySelectorAll('.project-filter-btn');
    if (filterBtns.length) {
        filterBtns.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const filter = btn.dataset.filter;
                filterBtns.forEach((b) => b.classList.remove('active'));
                btn.classList.add('active');
                document.querySelectorAll('.project-card').forEach((card) => {
                    const match = filter === 'all' || card.dataset.category === filter;
                    card.classList.toggle('is-hidden', !match);
                    if (match) {
                        card.style.opacity = '1';
                        card.style.transform = 'scale(1)';
                    }
                });
            });
        });
    }

    // ── Per-project image gallery state ────────────────────────────────────
    let galleryImages = [];
    let galleryIndex = 0;

    function parseImages(card) {
        const raw = card.getAttribute('data-images');
        if (raw) {
            try {
                const arr = JSON.parse(raw);
                if (Array.isArray(arr) && arr.length) return arr.filter(Boolean);
            } catch (err) {
                /* fall through to the single-image attribute */
            }
        }
        const single = card.getAttribute('data-image');
        return single ? [single] : [];
    }

    function showGalleryImage(index, animate) {
        const total = galleryImages.length;
        if (!total || !modalImage) return;
        galleryIndex = ((index % total) + total) % total; // wrap both directions
        const src = galleryImages[galleryIndex];

        const apply = () => {
            modalImage.src = src;
            modalImage.style.opacity = '1';
        };

        if (animate) {
            modalImage.style.opacity = '0';
            const pre = new Image();
            pre.onload = apply;
            pre.onerror = apply;
            pre.src = src;
        } else {
            apply();
        }

        if (galleryCounter) {
            galleryCounter.textContent =
                String(galleryIndex + 1).padStart(2, '0') + ' / ' + String(total).padStart(2, '0');
        }
    }

    function syncGalleryControls() {
        const multi = galleryImages.length > 1;
        [galleryPrev, galleryNext, galleryCounter].forEach((el) => {
            if (el) el.classList.toggle('hidden', !multi);
        });
    }

    function openProjectModal(card) {
        if (modalTitle) modalTitle.textContent = card.getAttribute('data-title') || '';
        if (modalCategory) modalCategory.textContent = card.getAttribute('data-category') || '';
        if (modalClient) modalClient.textContent = card.getAttribute('data-client') || '';
        if (modalDescription) modalDescription.textContent = card.getAttribute('data-description') || '';

        // This project's images only — never shared between projects.
        galleryImages = parseImages(card);
        galleryIndex = 0;
        if (modalImage) modalImage.alt = card.getAttribute('data-title') || '';
        syncGalleryControls();
        showGalleryImage(0, false);

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    // ── Open: delegated so a click anywhere on a card (image, text, footer)
    //    always resolves to its .project-card, regardless of pointer-events. ──
    document.addEventListener('click', function (e) {
        if (e.target.closest('#project-modal')) return; // clicks inside the modal handled below
        const card = e.target.closest('.project-card');
        if (!card) return;
        e.preventDefault();
        openProjectModal(card);
    });

    // ── Modal-internal controls ──────────────────────────────────────────
    if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
    });
    if (galleryPrev) galleryPrev.addEventListener('click', (e) => {
        e.stopPropagation();
        showGalleryImage(galleryIndex - 1, true);
    });
    if (galleryNext) galleryNext.addEventListener('click', (e) => {
        e.stopPropagation();
        showGalleryImage(galleryIndex + 1, true);
    });

    // Touch swipe on the gallery image (mobile)
    if (galleryRoot) {
        let touchStartX = 0;
        let touchStartY = 0;
        galleryRoot.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].clientX;
            touchStartY = e.changedTouches[0].clientY;
        }, { passive: true });
        galleryRoot.addEventListener('touchend', (e) => {
            if (galleryImages.length < 2) return;
            const dx = e.changedTouches[0].clientX - touchStartX;
            const dy = e.changedTouches[0].clientY - touchStartY;
            if (Math.abs(dx) > 45 && Math.abs(dx) > Math.abs(dy)) {
                showGalleryImage(galleryIndex + (dx < 0 ? 1 : -1), true);
            }
        }, { passive: true });
    }

    // Keyboard: Escape closes, arrows step through the project gallery
    window.addEventListener('keydown', function (e) {
        if (modal.classList.contains('hidden')) return;
        if (e.key === 'Escape') closeModal();
        if (e.key === 'ArrowLeft' && galleryImages.length > 1) showGalleryImage(galleryIndex - 1, true);
        if (e.key === 'ArrowRight' && galleryImages.length > 1) showGalleryImage(galleryIndex + 1, true);
    });
})();


// ── Floating WhatsApp contact widget: toggle, outside-click, Esc ──────────────
(function initWhatsAppWidget() {
    const widget = document.querySelector('[data-wa-widget]');
    if (!widget) return;

    const toggle = widget.querySelector('[data-wa-toggle]');
    const panel = document.getElementById('wa-panel');
    const cta = widget.querySelector('[data-wa-cta]');
    if (!toggle || !panel) return;

    function setOpen(open) {
        widget.classList.toggle('is-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        panel.setAttribute('aria-hidden', open ? 'false' : 'true');
    }

    const isOpen = () => widget.classList.contains('is-open');

    toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        setOpen(!isOpen());
    });

    // Click outside the widget closes the panel.
    document.addEventListener('click', function (e) {
        if (isOpen() && !widget.contains(e.target)) setOpen(false);
    });

    // Esc closes and returns focus to the toggle.
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && isOpen()) {
            setOpen(false);
            toggle.focus();
        }
    });

    // Leaving for WhatsApp: collapse so the panel isn't open on return.
    if (cta) cta.addEventListener('click', function () { setOpen(false); });
})();

// ── Certificate lightbox (SSM Certificate of Incorporation) ──────────────────
(function initCertLightbox() {
    const lb = document.getElementById('cert-lightbox');
    const opener = document.querySelector('[data-cert-open]');
    if (!lb || !opener) return;

    const closeBtn = lb.querySelector('[data-cert-close]');
    const isOpen = () => lb.classList.contains('is-open');

    const open = () => {
        lb.classList.add('is-open');
        document.body.style.overflow = 'hidden';
        if (closeBtn) closeBtn.focus();
    };
    const close = () => {
        lb.classList.remove('is-open');
        document.body.style.overflow = '';
        opener.focus();
    };

    opener.addEventListener('click', open);
    if (closeBtn) closeBtn.addEventListener('click', close);
    lb.addEventListener('click', (e) => { if (e.target === lb) close(); });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && isOpen()) close();
    });
})();

/* iOS/Safari safety nets — no visual change: force reveal if IntersectionObserver is missing,
       and promote lazy images to eager after load (Safari can stall lazy-load inside animated containers). */
    (function () {
        if (!('IntersectionObserver' in window)) {
            document.querySelectorAll('.reveal').forEach(function (el) { el.classList.add('is-visible'); });
        }
        var ua = navigator.userAgent;
        var isSafari = /Safari/i.test(ua) && !/Chrome|CriOS|Chromium|Android|Edg|FxiOS/i.test(ua);
        var isIOS = /iP(hone|ad|od)/i.test(ua) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
        if (!(isSafari || isIOS)) return;
        window.addEventListener('load', function () {
            setTimeout(function () {
                document.querySelectorAll('img[loading="lazy"]').forEach(function (img) {
                    img.loading = 'eager';
                    if (!img.complete) { var src = img.getAttribute('src'); if (src) img.setAttribute('src', src); }
                });
            }, 800);
            setTimeout(function () {
                document.querySelectorAll('.reveal:not(.is-visible)').forEach(function (el) {
                    if (el.getBoundingClientRect().top < window.innerHeight * 1.5) el.classList.add('is-visible');
                });
            }, 4000);
        });
    })();
