# Laravel Google Drive Uploader

This is a Laravel-based project that integrates with the Google Drive API to allow users to authenticate via Google OAuth and upload files directly to their Google Drive accounts.

## 🚀 Features

- 🔐 Google OAuth 2.0 Authentication
- 📂 Upload files to a specific folder in Google Drive
- 📁 Create folders dynamically (optional)
- 🧠 Store and reuse access tokens
- 📡 Uses official Google Client API
- 🛠️ Built with Laravel 11+

---

## 🧰 Tech Stack

- Laravel 12
- Google Drive API
- Google API PHP Client
- Laravel Socialite (for OAuth)
- PHP 8.2+

---

## 🧑‍💻 Getting Started

### 📦 Clone the Repo

```bash
git clone https://github.com/your-username/laravel-google-drive-uploader.git
cd laravel-google-drive-uploader
```
### 🛠️ Install Dependencies
composer install
cp .env.example .env
composer require google/apiclient
php artisan key:generate

### ⚙️ Set Up Environment Variables:
In your .env file, add the following:
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/google/callback
GOOGLE_REDIRECT_URI=http://localhost:8000/api/google/callback(Use When Using Api's)
