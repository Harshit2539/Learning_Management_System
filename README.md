# Learning Management System (LMS)

A feature-rich Learning Management System built with **Laravel 9** supporting multi-role access, online courses, live classes, payments, and more.

## Features

- **Multi-role Support** — Admin, Instructor, Student panels
- **Course Management** — Create, manage, and sell courses with video/file content
- **Live Classes** — Zoom, BigBlueButton, and Agora integrations
- **Payment Gateways** — Stripe, PayPal, Razorpay, Flutterwave, Paystack, Mollie, and 20+ more
- **AI Integration** — OpenAI powered features
- **Multi-language** — Arabic, English, Spanish support with RTL
- **Notifications** — Email, SMS (Twilio, Vonage, MSG91, Kavenegar), Firebase push
- **Student Import** — Bulk import via Excel
- **Certificate Generation** — PDF certificates via DomPDF / mPDF
- **File Manager** — Laravel Filemanager integration
- **API** — JWT-based REST API
- **Google Calendar** — Event sync support
- **QR Code** — Course/certificate QR generation

## Tech Stack

- **Backend:** PHP 8.1+, Laravel 9
- **Frontend:** Blade, Laravel Mix / Vite
- **Database:** MySQL
- **Queue:** Laravel Jobs & Queues
- **Storage:** Local / AWS S3 / BunnyNet CDN

## Requirements

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL
- Redis (optional, for queues)

## Installation

```bash
# Clone the repo
git clone https://github.com/Harshit2539/Learning_Management_System.git
cd Learning_Management_System

# Install PHP dependencies
composer install

# Install JS dependencies
npm install && npm run dev

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations & seeders
php artisan migrate --seed

# Start the server
php artisan serve
```

## Environment Setup

Copy `.env.example` to `.env` and configure:

```env
APP_NAME="Learning Management System"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=your-mail-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
```

## Queue Worker

```bash
php artisan queue:work
```

## License

This project is open-sourced under the [MIT license](LICENSE).
