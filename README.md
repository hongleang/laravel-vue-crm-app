# Laravel + Vue + Bootstrap + Vite Starter

A modern full-stack starter template using **Laravel** (backend), **Vue 3** (frontend), **Bootstrap 5**, and **Vite** for fast and optimized development.

---
## ⚙️ Getting Started

### Prerequisites

- PHP ^8.1
- Composer
- MySQL or another supported database

---

### 🔧 Installation

```bash

# Install PHP dependencies
composer install

# Copy env file and set up environment
cp .env.example .env
php artisan key:generate

# Install Node dependencies
npm install

# Run migrations (adjust DB config in .env first)
php artisan migrate

# Start Vite development server (frontend)
npm run dev

# Start Laravel backend server (backend)
php artisan serve
