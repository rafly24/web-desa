# ============================================================
# Script Export Semua Diagram PlantUML ke PNG (Kualitas Tinggi)
# ============================================================
# Cara pakai:
#   1. Buka PowerShell
#   2. cd c:\xampp\htdocs\web-desa\diagram
#   3. powershell -ExecutionPolicy Bypass -File export_diagrams.ps1
# ============================================================

$jarPath = "$PSScriptRoot\plantuml.jar"
$baseDir = "$PSScriptRoot\diagram activity"
$outputBase = "$PSScriptRoot\output_png"

# Buat folder output
if (-Not (Test-Path $outputBase)) {
    New-Item -ItemType Directory -Path $outputBase | Out-Null
}

Write-Host "========================================" -ForegroundColor Cyan
Write-Host " Export PlantUML ke PNG - Kualitas Tinggi" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Cari semua file .plantuml di subfolder
$files = Get-ChildItem -Path $baseDir -Recurse -Filter "*.plantuml" | Where-Object { $_.DirectoryName -ne $baseDir }

$total = $files.Count
$current = 0

foreach ($file in $files) {
    $current++
    $relativePath = $file.DirectoryName.Replace($baseDir, "").TrimStart("\")
    $outputDir = Join-Path $outputBase $relativePath

    if (-Not (Test-Path $outputDir)) {
        New-Item -ItemType Directory -Path $outputDir -Force | Out-Null
    }

    Write-Host "[$current/$total] " -NoNewline -ForegroundColor Yellow
    Write-Host "$relativePath\$($file.Name)" -ForegroundColor White

    # Export ke PNG dengan resolusi tinggi (scale 2x)
    java -jar $jarPath -tpng -o $outputDir -SdefaultFontSize=14 $file.FullName 2>$null

    if ($LASTEXITCODE -eq 0) {
        Write-Host "         -> OK" -ForegroundColor Green
    } else {
        Write-Host "         -> GAGAL" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host " Selesai! Total $total diagram di-export." -ForegroundColor Cyan
Write-Host " Output: $outputBase" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan

# Buka folder output
explorer.exe $outputBase
