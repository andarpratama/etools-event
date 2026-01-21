# Deployment Guide untuk Render.com

## Konfigurasi yang Diperlukan

### 1. Environment Variables di Render.com Dashboard

Setelah membuat service di Render.com, pastikan untuk mengatur environment variables berikut:

#### Database Configuration
```
DB_CONNECTION=mysql
DB_HOST=<dari Render Database Internal Connection String>
DB_PORT=3306
DB_DATABASE=etools
DB_USERNAME=<dari Render Database>
DB_PASSWORD=<dari Render Database>
```

#### Application Configuration
```
APP_NAME="ETools Event"
APP_ENV=production
APP_KEY=<generate dengan: php artisan key:generate>
APP_DEBUG=false
APP_URL=https://your-app-name.onrender.com
```

#### Logging
```
LOG_CHANNEL=stderr
LOG_LEVEL=error
```

### 2. Build & Start Commands

**Build Command:**
```bash
composer install --no-dev --optimize-autoloader && npm install && npm run build && php artisan key:generate --force && php artisan migrate --force && php artisan db:seed --class=ToolSeeder --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan storage:link
```

**Start Command:**
```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

### 3. Database Setup

1. Buat PostgreSQL atau MySQL database di Render.com
2. Copy connection string dari database settings
3. Set environment variables untuk database connection
4. Migrations akan berjalan otomatis saat build

### 4. Storage Configuration

**PENTING:** Render.com menggunakan ephemeral filesystem, artinya file yang di-upload akan hilang saat restart.

**Solusi:**
- Gunakan **Persistent Disk** untuk storage (recommended)
- Atau gunakan **S3/Cloud Storage** (lebih baik untuk production)

Untuk menggunakan Persistent Disk:
1. Di Render Dashboard, tambahkan Persistent Disk ke service
2. Mount ke `/opt/render/project/src/storage/app/public`

Atau konfigurasi S3 di `.env`:
```
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=your_region
AWS_BUCKET=your_bucket
AWS_URL=your_s3_url
```

### 5. Checklist Sebelum Deploy

- [ ] Semua environment variables sudah di-set
- [ ] Database sudah dibuat dan connection string sudah benar
- [ ] APP_KEY sudah di-generate
- [ ] APP_URL sudah di-set ke URL Render.com
- [ ] Storage configuration sudah benar (Persistent Disk atau S3)
- [ ] Build command sudah benar
- [ ] Start command sudah benar

### 6. Setelah Deploy

1. Cek logs untuk memastikan tidak ada error
2. Test halaman utama: `https://your-app.onrender.com`
3. Test admin dashboard: `https://your-app.onrender.com/dashboard`
4. Test upload file (jika menggunakan Persistent Disk atau S3)

### 7. Troubleshooting

**Error: Storage link tidak berfungsi**
- Pastikan `php artisan storage:link` ada di build command
- Atau gunakan S3 untuk storage

**Error: Database connection failed**
- Cek environment variables DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD
- Pastikan menggunakan Internal Database URL dari Render

**Error: 500 Internal Server Error**
- Cek logs di Render Dashboard
- Pastikan APP_KEY sudah di-set
- Pastikan APP_DEBUG=false untuk production

**File upload tidak tersimpan**
- Gunakan Persistent Disk atau S3
- Jangan gunakan local storage di Render (ephemeral)

### 8. Recommended: Setup S3 Storage

Untuk production, sangat disarankan menggunakan S3:

1. Buat AWS S3 bucket
2. Set environment variables:
   ```
   FILESYSTEM_DISK=s3
   AWS_ACCESS_KEY_ID=xxx
   AWS_SECRET_ACCESS_KEY=xxx
   AWS_DEFAULT_REGION=ap-southeast-1
   AWS_BUCKET=your-bucket-name
   ```
3. Update `config/filesystems.php` jika perlu

### 9. Auto-Deploy dari GitHub

1. Connect GitHub repository ke Render
2. Set branch (biasanya `main` atau `master`)
3. Render akan auto-deploy setiap push ke branch tersebut

