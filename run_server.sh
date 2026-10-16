#!/bin/bash
# Skrip otomatisasi instalasi, konfigurasi database, dan peluncuran web server SuaraKita

echo "--------------------------------------------------------"
echo "🚀 SuaraKita - Auto Setup & Web Server Runner"
echo "--------------------------------------------------------"

# 1. Install PHP & MariaDB
echo "📦 1. Memeriksa & menginstall paket PHP + MariaDB..."
sudo dnf install -y php php-cli php-mysqlnd php-json php-mbstring mariadb mariadb-server

# 2. Start Service Database MariaDB
echo "⚙️ 2. Mengaktifkan service database MariaDB..."
sudo systemctl start mariadb
sudo systemctl enable mariadb

# 3. Create Database & Import Schema
echo "🗄️ 3. Mengimpor basis data suarakita..."
mysql -u root -e "CREATE DATABASE IF NOT EXISTS suarakita;"
mysql -u root suarakita < "./database/suarakita.sql"

# 4. Running Migrations automatically via CLI PHP
echo "🔐 4. Menjalankan migrasi keamanan password, kategori, dan urgensi..."
php ./database/update_passwords.php
php ./database/migrate_kategori.php
php ./database/migrate_urgensi.php

echo "--------------------------------------------------------"
echo "🌐 5. Menjalankan PHP Web Server di http://localhost:8000"
echo "Tekan Ctrl+C untuk menghentikan server kapan saja."
echo "--------------------------------------------------------"

php -S localhost:8000
