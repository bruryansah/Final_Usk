/* ============================================================
   NEXELABS – script.js
   Vanilla JS: Navbar, Animations, Greeting, Active Nav
   ============================================================ */

(function () {
    'use strict';

    // ============================================================
    // 1. NAVBAR SCROLL DETECTION
    // ============================================================
    const navbar  = document.getElementById('navbar');
    const hero    = document.querySelector('.section-hero');
    let heroBottom = 0;

    function updateHeroBottom() {
        if (hero) {
            heroBottom = hero.offsetTop + hero.offsetHeight;
        }
    }

    function handleNavbarScroll() {
        if (!navbar) return;
        const scrollY = window.scrollY || window.pageYOffset;

        if (scrollY > heroBottom - 80) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    }

    window.addEventListener('scroll', handleNavbarScroll, { passive: true });
    window.addEventListener('resize', () => { updateHeroBottom(); handleNavbarScroll(); });
    updateHeroBottom();
    handleNavbarScroll();


    // ============================================================
    // 2. HAMBURGER MENU (mobile)
    // ============================================================
    const hamburger = document.getElementById('hamburger');
    const navLinks  = document.getElementById('navLinks');
    const navAuth   = document.querySelector('.nav-auth');

    if (hamburger) {
        hamburger.addEventListener('click', () => {
            const isOpen = navLinks && navLinks.classList.toggle('open');
            if (navAuth) navAuth.classList.toggle('open', isOpen);
            hamburger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

            // Animate spans
            const spans = hamburger.querySelectorAll('span');
            if (isOpen) {
                spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
                spans[1].style.opacity   = '0';
                spans[2].style.transform = 'rotate(-45deg) translate(5px, -5px)';
            } else {
                spans.forEach(s => { s.style.transform = ''; s.style.opacity = ''; });
            }
        });
    }

    // Close mobile menu on link click
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            if (navLinks) navLinks.classList.remove('open');
            if (navAuth) navAuth.classList.remove('open');
            if (hamburger) {
                hamburger.querySelectorAll('span').forEach(s => { s.style.transform = ''; s.style.opacity = ''; });
            }
        });
    });


    // ============================================================
    // 3. SMOOTH SCROLL NAVIGATION
    // ============================================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                const navH = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--nav-h')) || 72;
                const top  = target.getBoundingClientRect().top + window.scrollY - navH;
                window.scrollTo({ top, behavior: 'smooth' });
            }
        });
    });


    // ============================================================
    // 4. ACTIVE MENU HIGHLIGHT (Intersection Observer)
    // ============================================================
    const sections    = document.querySelectorAll('section[id]');
    const navLinkEls  = document.querySelectorAll('.nav-link[data-section]');

    const sectionObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.getAttribute('id');
                navLinkEls.forEach(link => {
                    link.classList.toggle('active', link.dataset.section === id);
                });
            }
        });
    }, {
        threshold: 0.35,
        rootMargin: '-10% 0px -10% 0px'
    });

    sections.forEach(section => sectionObserver.observe(section));


    // ============================================================
    // 5. REVEAL ANIMATIONS (Intersection Observer)
    // ============================================================

    // Generic .reveal-up elements
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.12,
        rootMargin: '0px 0px -40px 0px'
    });

    document.querySelectorAll('.reveal-up').forEach(el => revealObserver.observe(el));

    // Product cards stagger
    const cardObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Add visible class with stagger preserved via CSS --card-delay
                entry.target.classList.add('visible');
                cardObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.08,
        rootMargin: '0px 0px -30px 0px'
    });

    document.querySelectorAll('.product-card').forEach(card => cardObserver.observe(card));


    // ============================================================
    // 6. GREETING WAKTU DINAMIS
    // ============================================================
    const greetingEl = document.getElementById('greetingText');

    function getGreeting() {
        const hour = new Date().getHours();
        if (hour >= 5  && hour <= 10) return 'Pagi';
        if (hour >= 11 && hour <= 14) return 'Siang';
        if (hour >= 15 && hour <= 17) return 'Sore';
        return 'Malam';
    }

    if (greetingEl && window.NEXE && window.NEXE.isAuth) {
        const salam = getGreeting();
        const name  = window.NEXE.userName || '';
        greetingEl.textContent = `Selamat ${salam}, ${name}`;
    }


    // ============================================================
    // 7. CARD IMAGE FALLBACK
    //    (handles empty src – shows placeholder)
    // ============================================================
    document.querySelectorAll('.card-img, .hero-img, .about-img').forEach(img => {
        if (!img.src || img.src === '' || img.src === window.location.href) {
            img.style.display = 'none';
        }
    });


    // ============================================================
    // 8. SCROLL INDICATOR HIDE ON SCROLL
    // ============================================================
    const scrollIndicator = document.querySelector('.scroll-indicator');
    if (scrollIndicator) {
        window.addEventListener('scroll', () => {
            scrollIndicator.style.opacity = window.scrollY > 60 ? '0' : '1';
            scrollIndicator.style.transform = window.scrollY > 60 ? 'translateY(10px)' : '';
        }, { passive: true });

        scrollIndicator.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
    }


    // ============================================================
    // 9. BUTTON HOVER RIPPLE (subtle)
    // ============================================================
    document.querySelectorAll('.btn-primary, .btn-register, .btn-buy').forEach(btn => {
        btn.addEventListener('click', function (e) {
            const rect   = btn.getBoundingClientRect();
            const ripple = document.createElement('span');
            const size   = Math.max(rect.width, rect.height);

            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${e.clientX - rect.left - size / 2}px;
                top: ${e.clientY - rect.top - size / 2}px;
                background: rgba(255,255,255,0.25);
                border-radius: 50%;
                transform: scale(0);
                animation: rippleAnim 0.6s ease-out forwards;
                pointer-events: none;
            `;

            // Ensure button has position:relative
            if (getComputedStyle(btn).position === 'static') {
                btn.style.position = 'relative';
            }
            btn.style.overflow = 'hidden';
            btn.appendChild(ripple);
            setTimeout(() => ripple.remove(), 650);
        });
    });

    // Inject ripple keyframe once
    if (!document.getElementById('nexe-ripple-style')) {
        const style = document.createElement('style');
        style.id = 'nexe-ripple-style';
        style.textContent = `
            @keyframes rippleAnim {
                to { transform: scale(3); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }

    console.log('%c⚡ NexeLabs', 'color:#5a64d3;font-size:20px;font-weight:bold;');
    console.log('%cWebsite siap pakai berbasis Laravel & MySQL', 'color:#cfcfcf;font-size:12px;');

})();

    // ============================================================
    // 10. MODAL PEMBELIAN
    // ============================================================

    /**
     * Buka modal beli berdasarkan kode produk
     */
    window.openModalBeli = function (kode) {
        const modal = document.getElementById('modal-beli-' + kode);
        if (!modal) return;
        modal.classList.add('open');
        document.body.classList.add('modal-open');
        // Focus trap: fokus ke tombol close
        const closeBtn = modal.querySelector('.modal-close');
        if (closeBtn) setTimeout(() => closeBtn.focus(), 100);
    };

    /**
     * Tutup modal beli
     */
    window.closeModalBeli = function (kode) {
        const modal = document.getElementById('modal-beli-' + kode);
        if (!modal) return;
        modal.classList.remove('open');
        document.body.classList.remove('modal-open');
    };

    /**
     * Tutup jika klik overlay (bukan box)
     */
    window.closeModalBeliIfOverlay = function (e, overlay) {
        if (e.target === overlay) {
            overlay.classList.remove('open');
            document.body.classList.remove('modal-open');
        }
    };

    /**
     * Tombol +/− untuk qty input
     */
    window.changeQty = function (inputId, delta) {
        const input = document.getElementById(inputId);
        if (!input) return;

        let val = parseInt(input.value) || 1;
        const min = parseInt(input.min) || 1;
        const max = input.max ? parseInt(input.max) : Infinity;

        val = Math.max(min, Math.min(max, val + delta));
        input.value = val;

        // Update total harga jika ada elemen total
        const prodId   = input.dataset.id;
        const totalEl  = document.getElementById('total-' + prodId);
        if (totalEl) {
            // Ambil harga dari hidden input di form yang sama
            const form     = input.closest('form');
            const hargaEl  = form ? form.querySelector('input[name="harga"]') : null;
            if (hargaEl) {
                const harga = parseInt(hargaEl.value) || 0;
                const total = harga * val;
                totalEl.innerHTML = 'Total: <strong>' + formatRupiah(total) + '</strong>';
            }
        }
    };

    /**
     * Format angka ke format Rupiah
     */
    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID');
    }

    /**
     * Update total saat input qty diketik manual
     */
    document.querySelectorAll('.qty-input').forEach(function (input) {
        input.addEventListener('input', function () {
            const prodId  = this.dataset.id;
            const totalEl = document.getElementById('total-' + prodId);
            if (!totalEl) return;

            const form    = this.closest('form');
            const hargaEl = form ? form.querySelector('input[name="harga"]') : null;
            if (!hargaEl) return;

            const harga = parseInt(hargaEl.value) || 0;
            const qty   = parseInt(this.value) || 1;
            totalEl.innerHTML = 'Total: <strong>' + formatRupiah(harga * qty) + '</strong>';
        });
    });

    /**
     * Tutup modal dengan tombol Escape
     */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.open').forEach(function (m) {
                m.classList.remove('open');
                document.body.classList.remove('modal-open');
            });
        }
    });

    // ============================================================
    // 11. MODAL KERANJANG
    // ============================================================

    /** Buka modal keranjang */
    window.openModalKeranjang = function (id) {
        const modal = document.getElementById('modal-keranjang-' + id);
        if (!modal) return;
        modal.classList.add('open');
        document.body.classList.add('modal-open');
        const closeBtn = modal.querySelector('.modal-close');
        if (closeBtn) setTimeout(() => closeBtn.focus(), 100);
    };

    /** Tutup modal keranjang */
    window.closeModalKeranjang = function (id) {
        const modal = document.getElementById('modal-keranjang-' + id);
        if (!modal) return;
        modal.classList.remove('open');
        document.body.classList.remove('modal-open');
    };

    /** Tutup jika klik overlay */
    window.closeModalKeranjangIfOverlay = function (e, overlay) {
        if (e.target === overlay) {
            overlay.classList.remove('open');
            document.body.classList.remove('modal-open');
        }
    };

    /**
     * Stepper qty untuk keranjang
     * @param {string} inputId  - id elemen input
     * @param {number} delta    - +1 atau -1 (default -1 jika kosong)
     */
    window.changeQtyKeranjang = function (inputId, delta) {
        delta = (delta === undefined) ? -1 : delta;
        const input = document.getElementById(inputId);
        if (!input) return;

        let val = parseInt(input.value) || 1;
        const min = parseInt(input.min) || 1;
        const max = input.max ? parseInt(input.max) : Infinity;
        val = Math.max(min, Math.min(max, val + delta));
        input.value = val;

        // Update total di modal keranjang
        const prodId  = input.id.replace('jumlah-', '');  // ambil angka id produk
        const totalEl = document.getElementById('total-cart-' + prodId);
        if (totalEl) {
            const form    = input.closest('form');
            // Cari harga: bisa dari hidden input atau dari elemen .cart-preview-price
            let harga = 0;
            const hargaInput = form ? form.querySelector('input[name="harga"]') : null;
            if (hargaInput) {
                harga = parseInt(hargaInput.value) || 0;
            } else {
                // Fallback: ambil dari teks .cart-preview-price di modal yang sama
                const modalEl = input.closest('.modal-overlay');
                const priceEl = modalEl ? modalEl.querySelector('.cart-preview-price') : null;
                if (priceEl) {
                    harga = parseInt(priceEl.textContent.replace(/\D/g, '')) || 0;
                }
            }
            totalEl.innerHTML = 'Total: <strong>' + formatRupiah(harga * val) + '</strong>';
        }
    };

    /** Update total keranjang saat ketik manual */
    document.querySelectorAll('[id^="jumlah-"]').forEach(function (input) {
        input.addEventListener('input', function () {
            const prodId  = this.id.replace('jumlah-', '');
            const totalEl = document.getElementById('total-cart-' + prodId);
            if (!totalEl) return;

            const form       = this.closest('form');
            const hargaInput = form ? form.querySelector('input[name="harga"]') : null;
            let harga = 0;
            if (hargaInput) {
                harga = parseInt(hargaInput.value) || 0;
            } else {
                const modalEl = this.closest('.modal-overlay');
                const priceEl = modalEl ? modalEl.querySelector('.cart-preview-price') : null;
                if (priceEl) harga = parseInt(priceEl.textContent.replace(/\D/g, '')) || 0;
            }
            const qty = parseInt(this.value) || 1;
            totalEl.innerHTML = 'Total: <strong>' + formatRupiah(harga * qty) + '</strong>';
        });
    });

    /** Escape juga tutup modal keranjang */
    // (sudah ditangani oleh listener Escape di section 10 yang menutup semua .modal-overlay.open)
