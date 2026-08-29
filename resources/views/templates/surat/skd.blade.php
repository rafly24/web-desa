<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Keterangan Domisili</title>
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
            margin-bottom: 30px;
            line-height: 1.2;
        }
        .nomor-surat h4 { margin: 0; text-decoration: underline; font-size: 14pt; font-weight: bold; }
        .nomor-surat p { margin: 0; }
        .isi-surat { text-align: justify; }
        .data-pemohon { margin-left: 30px; margin-top: 15px; margin-bottom: 15px; }
        .data-pemohon table { width: 100%; border-collapse: collapse; }
        .data-pemohon td { padding: 4px 0; vertical-align: top; }
        .keterangan-text { margin-top: 15px; text-indent: 0; text-align: justify; }
        .penutup { margin-top: 15px; text-indent: 0; text-align: justify; }
        .ttd {
            margin-top: 40px;
            float: right;
            width: 250px;
            text-align: left;
        }
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
        <p><strong>Jl. Raya Karangduren No. 60-62</strong></p>
        <p><strong>Pakisaji 65162</strong></p>
    </div>

    <div class="nomor-surat">
        <h4>SURAT KETERANGAN DOMISILI</h4>
        <p>Nomor : 474/_____/35.07.19.2005/____</p>
    </div>

    <div class="isi-surat">
        <p>Dengan ini menerangkan dengan sebenarnya bahwa:</p>

        <div class="data-pemohon">
            <table>
                <tr>
                    <td width="200">Nama</td>
                    <td width="20">:</td>
                    <td>{{ strtoupper($pengajuan->nama_lengkap) }}</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>{{ $pengajuan->jenis_kelamin == 'L' ? 'Laki - laki' : 'Perempuan' }}</td>
                </tr>
                <tr>
                    <td>Tempat Tanggal Lahir</td>
                    <td>:</td>
                    <td>{{ ucwords($pengajuan->tempat_lahir) }} {{ \Carbon\Carbon::parse($pengajuan->tanggal_lahir)->locale('id')->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Kebangsaan</td>
                    <td>:</td>
                    <td>{{ ucwords($pengajuan->kebangsaan) }}</td>
                </tr>
                <tr>
                    <td>Agama</td>
                    <td>:</td>
                    <td>{{ ucwords($pengajuan->agama) }}</td>
                </tr>
                <tr>
                    <td>Status Perkawinan</td>
                    <td>:</td>
                    <td>{{ ucwords($pengajuan->status_perkawinan) }}</td>
                </tr>
                <tr>
                    <td>No KTP</td>
                    <td>:</td>
                    <td>{{ $pengajuan->nik }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ ucwords($pengajuan->alamat) }}</td>
                </tr>
            </table>
        </div>

        <div class="keterangan-text">
            Bahwa nama tersebut diatas benar-benar berdomisili di {{ ucwords($pengajuan->alamat) }} Desa Karangduren Kecamatan Pakisaji Kabupaten Malang.
        </div>

        <p class="penutup">
            Demikian Surat Domisili ini kami buat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya.
        </p>
    </div>

    <div class="ttd">
        <p style="margin:0;">Karangduren, {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->locale('id')->format('d F Y') }}</p>
        <p style="margin:0;">Kepala Desa Karangduren</p>
        <p style="margin:0;">Sekretaris Desa</p>
        <div class="ttd-space"></div>
        <p class="ttd-name" style="margin:0;">S A I F U L</p>
    </div>
</body>
</html>
