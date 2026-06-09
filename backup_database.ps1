# Save as: backup_database.ps1
$date = Get-Date -Format "yyyyMMdd_HHmmss"
$backupDir = "C:\Users\dear-user\Desktop\new-avs\backups"

# ایجاد پوشه backups اگر وجود ندارد
if (!(Test-Path $backupDir)) {
    New-Item -ItemType Directory -Path $backupDir -Force
}

$backupFile = "$backupDir\new_avs_backup_$date.sql"

Write-Host "🔄 در حال گرفتن بکاپ از دیتابیس new_avs..." -ForegroundColor Cyan

mysqldump -u root -p --default-character-set=utf8mb4 `
    --set-charset `
    --add-drop-table `
    --complete-insert `
    --skip-comments `
    --single-transaction `
    --routines `
    --triggers `
    --hex-blob `
    --result-file="$backupFile" `
    new_avs

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ بکاپ با موفقیت ایجاد شد: $backupFile" -ForegroundColor Green
    
    # نمایش حجم فایل
    $fileSize = [math]::Round((Get-Item $backupFile).Length / 1MB, 2)
    Write-Host "📦 حجم فایل: $fileSize MB" -ForegroundColor Yellow
} else {
    Write-Host "❌ خطا در ایجاد بکاپ" -ForegroundColor Red
}