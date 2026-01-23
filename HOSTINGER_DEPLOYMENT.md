# Deployment Guide untuk Hostinger Shared Hosting

## Prerequisites

1. Hostinger shared hosting account
2. Database MySQL di Hostinger
3. FTP/File Manager access
4. SSH access (jika tersedia)

## Step 1: Persiapan Database

1. Login ke Hostinger hPanel
2. Buat database MySQL baru:
   - Masuk ke **MySQL Databases**
   - Buat database baru (contoh: `u123456789_etools`)
   - Buat user baru dan berikan akses ke database
   - Catat: Database name, Username, Password, Host (biasanya `localhost`)

## Step 2: Upload Files ke Server

### Opsi A: Menggunakan File Manager (Recommended untuk Shared Hosting)

1. Login ke hPanel → **File Manager**
2. Navigate ke `public_html` folder
3. Upload semua file project ke `public_html` (atau subfolder jika menggunakan subdomain)

**Struktur yang diharapkan:**
```
public_html/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/          (isi folder ini akan dipindah ke root)
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
├── artisan
├── composer.json
└── index.php        (dari public/index.php)
```

### Opsi B: Menggunakan FTP

1. Gunakan FTP client (FileZilla, WinSCP, dll)
2. Connect ke server dengan credentials dari Hostinger
3. Upload semua file ke `public_html`

## Step 3: Konfigurasi File Structure untuk Shared Hosting

**PENTING:** Shared hosting biasanya tidak mengizinkan akses ke folder di luar `public_html`. Ada 3 opsi:

### Opsi 1: Keep Laravel Structure Intact (Recommended - No File Moving)

**Keep semua file Laravel di struktur aslinya, gunakan `.htaccess` untuk redirect ke `public/`:**

1. Upload semua file Laravel ke `public_html` dengan struktur asli:
```
public_html/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/          (KEEP di sini, jangan dipindah!)
│   ├── index.php
│   ├── .htaccess
│   └── ...
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
├── artisan
└── composer.json
```

2. Buat file `.htaccess` di root `public_html/`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirect semua request ke public folder
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ /public/$1 [L]
    
    # Redirect root ke public/index.php
    RewriteRule ^$ /public/index.php [L]
</IfModule>
```

**Keuntungan:**
- ✅ Tidak perlu memindahkan file
- ✅ Struktur Laravel tetap utuh
- ✅ Mudah untuk update/maintenance
- ✅ Compatible dengan semua Laravel commands

### Opsi 2: Semua File di public_html (Traditional Method)

1. Upload semua file Laravel ke `public_html`
2. Pindahkan isi folder `public/` ke root `public_html/`
3. Update `public_html/index.php`:

```php
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
```

### Opsi 3: Menggunakan Subfolder (Jika menggunakan subdomain/folder)

Jika aplikasi di subfolder seperti `public_html/etools-event/`:

1. Upload semua file ke `public_html/etools-event/`
2. Keep struktur asli dengan `public/` folder
3. Buat `.htaccess` di `public_html/etools-event/`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_URI} !^/etools-event/public/
    RewriteRule ^(.*)$ /etools-event/public/$1 [L]
</IfModule>
```

## Step 4: Setup Environment File

1. Di `public_html`, copy `.env.example` ke `.env` (jika ada)
2. Atau buat file `.env` baru dengan konfigurasi:

```env
APP_NAME="ETools Event"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u123456789_etools
DB_USERNAME=u123456789_dbuser
DB_PASSWORD=your_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Ganti dengan data database Hostinger Anda!**

## Step 5: Install Dependencies via SSH (Jika Tersedia)

Jika Hostinger menyediakan SSH access:

```bash
cd ~/public_html
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

## Step 6: Install Dependencies via File Manager (Jika Tidak Ada SSH)

Jika tidak ada SSH, Anda perlu:

1. **Install Composer dependencies secara lokal:**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
   Upload folder `vendor/` ke server

2. **Generate APP_KEY:**
   - Buka terminal lokal
   - Run: `php artisan key:generate`
   - Copy `APP_KEY` dari `.env` lokal ke `.env` di server

