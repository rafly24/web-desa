<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Keterangan Tidak Mampu</title>
    <style>
        @page { margin: 2cm; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.6; }
        .kop-surat { text-align: center; border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat h2 { margin: 0; font-size: 18pt; font-weight: bold; }
        .kop-surat h3 { margin: 5px 0; font-size: 16pt; font-weight: bold; }
        .kop-surat p { margin: 2px 0; font-size: 10pt; }
        .nomor-surat { text-align: center; margin: 20px 0; }
        .nomor-surat h4 { margin: 5px 0; text-decoration: underline; font-size: 14pt; }
        .isi-surat { text-align: justify; margin: 20px 0; }
        .data-pemohon { margin-left: 40px; }
        .data-pemohon table { width: 100%; }
        .data-pemohon td { padding: 3px 0; }
        .ttd { margin-top: 40px; float: right; width: 300px; text-align: center; }
        .ttd-space { height: 80px; }
        .footer { clear: both; margin-top: 100px; font-size: 9pt; font-style: italic; }
    </style>
</head>
<body>
    <div class="kop-surat">
        <h2>PEMERINTAH KABUPATEN TEMANGGUNG</h2>
        <h3>KECAMATAN CANDIROTO</h3>
        <h3>DESA KARANGDUREN</h3>
        <p>Jl. Raya Karangduren, Kec. Candiroto, Kab. Temanggung, Jawa Tengah</p>
        <p>Email: desakarangduren@gmail.com | Telp: (0293) XXXXXX</p>
    </div>

    <div class="nomor-surat">
        <h4>SURAT KETERANGAN TIDAK MAMPU</h4>
        <p>Nomor: {{ $nomor_surat }}</p>
    </div>

    <div class="isi-surat">
        <p style="text-indent: 40px;">
            Yang bertanda tangan di bawah ini, Kepala Desa Karangduren, Kecamatan Candiroto, Kabupaten Temanggung, 
            menerangkan dengan sebenarnya bahwa:
        </p>

        <div class="data-pemohon">
            <table>
                <tr>
                    <td width="200">Nama</td>
                    <td width="20">:</td>
                    <td><strong>{{ $pengajuan->nama_lengkap }}</strong></td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>:</td>
                    <td>{{ $pengajuan->nik }}</td>
                </tr>
                <tr>
                    <td>Tempat, Tanggal Lahir</td>
                    <td>:</td>
                    <td>{{ $pengajuan->tempat_lahir }}, {{ \Carbon\Carbon::parse($pengajuan->tanggal_lahir)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>{{ $pengajuan->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td>{{ $pengajuan->pekerjaan ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $pengajuan->alamat }}, RT/RW {{ $pengajuan->rt_rw }}, Desa {{ $pengajuan->desa_kelurahan }}</td>
                </tr>
            </table>
        </div>

        <p style="text-indent: 40px; margin-top: 20px;">
            Orang tersebut di atas adalah benar warga Desa Karangduren yang tergolong keluarga kurang mampu/tidak mampu.
        </p>

        <p style="text-indent: 40px;">
            Surat keterangan ini dibuat untuk keperluan: <strong>{{ $pengajuan->keperluan }}</strong>
        </p>

        <p style="text-indent: 40px;">
            Demikian surat keterangan ini dibuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya.
        </p>
    </div>

    <div class="ttd">
        <p>Karangduren, {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d F Y') }}</p>
        <p><strong>Kepala Desa Karangduren</strong></p>
        <div class="ttd-space"></div>
        <p><strong><u>NAMA KEPALA DESA</u></strong></p>
        <p>NIP. XXXX XXXX XXXX XXXX</p>
    </div>

    <div class="footer">
        <p>* Surat ini dibuat secara elektronik dan sah tanpa tanda tangan basah</p>
        <p>* Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i') }} WIB</p>
    </div>
</body>
</html>
