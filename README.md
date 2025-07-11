<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Furniture Store Laravel + MongoDB + Redis
---

# Furniture Store Laravel Project

---

## 🚀 Fitur Utama

- Autentikasi user (login/register)
- Katalog & detail produk
- Keranjang belanja persisten (MongoDB)
- Rekomendasi produk
- Riwayat transaksi & checkout
- Like produk
- Responsive UI (Bootstrap 5)
- Session & cache dengan Redis

---

## Persyaratan

- PHP 8.1+
- Composer
- Node.js & npm
- **MongoDB** (database utama)
- **Redis** (untuk session/cache)
- Laravel 10.x

---

## Instalasi & Setup Lingkungan


### 1. **Install Dependency**
```bash
composer install
npm install && npm run build
```

### 2. **Copy & Edit Environment**
```bash
cp .env.example .env
php artisan key:generate
```
Edit file `.env`:
```
APP_NAME=FurnitureStore
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mongodb
DB_HOST=127.0.0.1
DB_PORT=27017
DB_DATABASE=furniture_db
DB_USERNAME=
DB_PASSWORD=

SESSION_DRIVER=redis
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

---

### 3. **Instalasi & Jalankan MongoDB**

#### **a. Install MongoDB**
- **Windows:**  
  Download dari [mongodb.com/try/download/community](https://www.mongodb.com/try/download/community)
- **Mac:**  
  `brew tap mongodb/brew && brew install mongodb-community`
- **Linux:**  
  Ikuti [petunjuk resmi](https://docs.mongodb.com/manual/installation/)

#### **b. Jalankan MongoDB**
```bash
mongod
```
Secara default, MongoDB akan berjalan di `mongodb://127.0.0.1:27017`

---

### 4. **Instalasi & Jalankan Redis**

#### **a. Install Redis**
- **Windows:**  
  Download dari [github.com/MicrosoftArchive/redis/releases](https://github.com/MicrosoftArchive/redis/releases)
- **Mac:**  
  `brew install redis`
- **Linux:**  
  `sudo apt install redis-server`

#### **b. Jalankan Redis**
```bash
redis-server
```
Secara default, Redis akan berjalan di `127.0.0.1:6379`

---

### 5. **(Opsional) Migrasi & Seeder**
Jika ada migrasi/seed:
```bash
php artisan migrate
php artisan db:seed
```

---

### 6. **Jalankan Aplikasi**
```bash
php artisan serve
```
Akses di [http://localhost:8000](http://localhost:8000)

---

##  Struktur Folder

- `app/Http/Controllers` — Controller Laravel
- `app/Models` — Model (User, Product, dsb)
- `resources/views` — Blade template
- `routes/web.php` — Routing web
- `public/` — Asset publik
- `config/` — File konfigurasi

---

##  Catatan

- **Keranjang**: Data keranjang user disimpan di MongoDB, tidak hilang walau logout/login.
- **Session & cache**: Redis digunakan untuk session, cache, dan queue.
- **Rekomendasi produk**: Berdasarkan aktivitas user (like, view, dsb).
- **Frontend**: Bootstrap 5, Toastr, FontAwesome.

---

## 1. Install MongoDB Server

### **Windows**
- Download installer dari:  
  [https://www.mongodb.com/try/download/community](https://www.mongodb.com/try/download/community)
- Jalankan installer, ikuti petunjuknya (biasanya cukup klik Next).
- Setelah selesai, jalankan MongoDB dengan:
  ```sh
  mongod
  ```
  atau gunakan **MongoDB Compass** (GUI) jika ingin.

### **MacOS**
```sh
brew tap mongodb/brew
brew install mongodb-community
brew services start mongodb-community
```

### **Linux (Ubuntu)**
```sh
sudo apt update
sudo apt install -y mongodb
sudo systemctl start mongodb
sudo systemctl enable mongodb
```

---

## 2. Install Package Jenssegers di Laravel

Jalankan perintah berikut di folder project Laravel Anda:
```sh
composer require jenssegers/mongodb
```

---

## 3. Konfigurasi Laravel

- Buka file `.env` dan ubah bagian database:
  ```
  DB_CONNECTION=mongodb
  DB_HOST=127.0.0.1
  DB_PORT=27017
  DB_DATABASE=nama_database_anda
  DB_USERNAME=
  DB_PASSWORD=
  ```
- Buka file `config/database.php`, tambahkan konfigurasi MongoDB (biasanya package jenssegers sudah otomatis menambahkannya).

---

## 4. Cek Koneksi

- Jalankan aplikasi Laravel:
  ```sh
  php artisan serve
  ```
- Pastikan MongoDB sudah berjalan (`mongod` aktif).
- Coba akses aplikasi, atau jalankan migrasi jika sudah ada migration untuk MongoDB.

---

**Catatan:**  
- Package **jenssegers/mongodb** adalah _driver_ Eloquent untuk MongoDB di Laravel, sehingga Anda bisa menggunakan model seperti biasa.
- Dokumentasi resmi: [https://github.com/jenssegers/laravel-mongodb](https://github.com/jenssegers/laravel-mongodb)


