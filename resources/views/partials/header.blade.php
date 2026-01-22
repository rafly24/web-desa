<!-- ======= Header ======= -->
<header id="header" class="d-flex align-items-center">
    <div class="container d-flex align-items-center">

        <div class="logo me-auto">
            <h1><a href="/">
                    <img src="{{ asset('storage/' . $logo->logo) }}" alt="Logo">
                </a></h1>
        </div>

        <nav id="navbar" class="navbar">
            <ul>
                <li><a class="nav-link scrollto active" href="/">Beranda</a></li>
                <li class="dropdown"><a href="#"><span>Profil Desa</span> <i class="bi bi-chevron-down"></i></a>
                    <ul>
                        <li><a href="/wilayah">Wilayah</a></li>
                        <li><a href="/sejarah">Sejarah</a></li>
                        <li><a href="/visi-misi">Visi & Misi</a></li>
                        <li><a href="/perangkat-desa">Perangkat Desa</a></li>
                        <li><a href="/peta-desa">Peta Desa</a></li>
                        <li><a href="/data-desa">Data Desa</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a href="#"><span>Informasi</span> <i class="bi bi-chevron-down"></i></a>
                    <ul>
                        <li><a href="/pengumuman">Pengumuman</a></li>
                        <li><a href="/berita">Berita</a></li>
                        <li><a href="/gallery">Gallery</a></li>
                        <li><a href="/apbdesa">APBDesa</a></li>
                    </ul>
                </li>
                <li><a class="nav-link scrollto" href="/umkm">Umkm</a></li>
                <li class="dropdown"><a href="#"><span>Layanan Online</span> <i class="bi bi-chevron-down"></i></a>
                    <ul>
                        <li><a href="/pengajuan-surat"><i class="bi bi-file-earmark-text"></i> Pengajuan Surat</a></li>
                        <li><a href="/laporan-warga"><i class="bi bi-megaphone"></i> Laporan Warga</a></li>
                        <li><a href="/layanan">Layanan Lainnya</a></li>
                    </ul>
                </li>
                <li><a class="nav-link scrollto" href="/kontak">Kontak kami</a></li>
                
                @auth
                    <!-- Menu untuk user yang sudah login -->
                    <li class="dropdown"><a href="#"><span><i class="bi bi-person-circle"></i> {{ Auth::user()->name }}</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            @if(Auth::user()->role == 'admin')
                                <li><a href="/dashboard"><i class="bi bi-speedometer2"></i> Dashboard Admin</a></li>
                            @else
                                <li><a href="{{ route('warga.dashboard') }}"><i class="bi bi-house-door"></i> Dashboard Saya</a></li>
                                <li><a href="{{ route('warga.profile') }}"><i class="bi bi-person"></i> Profil Saya</a></li>
                            @endif
                            <li>
                                <a href="{{ route('logout') }}" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right"></i> Keluar
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <!-- Menu untuk guest (belum login) -->
                    <li><a href="/login" class="nav-link scrollto"><i class="bi bi-box-arrow-in-right"></i> Masuk</a></li>
                    <li><a href="/register" class="nav-link scrollto" style="background: #5846f9; color: white; padding: 8px 20px; border-radius: 50px;"><i class="bi bi-person-plus"></i> Daftar</a></li>
                @endauth
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav><!-- .navbar -->

    </div>
</header><!-- End Header -->
