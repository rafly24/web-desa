<!-- Tips Notifikasi untuk Mobile User -->
@if(request()->is('warga/*'))
<div class="alert alert-info alert-dismissible fade show d-lg-none" role="alert">
    <h6 class="alert-heading"><i class="bi bi-info-circle-fill"></i> Tips Notifikasi di HP</h6>
    <p class="mb-2">Agar notifikasi selalu muncul di HP:</p>
    <ul class="mb-2" style="font-size: 0.9rem;">
        <li><strong>Install aplikasi</strong> dari Chrome (Menu → Install app)</li>
        <li><strong>Jangan tutup aplikasi</strong> (swipe close) dari recent apps</li>
        <li><strong>Tekan tombol HOME</strong> untuk keluar (biarkan app di background)</li>
        <li>Setting → Apps → Portal Desa → Battery → <strong>Unrestricted</strong></li>
    </ul>
    <hr>
    <p class="mb-0" style="font-size: 0.85rem;">
        <i class="bi bi-exclamation-triangle text-warning"></i> 
        <strong>Catatan:</strong> Jika app ditutup total (swipe dari recent apps), 
        notifikasi tidak akan muncul sampai membuka aplikasi lagi. Ini keterbatasan teknologi web.
    </p>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