3. **Run migrations:**
   - Jika ada phpMyAdmin, import SQL dari migration
   - Atau gunakan online Laravel migration tool
   - Atau jalankan via browser (buat route temporary)

## Step 7: Setup File Permissions

Via File Manager atau FTP, set permissions:

```
storage/ → 755 (atau 775)
storage/framework/ → 755
storage/framework/cache/ → 755
storage/framework/sessions/ → 755
storage/framework/views/ → 755
storage/logs/ → 755
bootstrap/cache/ → 755
```

**Atau via SSH:**
```bash
chmod -R 755 storage bootstrap/cache
```

## Step 8: Setup Storage Link

Jika menggunakan storage untuk upload file:

**Via SSH:**
```bash
php artisan storage:link
```

**Manual (jika tidak ada SSH):**
1. Di File Manager, buat symbolic link dari `public_html/storage` ke `storage/app/public`
2. Atau copy isi `storage/app/public` ke `public_html/storage`

## Step 9: Build Assets (Jika Menggunakan Vite)

Jika ada build assets:

**Lokal:**
```bash
npm install
npm run build
```

Upload folder `public/build/` ke server.

## Step 10: Konfigurasi .htaccess

### Jika menggunakan Opsi 1 (Keep Laravel Structure):

1. Copy file `public_html.htaccess` (dari project root) ke `public_html/.htaccess`
2. Atau buat file `.htaccess` di root `public_html/` dengan isi:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirect semua request ke public folder
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ /public/$1 [L]
    
    # Redirect root ke public/index.php
    RewriteRule ^$ /public/index.php [L]
</IfModule>
```

3. File `.htaccess` di `public/.htaccess` sudah ada dan tidak perlu diubah

### Jika menggunakan Opsi 2 (Traditional - File Dipindah):

Pastikan ada file `.htaccess` di root `public_html/`:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

## Step 11: Testing

1. Akses website: `https://yourdomain.com`
2. Cek halaman utama
3. Cek admin dashboard: `https://yourdomain.com/dashboard`
4. Test upload file (jika ada)

## Troubleshooting

### Error 500 Internal Server Error
- Cek file `.env` sudah benar
- Cek `APP_KEY` sudah di-set
- Cek file permissions (storage, bootstrap/cache)
- Cek error log di `storage/logs/laravel.log`

### Database Connection Error
- Pastikan database credentials di `.env` benar
- Pastikan database user punya akses ke database
- Cek `DB_HOST` (biasanya `localhost` untuk shared hosting)

### Storage Link Tidak Berfungsi
- Pastikan symbolic link dibuat: `php artisan storage:link`
- Atau copy manual: `storage/app/public` → `public/storage`

### File Upload Tidak Bekerja
- Cek permissions folder `storage/`
- Cek `storage/app/public` ada dan writable
- Cek `public/storage` link ada

### Composer Dependencies Error
- Pastikan PHP version di Hostinger >= 8.1
- Upload folder `vendor/` yang sudah di-install lokal
- Atau gunakan SSH untuk install

## Checklist Sebelum Go Live

- [ ] Database sudah dibuat dan credentials benar
- [ ] File sudah di-upload ke server
- [ ] `.env` sudah dikonfigurasi dengan benar
- [ ] `APP_KEY` sudah di-generate
- [ ] Dependencies sudah di-install (`vendor/` folder ada)
- [ ] Migrations sudah di-run
- [ ] File permissions sudah benar (storage, bootstrap/cache)
- [ ] Storage link sudah dibuat
- [ ] `.htaccess` sudah ada
- [ ] Assets sudah di-build (jika perlu)
- [ ] APP_DEBUG=false untuk production
- [ ] Test semua fitur utama

## Tips untuk Shared Hosting

1. **Gunakan Cloud Storage** untuk file upload (S3, Cloudinary, dll) karena shared hosting punya limit storage
2. **Enable caching** untuk performa lebih baik
3. **Monitor storage usage** - shared hosting punya limit
4. **Backup database** secara rutin
5. **Gunakan CDN** untuk static assets jika perlu

