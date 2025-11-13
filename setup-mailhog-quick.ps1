# Quick MailHog Setup for Presentation
# This script will download, setup and start MailHog automatically

Write-Host "🚀 Setting up MailHog for presentation..." -ForegroundColor Green
Write-Host ""

$mailhogPath = "$env:USERPROFILE\mailhog\MailHog.exe"
$mailhogDir = "$env:USERPROFILE\mailhog"

# Check if MailHog exists
if (-not (Test-Path $mailhogPath)) {
    Write-Host "📥 MailHog not found. Downloading..." -ForegroundColor Yellow
    
    # Create directory
    if (-not (Test-Path $mailhogDir)) {
        New-Item -ItemType Directory -Path $mailhogDir | Out-Null
    }
    
    # Download MailHog
    $url = "https://github.com/mailhog/MailHog/releases/download/v1.0.1/MailHog_windows_amd64.exe"
    try {
        Invoke-WebRequest -Uri $url -OutFile $mailhogPath -UseBasicParsing
        Write-Host "✅ MailHog downloaded successfully!" -ForegroundColor Green
    } catch {
        Write-Host "❌ Failed to download MailHog" -ForegroundColor Red
        Write-Host "Please download manually from: https://github.com/mailhog/MailHog/releases" -ForegroundColor Yellow
        exit 1
    }
} else {
    Write-Host "✅ MailHog already exists" -ForegroundColor Green
}

# Check if MailHog is already running
$mailhogProcess = Get-Process -Name "MailHog" -ErrorAction SilentlyContinue
if ($mailhogProcess) {
    Write-Host "⚠️  MailHog is already running" -ForegroundColor Yellow
    Write-Host "Opening web UI..." -ForegroundColor Cyan
} else {
    # Start MailHog
    Write-Host "🚀 Starting MailHog..." -ForegroundColor Green
    Start-Process $mailhogPath
    Start-Sleep -Seconds 2
}

# Open web UI
Write-Host "🌐 Opening MailHog web UI..." -ForegroundColor Green
Start-Process "http://localhost:8025"

Write-Host ""
Write-Host "✅ MailHog is ready!" -ForegroundColor Green
Write-Host ""
Write-Host "📍 Web UI: http://localhost:8025" -ForegroundColor Cyan
Write-Host "📍 SMTP: localhost:1025" -ForegroundColor Cyan
Write-Host ""
Write-Host "💡 Tips:" -ForegroundColor Yellow
Write-Host "  - Keep this window open" -ForegroundColor White
Write-Host "  - MailHog will catch all emails" -ForegroundColor White
Write-Host "  - Refresh browser to see new emails" -ForegroundColor White
Write-Host ""

