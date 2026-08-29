<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Keterangan SKTM</title>
    <style>
        @page { margin: 1cm 2cm 2cm 2cm; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
        }
        .kop-surat {
            text-align: center;
            padding-bottom: 5px;
            margin-bottom: 25px;
            position: relative;
        }
        .kop-surat img {
            position: absolute;
            left: 0;
            top: 0;
            width: 70px;
        }
        .kop-surat h2 { margin: 0; font-size: 16pt; font-weight: bold; }
        .kop-surat h3 { margin: 0; font-size: 14pt; font-weight: bold; }
        .kop-surat p { margin: 0; font-size: 10pt; }
        .nomor-surat {
            text-align: center;
            margin-bottom: 15px;
            line-height: 1.2;
        }
        .nomor-surat h4 { margin: 0; text-decoration: underline; font-size: 14pt; font-weight: bold; }
        .nomor-surat p { margin: 0; }
        .isi-surat { text-align: justify; }
        .data-tabel { margin-left: 20px; margin-top: 10px; margin-bottom: 10px; }
        .data-tabel table { width: 100%; border-collapse: collapse; }
        .data-tabel td { padding: 2px 0; vertical-align: top; }
        .penutup { margin-top: 15px; }
        .ttd-container { width: 100%; margin-top: 40px; }
        .ttd-kiri { float: left; width: 40%; text-align: center; }
        .ttd-kanan { float: right; width: 40%; text-align: left; margin-left:20px; }
        .ttd-space { height: 90px; }
        .ttd-name { font-weight: bold; text-decoration: underline; letter-spacing: 2px;}
    </style>
</head>
<body>
    @php
        $path = public_path('assets/img/logo-malang.png');
        if(file_exists($path)){
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
        } else {
            $logo = '';
        }
    @endphp
    <div class="kop-surat">
        @if($logo)
            <img src="{{ $logo }}" alt="Logo Kabupaten Malang">
        @endif
        <h2>PEMERINTAH KABUPATEN MALANG</h2>
        <h2>KECAMATAN PAKISAJI</h2>
        <h2>DESA KARANGDUREN</h2>
        <p><strong>Jl. Raya Karangduren No. 60-62 Karangduren Pakisaji 65162</strong></p>
    </div>

    <div class="nomor-surat">
        <h4>SURAT KETERANGAN</h4>
        <p>Nomor : 474/_____/35.07.19.2005/____</p>
    </div>

    <div class="isi-surat">
        <p style="margin-top:0; margin-bottom:5px;">Dengan ini menerangkan sebenarnya bahwa:</p>

        <div class="data-tabel">
            <table>
                <tr>
                    <td width="30">1.</td>
                    <td width="160">Nama</td>
                    <td width="20">:</td>
                    <td>{{ strtoupper($pengajuan->nama_lengkap) }}</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>{{ $pengajuan->jenis_kelamin == 'L' ? 'Laki - laki' : 'Perempuan' }}</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>Tempat Tanggal Lahir</td>
                    <td>:</td>
                    <td>{{ ucwords(strtolower($pengajuan->tempat_lahir)) }} {{ \Carbon\Carbon::parse($pengajuan->tanggal_lahir)->locale('id')->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td>Kebangsaan/Suku</td>
                    <td>:</td>
                    <td>{{ ucwords(strtolower($pengajuan->kebangsaan)) }}</td>
                </tr>
                <tr>
                    <td>5.</td>
                    <td>Agama</td>
                    <td>:</td>
                    <td>{{ ucwords(strtolower($pengajuan->agama)) }}</td>
                </tr>
                <tr>
                    <td>6.</td>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td>{{ ucwords(strtolower($pengajuan->pekerjaan)) }}</td>
                </tr>
                <tr>
                    <td>7.</td>
                    <td>No. KTP</td>
                    <td>:</td>
                    <td>{{ $pengajuan->nik }}</td>
                </tr>
                <tr>
                    <td>8.</td>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ ucwords(strtolower($pengajuan->alamat)) }}</td>
                </tr>
            </table>
        </div>

        <p style="margin-top:15px; margin-bottom:5px;">Orang tersebut di atas orang tua kandung dari :</p>

        <div class="data-tabel">
            <table>
                <tr>
                    <td width="30">1.</td>
                    <td width="160">Nama</td>
                    <td width="20">:</td>
                    <td>{{ strtoupper($pengajuan->nama_anak) }}</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>{{ $pengajuan->jenis_kelamin_anak == 'L' ? 'Laki - laki' : 'Perempuan' }}</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>Tempat Tanggal Lahir</td>
                    <td>:</td>
                    <td>{{ ucwords(strtolower($pengajuan->tempat_lahir_anak)) }} {{ \Carbon\Carbon::parse($pengajuan->tanggal_lahir_anak)->locale('id')->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td>No. KTP</td>
                    <td>:</td>
                    <td>{{ $pengajuan->nik_anak }}</td>
                </tr>
                <tr>
                    <td>5.</td>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td>{{ ucwords(strtolower($pengajuan->pekerjaan_anak)) }}</td>
                </tr>
                <tr>
                    <td>6.</td>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ ucwords(strtolower($pengajuan->alamat_anak)) }}</td>
                </tr>
            </table>
        </div>

        <p class="penutup" style="margin-bottom:0;">
            Orang tersebut di atas adalah penduduk Desa Karangduren dan <u>KURANG MAMPU</u>.
        </p>
        <p style="margin-top:0; margin-bottom:0;">
            Demikian Surat Keterangan ini kami buat dengan sebenarnya untuk {{ $pengajuan->keperluan }}.
        </p>
        <p style="margin-top:0;">
            Kemudian atas perhatian dan bantuannya kami ucapkan banyak-banyak terima kasih.
        </p>
    </div>

    <div class="ttd-container">
        <div class="ttd-kiri">
            <p style="margin:0;">&nbsp;</p>
            <p style="margin:0;">Yang Bersangkutan</p>
            <p style="margin:0;">&nbsp;</p>
            <div class="ttd-space"></div>
            <p class="ttd-name" style="margin:0; text-transform:uppercase;">{{ strtoupper($pengajuan->nama_lengkap) }}</p>
        </div>
        <div class="ttd-kanan">
            <p style="margin:0;">Karangduren, {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->locale('id')->format('d F Y') }}</p>
            <p style="margin:0;">An. Kepala Desa Karangduren</p>
            <p style="margin:0;">Sekretaris Desa</p>
            <div class="ttd-space"></div>
            <p class="ttd-name" style="margin:0;">S A I F U L</p>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>
