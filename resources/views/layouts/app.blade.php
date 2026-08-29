
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <link rel="preconnect" href="https://www.gstatic.com">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Authentication - Website Portal Desa Cibuni</title>
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/logo-malang.png') }}" />
  <link rel="stylesheet" href="admin/assets/css/styles.min.css" />
  
  <!-- PWA -->
  <link rel="manifest" href="{{ asset('manifest.webmanifest') }}" crossorigin="use-credentials">
  <meta name="theme-color" content="#0d6efd">
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">

              @yield('auth')
    
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="admin/assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="admin/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js', { scope: '/' });
      });
    }
  </script>
</body>

</html>