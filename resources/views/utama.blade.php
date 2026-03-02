<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoPart Original – Toko Spare Part Mobil Terlengkap</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>

    <!-- ============================================================
         NAVBAR
    ============================================================ -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <!-- Logo -->
            <a href="#home" class="nav-logo">
                <div class="logo-icon">A</div>
                <span class="logo-text">AutoPart Original</span>
            </a>

            <!-- Nav Links -->
            <ul class="nav-links" id="navLinks">
                <li><a href="#home" class="nav-link active" data-section="home">Home</a></li>
                <li><a href="#about" class="nav-link" data-section="about">About</a></li>
                <li><a href="#product" class="nav-link" data-section="product">Product</a></li>
                @auth
                <li><a href="/admin" class="nav-link" data-section="dashboard">Dashboard</a></li>
                @endauth
            </ul>

            <!-- Auth Actions -->
            <div class="nav-auth">
                @guest
                <a href="/login" class="btn-login">Login</a>
                <a href="/register" class="btn-register">Register</a>
                @endguest

                @auth
                <div class="greeting" id="greetingText">
                    Selamat, {{ Auth::user()->name }}
                </div>
                <form action="/logout" method="POST" style="display:inline">
                    @csrf
                    <button type="submit" class="btn-login">Logout</button>
                </form>
                @endauth
            </div>

            <!-- Hamburger -->
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <!-- ============================================================
         HERO / HOME SECTION
    ============================================================ -->
    <section class="section-hero" id="home">
        <div class="hero-wrapper reveal-hero">
            <div class="hero-inner">
                <div class="hero-content">
                    <div class="hero-badge">✦ Suku Cadang Mobil Original & Terlengkap</div>
                    <h1 class="hero-title">
                        Solusi Digital<br>
                        <span class="hero-title-accent">Kendaraan Anda</span>
                    </h1>
                    <p class="hero-desc">Kebutuhan spare part mobil Jepang, Eropa, dan Amerika. Harga distributor,
                        garansi resmi, dan pengiriman ke seluruh Indonesia.</p>
                    <div class="hero-actions">
                        <a href="#product" class="btn-primary">Lihat Produk</a>
                        <a href="#about" class="btn-outline">Tentang Kami</a>
                    </div>
                </div>

                <div class="hero-visual">
                    <div class="hero-image-box">
                        <img src="{{ asset('assets/img/soon.jpg') }}" alt="Hero Visual NexeLabs" class="hero-img">
                        <div class="hero-glow"></div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Scroll indicator -->
        <div class="scroll-indicator">
            <div class="scroll-dot"></div>
        </div>
    </section>

    <!-- ============================================================
         ABOUT SECTION
    ============================================================ -->
    <section class="section-about" id="about">
        <div class="container">
            <div class="about-grid">
                <div class="about-text reveal-up">
                    <div class="section-label">Tentang Kami</div>
                    <h2 class="section-title">Mengapa Memilih <span class="accent">Kami?</span></h2>
                    <p class="about-desc">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Itaque, quisquam atque distinctio
                        asperiores inventore illo molestias tempore amet ducimus nobis quasi et magnam non, voluptate
                        nam incidunt. Ipsum dicta sit quos veniam.
                    </p>
                    <div class="about-features">
                        <div class="feature-item">
                            <div class="feature-icon">🚀</div>
                            <div>
                                <h4>Lorem ipsum dolor sit.</h4>
                                <p>Lorem, ipsum dolor sit amet consectetur adipisicing.</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">🛡️</div>
                            <div>
                                <h4>Lorem, ipsum dolor.</h4>
                                <p>Lorem ipsum dolor, sit amet consectetur adipisicing.</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">📱</div>
                            <div>
                                <h4>Lorem, ipsum.</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur adipisicing.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="about-visual reveal-up" style="--delay: 0.2s">
                    <div class="about-image-box">
                        <img src="{{ asset('assets/img/soon.jpg') }}" alt="About NexeLabs" class="about-img">
                    </div>
                    <!-- produk filter list (jika ada) -->
                    @isset($produk)
                    <ul class="produk-filter-list" style="display:none">
                        @foreach ($produk as $p)
                        <li data-filter=".{{ $p->nama }}">{{ $p->nama }}</li>
                        @endforeach
                    </ul>
                    @endisset
                </div>
            </div>
        </div>
    </section>

   <!-- ============================================================
         PRODUCT SECTION
    ============================================================ -->
    <section class="section-product" id="product">
        <div class="container">
            <div class="product-header reveal-up">
                <div class="section-label">Etalase Digital</div>
                <h2 class="section-title">Produk <span class="accent">Unggulan</span></h2>
                <p class="product-subtitle">Pilih template yang sesuai dengan kebutuhan bisnis Anda</p>
            </div>

            <div class="product-grid" id="productGrid">
                @isset($produk)
                    @forelse ($produk as $index => $p)

                    {{-- ===== CARD ===== --}}
                    <div class="product-card {{ $p->nama }}" style="--card-delay: {{ $index * 0.1 }}s">
                        <div class="card-image-wrap">
                            <img
                                src="{{ $p->image ? asset('storage/'.$p->image) : '' }}"
                                alt="{{ $p->nama }}"
                                class="card-img"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                            >
                        </div>
                        <div class="card-body">
                            <span class="card-type">{{ $p->tipe }}</span>
                            <h4 class="card-title">{{ $p->nama }}</h4>
                            @isset($p->harga)
                            <p class="card-price">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                            @endisset
                            <div class="card-actions">
                                <a href="{{ $p->image ? asset('storage/'.$p->image) : '#' }}" target="_blank" class="btn-show">
                                    <span>👁</span> Preview
                                </a>
                                @if(Auth::check() && Auth::user()->role == 'Guest')
                                <button class="btn-buy" onclick="openModalBeli('{{ $p->kode ?? $p->id }}')">
                                    <span>🛒</span> Beli
                                </button>
                                <button class="btn-cart" onclick="openModalKeranjang('{{ $p->id }}')">
                                    <span>🧺</span> Keranjang
                                </button>
                                @endif
                            </div>
                        </div>
                        <div class="card-glow-border"></div>
                    </div>

                    {{-- ===== MODAL BELI ===== --}}
                    @if(Auth::check() && Auth::user()->role == 'Guest')
                    <div class="modal-overlay" id="modal-beli-{{ $p->kode ?? $p->id }}" onclick="closeModalBeliIfOverlay(event, this)">
                        <div class="modal-box">
                            <div class="modal-head">
                                <div>
                                    <span class="modal-label">Pembelian Langsung</span>
                                    <h3 class="modal-title">{{ $p->nama }}</h3>
                                </div>
                                <button class="modal-close" onclick="closeModalBeli('{{ $p->kode ?? $p->id }}')" aria-label="Tutup">✕</button>
                            </div>
                            <div class="modal-body">
                                <div class="modal-img-wrap">
                                    @if($p->image)
                                        <img src="{{ asset('storage/'.$p->image) }}" alt="{{ $p->nama }}" class="modal-img">
                                    @else
                                        <div class="modal-img-placeholder"><span>🖥️</span></div>
                                    @endif
                                </div>
                                <div class="modal-info">
                                    <div class="modal-info-row">
                                        <span class="modal-info-label">Tipe</span>
                                        <span class="modal-info-value">{{ $p->tipe }}</span>
                                    </div>
                                    @isset($p->jenis)
                                    <div class="modal-info-row">
                                        <span class="modal-info-label">Jenis</span>
                                        <span class="modal-info-value">{{ $p->jenis }}</span>
                                    </div>
                                    @endisset
                                    @isset($p->harga)
                                    <div class="modal-info-row">
                                        <span class="modal-info-label">Harga</span>
                                        <span class="modal-info-value modal-price">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                                    </div>
                                    @endisset
                                    @isset($p->stok)
                                    <div class="modal-info-row">
                                        <span class="modal-info-label">Stok</span>
                                        <span class="modal-info-value {{ $p->stok > 0 ? 'stok-ada' : 'stok-habis' }}">
                                            {{ $p->stok > 0 ? $p->stok . ' tersedia' : 'Habis' }}
                                        </span>
                                    </div>
                                    @endisset
                                </div>
                                <form action="/pembelian/storeinput" method="POST" class="modal-form">
                                    @csrf
                                    <input type="hidden" name="kodeproduk" value="{{ $p->id }}">
                                    @isset($p->harga)
                                    <input type="hidden" name="harga" value="{{ $p->harga }}">
                                    @endisset
                                    <div class="modal-field">
                                        <label for="banyak-{{ $p->id }}" class="modal-field-label">Jumlah Pembelian</label>
                                        <div class="qty-wrap">
                                            <button type="button" class="qty-btn" onclick="changeQty('banyak-{{ $p->id }}', -1)">−</button>
                                            <input type="number" id="banyak-{{ $p->id }}" name="banyak" value="1" min="1"
                                                @isset($p->stok) max="{{ $p->stok }}" @endisset
                                                required class="qty-input" data-id="{{ $p->id }}" data-prefix="beli">
                                            <button type="button" class="qty-btn" onclick="changeQty('banyak-{{ $p->id }}', 1)">+</button>
                                        </div>
                                        @isset($p->harga)
                                        <p class="qty-total" id="total-{{ $p->id }}">
                                            Total: <strong>Rp {{ number_format($p->harga, 0, ',', '.') }}</strong>
                                        </p>
                                        @endisset
                                    </div>
                                    <div class="modal-footer-btns">
                                        <button type="button" class="btn-cancel" onclick="closeModalBeli('{{ $p->kode ?? $p->id }}')">Batal</button>
                                        <button type="submit" class="btn-confirm"><span>🛒</span> Konfirmasi Beli</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- ===== MODAL KERANJANG ===== --}}
                    <div class="modal-overlay" id="modal-keranjang-{{ $p->id }}" onclick="closeModalKeranjangIfOverlay(event, this)">
                        <div class="modal-box modal-box-keranjang">
                            <div class="modal-head">
                                <div>
                                    <span class="modal-label modal-label-cart">🧺 Keranjang Belanja</span>
                                    <h3 class="modal-title">{{ $p->nama }}</h3>
                                </div>
                                <button class="modal-close" onclick="closeModalKeranjang('{{ $p->id }}')" aria-label="Tutup">✕</button>
                            </div>
                            <div class="modal-body">
                                {{-- Preview mini produk --}}
                                <div class="cart-product-preview">
                                    <div class="cart-preview-img">
                                        @if($p->image)
                                            <img src="{{ asset('storage/'.$p->image) }}" alt="{{ $p->nama }}">
                                        @else
                                            <div class="modal-img-placeholder" style="height:100%"><span>🖥️</span></div>
                                        @endif
                                    </div>
                                    <div class="cart-preview-info">
                                        <span class="card-type" style="display:inline-block;margin-bottom:6px">{{ $p->tipe }}</span>
                                        <p class="cart-preview-name">{{ $p->nama }}</p>
                                        @isset($p->harga)
                                        <p class="cart-preview-price">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                                        @endisset
                                        @isset($p->stok)
                                        <p class="cart-preview-stok {{ $p->stok > 0 ? 'stok-ada' : 'stok-habis' }}">
                                            {{ $p->stok > 0 ? 'Stok: ' . $p->stok : 'Stok Habis' }}
                                        </p>
                                        @endisset
                                    </div>
                                </div>

                                {{-- Divider --}}
                                <div class="modal-divider"></div>

                                {{-- Form keranjang --}}
                                <form action="/keranjang/store" method="POST" class="modal-form">
                                    @csrf
                                    <input type="hidden" name="produk_id" value="{{ $p->id }}">
                                    <div class="modal-field">
                                        <label for="jumlah-{{ $p->id }}" class="modal-field-label">Jumlah</label>
                                        <div class="qty-wrap">
                                            <button type="button" class="qty-btn qty-btn-cart" onclick="changeQtyKeranjang('jumlah-{{ $p->id }}')">−</button>
                                            <input type="number" id="jumlah-{{ $p->id }}" name="jumlah" value="1" min="1"
                                                @isset($p->stok) max="{{ $p->stok }}" @endisset
                                                required class="qty-input" data-id="cart-{{ $p->id }}">
                                            <button type="button" class="qty-btn qty-btn-cart" onclick="changeQtyKeranjang('jumlah-{{ $p->id }}', 1)">+</button>
                                        </div>
                                        @isset($p->harga)
                                        <p class="qty-total" id="total-cart-{{ $p->id }}">
                                            Total: <strong>Rp {{ number_format($p->harga, 0, ',', '.') }}</strong>
                                        </p>
                                        @endisset
                                    </div>
                                    <div class="modal-footer-btns">
                                        <button type="button" class="btn-cancel" onclick="closeModalKeranjang('{{ $p->id }}')">Batal</button>
                                        <button type="submit" class="btn-confirm btn-confirm-cart">
                                            <span>🧺</span> Tambah ke Keranjang
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                    {{-- END MODALS --}}

                    @empty
                        @for ($i = 0; $i < 6; $i++)
                        <div class="product-card" style="--card-delay: {{ $i * 0.1 }}s">
                            <div class="card-image-wrap">
                                <img src="" alt="Produk {{ $i+1 }}" class="card-img" style="display:none">
                                <div class="card-img-placeholder"><span>🖥️</span></div>
                            </div>
                            <div class="card-body">
                                <span class="card-type">Laravel Template</span>
                                <h4 class="card-title">Produk {{ $i+1 }}</h4>
                                <div class="card-actions">
                                    <a href="#" class="btn-show"><span>👁</span> Preview</a>
                                </div>
                            </div>
                            <div class="card-glow-border"></div>
                        </div>
                        @endfor
                    @endforelse
                @else
                    @for ($i = 0; $i < 6; $i++)
                    <div class="product-card" style="--card-delay: {{ $i * 0.1 }}s">
                        <div class="card-image-wrap">
                            <img src="" alt="Produk {{ $i+1 }}" class="card-img" style="display:none">
                            <div class="card-img-placeholder"><span>🖥️</span></div>
                        </div>
                        <div class="card-body">
                            <span class="card-type">Laravel Template</span>
                            <h4 class="card-title">Template {{ $i+1 }}</h4>
                            <div class="card-actions">
                                <a href="#" class="btn-show"><span>👁</span> Preview</a>
                            </div>
                        </div>
                        <div class="card-glow-border"></div>
                    </div>
                    @endfor
                @endisset
            </div>
        </div>
    </section>

    <!-- ============================================================
         FOOTER
    ============================================================ -->
    <footer class="footer" id="footer">
        <div class="footer-top">
            <div class="container">
                <div class="footer-grid">
                    <!-- Brand -->
                    <div class="footer-brand">
                        <a href="#home" class="nav-logo footer-logo">
                            <div class="logo-icon">A</div>
                            <span class="logo-text">AutoPart Original</span>
                        </a>
                        <p class="footer-tagline">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                        <div class="footer-socials">
                            <a href="#" class="social-link" aria-label="Instagram">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                                    <circle cx="12" cy="12" r="4" />
                                    <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none" />
                                </svg>
                            </a>
                            <a href="#" class="social-link" aria-label="Facebook">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                                </svg>
                            </a>
                            <a href="#" class="social-link" aria-label="Twitter/X">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                </svg>
                            </a>
                            <a href="#" class="social-link" aria-label="LinkedIn">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2z" />
                                    <circle cx="4" cy="4" r="2" />
                                </svg>
                            </a>
                            <a href="#" class="social-link" aria-label="YouTube">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58zM9.75 15.02V8.98L15.5 12l-5.75 3.02z" />
                                </svg>
                            </a>
                            <a href="#" class="social-link" aria-label="WhatsApp">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Navigasi -->
                    <div class="footer-col">
                        <h5 class="footer-col-title">Navigasi</h5>
                        <ul class="footer-links">
                            <li><a href="#home">Home</a></li>
                            <li><a href="#about">About</a></li>
                            <li><a href="#product">Product</a></li>
                            <li><a href="/login">Login</a></li>
                            <li><a href="/register">Register</a></li>
                        </ul>
                    </div>

                    <!-- Layanan -->
                    <div class="footer-col">
                        <h5 class="footer-col-title">Layanan</h5>
                        <ul class="footer-links">
                            <li><a href="#">Website Bisnis</a></li>
                            <li><a href="#">Toko Online</a></li>
                            <li><a href="#">Sistem POS</a></li>
                            <li><a href="#">Custom Development</a></li>
                            <li><a href="#">Maintenance</a></li>
                        </ul>
                    </div>

                    <!-- Kontak -->
                    <div class="footer-col">
                        <h5 class="footer-col-title">Kontak</h5>
                        <ul class="footer-contact">
                            <li>
                                <span class="contact-icon">📧</span>
                                <a href="mailto:hello@autopartoriginal.id">hello@autopartoriginal.id</a>
                            </li>
                            <li>
                                <span class="contact-icon">📱</span>
                                <a href="https://wa.me/6281234567890">+62 812-3456-7890</a>
                            </li>
                            <li>
                                <span class="contact-icon">📍</span>
                                <span>Jakarta, Indonesia</span>
                            </li>
                            <li>
                                <span class="contact-icon">🕐</span>
                                <span>Senin–Jumat, 09.00–17.00</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <p>© 2026 AutoPart Original. All rights reserved.</p>
                <div class="footer-bottom-links">
                    <a href="#">Kebijakan Privasi</a>
                    <span>·</span>
                    <a href="#">Syarat & Ketentuan</a>
                    <span>·</span>
                    <a href="#">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        window.NEXE = {
            isAuth: {
                {
                    Auth::check() ? 'true' : 'false'
                }
            }
            , userName: "{{ Auth::check() ? Auth::user()->name : '' }}"
        };

    </script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>

</html>
