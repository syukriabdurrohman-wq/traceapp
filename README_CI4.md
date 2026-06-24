# Setup Project CodeIgniter 4 di Windows

Panduan singkat untuk menjalankan project CodeIgniter 4 hasil `git clone` di Windows melalui terminal VSCode.

---

## 1. Cek Environment

Buka terminal VSCode, lalu jalankan:

```bash
php -v
composer -V
git --version
mysql --version
php -m
```

Minimal harus tersedia:

```text
PHP
Composer
Git
MySQL
Extension PHP: intl, mbstring, mysqli, pdo_mysql, curl, openssl
```

---

## 2. Install Jika Belum Ada

Jika salah satu command belum terbaca / `command not found`, install melalui terminal:

```bash
winget install PHP.PHP.8.3
winget install Composer.Composer
winget install Git.Git
winget install Oracle.MySQL
```

Setelah selesai install, tutup VSCode lalu buka lagi.

Cek ulang:

```bash
php -v
composer -V
git --version
mysql --version
php -m
```

---

## 3. Clone Project

```bash
git clone URL_REPOSITORY
cd nama-project
```

Contoh:

```bash
git clone https://github.com/username/nama-project.git
cd nama-project
```

---

## 4. Install Dependency Project

```bash
composer install
```

---

## 5. Buat File Environment

```bash
copy env .env
```

Lalu buka file `.env` dan atur bagian berikut:

```env
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = nama_database_import
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

Sesuaikan `nama_database_import` dengan nama database lokal.

---

## 6. Buka phpMyAdmin

Jalankan Apache dan MySQL dari aplikasi lokal seperti XAMPP / Laragon / aplikasi server lain.

Buka browser:

```text
http://localhost/phpmyadmin
```

---

## 7. Buat dan Import Database

Di phpMyAdmin:

1. Buat database baru.
2. Nama database harus sama dengan yang ditulis di `.env`.
3. Masuk ke database tersebut.
4. Klik menu **Import**.
5. Pilih file `.sql` yang sudah disediakan.
6. Klik **Go / Import**.

---

## 8. Jalankan Project CI4

Kembali ke terminal VSCode:

```bash
php spark serve
```

Buka browser:

```text
http://localhost:8080
```

---

## 9. Jika Port 8080 Bentrok

Gunakan port lain:

```bash
php spark serve --port 8081
```

Lalu buka:

```text
http://localhost:8081
```

---

## 10. Ringkasan Command Utama

```bash
php -v
composer -V
git --version
mysql --version
php -m

git clone URL_REPOSITORY
cd nama-project
composer install
copy env .env
php spark serve
```

---

## Catatan

Tidak perlu menjalankan `php spark migrate` jika database sudah disediakan dalam bentuk file `.sql` dan diimport manual melalui phpMyAdmin.
