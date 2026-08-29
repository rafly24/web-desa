
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <link rel="preconnect" href="https://www.gstatic.com">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Web Desa Karangduren</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Favicons -->
  <link href="{{ asset('assets/img/logo-malang.png') }}" rel="icon">
  <link href="{{ asset('assets/img/logo-malang.png') }}" rel="apple-touch-icon">

  <!-- PWA Manifest -->
  <link rel="manifest" href="{{ asset('manifest.webmanifest') }}" crossorigin="use-credentials">
  <meta name="theme-color" content="#0d6efd">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="apple-mobile-web-app-title" content="Portal Desa">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,600,600i,700,700i,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  
  <!-- Non-critical Vendor CSS Files (Asynchronous loading to fix Render-Blocking) -->
  <link rel="preload" href="/assets/vendor/animate.css/animate.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="/assets/vendor/animate.css/animate.min.css"></noscript>
  
  <link rel="preload" href="/assets/vendor/aos/aos.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="/assets/vendor/aos/aos.css"></noscript>

  <link rel="preload" href="/assets/vendor/boxicons/css/boxicons.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="/assets/vendor/boxicons/css/boxicons.min.css"></noscript>

  <link rel="preload" href="/assets/vendor/glightbox/css/glightbox.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="/assets/vendor/glightbox/css/glightbox.min.css"></noscript>

  <link rel="preload" href="/assets/vendor/swiper/swiper-bundle.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
  <noscript><link rel="stylesheet" href="/assets/vendor/swiper/swiper-bundle.min.css"></noscript>

  <!-- Template Main CSS File -->
  <link href="/assets/css/style.css" rel="stylesheet">


  <style>
    #installBanner {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 9999;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 15px 20px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
      display: none;
      animation: slideUp 0.4s ease-out;
      max-width: 320px;
    }
    
    @keyframes slideUp {
      from { transform: translateY(100px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    
    #installBanner .banner-content {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    
    #installBanner .banner-icon {
      font-size: 32px;
      flex-shrink: 0;
    }
    
    #installBanner .banner-text h6 {
      margin: 0 0 5px 0;
      font-size: 14px;
      font-weight: 600;
    }
    
    #installBanner .banner-text p {
      margin: 0;
      font-size: 12px;
      opacity: 0.9;
    }
    
    #installBanner .banner-buttons {
      display: flex;
      gap: 8px;
      margin-top: 12px;
    }
    
    #installBanner button {
      padding: 8px 16px;
      border: none;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
    }
    
    #installBanner #installBtn {
      background: white;
      color: #667eea;
    }
    
    #installBanner #installBtn:hover {
      transform: scale(1.05);
      box-shadow: 0 2px 8px rgba(255,255,255,0.3);
    }
    
    #installBanner #dismissBtn {
      background: rgba(255,255,255,0.2);
      color: white;
    }
    
    #installBanner #dismissBtn:hover {
      background: rgba(255,255,255,0.3);
    }
  </style>

  <script>
    // Service Worker Registration
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js', { scope: '/' })
          .then(reg => console.log('SW registered:', reg))
          .catch(err => console.error('SW registration failed:', err));
      });
    }

    // PWA Install Prompt
    let deferredPrompt;
    
    window.addEventListener('beforeinstallprompt', (e) => {
      console.log('beforeinstallprompt event fired!');
      e.preventDefault();
      deferredPrompt = e;
      
      // Create install banner
      const installBanner = document.createElement('div');
      installBanner.id = 'installBanner';
      installBanner.innerHTML = `
        <div class="banner-content">
          <div class="banner-icon">📱</div>
          <div class="banner-text">
            <h6>Install Aplikasi</h6>
            <p>Akses lebih cepat dari layar utama</p>
          </div>
        </div>
        <div class="banner-buttons">
          <button id="installBtn">Install</button>
          <button id="dismissBtn">Nanti</button>
        </div>
      `;
      
      // Show install banner after 3 seconds
      setTimeout(() => {
        document.body.appendChild(installBanner);
        installBanner.style.display = 'block';
        console.log('Install banner shown');
      }, 3000);
      
      // Handle install button click
      installBanner.addEventListener('click', async (event) => {
        const target = event.target;
        
        if (target.id === 'installBtn') {
          if (deferredPrompt) {
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            console.log('Install outcome:', outcome);
            
            deferredPrompt = null;
            installBanner.remove();
          }
        }
        
        if (target.id === 'dismissBtn') {
          installBanner.remove();
        }
      });
    });

    // Hide banner if already installed
    window.addEventListener('appinstalled', () => {
      console.log('PWA installed successfully');
      const banner = document.getElementById('installBanner');
      if (banner) banner.remove();
    });
    
    // Debug: Check if PWA is installable
    console.log('PWA Check - SW Support:', 'serviceWorker' in navigator);
    console.log('PWA Check - Manifest:', document.querySelector('link[rel="manifest"]'));
  </script>
</head>

<body>

  @include('partials.header')

  <main id="main">

    @yield('content')

  </main><!-- End #main -->

  @include('partials.footer')
  @include('partials.notification-button')

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center" aria-label="Kembali ke Atas"><i class="bi bi-arrow-up-short" aria-hidden="true"></i></a>

  <!-- Vendor JS Files -->
  <script defer src="/assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script defer src="/assets/vendor/aos/aos.js"></script>
  <script defer src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script defer src="/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script defer src="/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script defer src="/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script defer src="/assets/vendor/php-email-form/validate.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Template Main JS File -->
  <script defer src="/assets/js/main.js"></script>

  <!-- Firebase SDK -->
  <script defer src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"></script>
  <script defer src="https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js"></script>
  
  <!-- Set user ID untuk Firebase -->
  <script>
    @auth
      window.userId = {{ auth()->id() }};
    @else
      window.userId = null;
    @endauth
  </script>
  
  <!-- Firebase Messaging Script -->
  <script defer src="/assets/js/firebase-messaging.js"></script>
  
  <!-- FCM Health Check (Auto-recovery & monitoring) -->
  <script defer src="/assets/js/fcm-health-check.js"></script>

  <!-- Offline Forms Handling -->
  <script defer src="/assets/js/offline-sync.js"></script>

  <!-- Sweet Alert -->
  <script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@10'])

</body>

</html>