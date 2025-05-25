# Personal CRM Project – Laravel & Vue 3

This is a **personal portfolio project** designed to showcase my skills in **Laravel (backend)** and **Vue 3 (frontend)**. It simulates a **modern Customer Relationship Management (CRM)** system with features like contact and company management, task tracking, and team role management.

## Key Highlights
- Role-based access control using Spatie Permissions
- Manage companies and contacts with detailed records, notes, and files
- Task assignment with filters and due-date tracking
- User and team management with role assignments
- Dashboard showing summary stats and recent activity

This project is intended to demonstrate my ability to:
- Build a scalable RESTful API with Laravel
- Architect a decoupled Vue 3 frontend with Pinia, Vue Router, and Axios
- Implement secure authentication and authorization with Laravel Sanctum
- Apply best practices in code structure, modularity, and reusable components
- Explore advanced features like real-time updates and queue-based processing (future enhancements)

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
