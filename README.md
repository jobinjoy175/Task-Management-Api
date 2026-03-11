Task Management API

A Task Management System built with Laravel 12 backend and Vue.js 3 frontend.
It allows users to manage tasks with filtering, sorting, assignment, and project-level overview.

Requirements

PHP >= 8.1

Composer

Node.js >= 18

npm >= 10

MySQL database

Git

Setup Instructions
1. Clone the repository
git clone https://github.com/jobinjoy175/Task-Management-Api.git
cd Task-Management-Api

2. Backend Setup (Laravel 12)
cd backend-laravel/laravel
composer install
cp .env.example .env
php artisan key:generate


Edit .env file with your database credentials:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=


Run migrations and seed the database:

php artisan migrate
php artisan db:seed


Start the backend server:

php artisan serve


Backend runs at: http://127.0.0.1:8000

3. Frontend Setup (Vue 3)
cd ../../frontend-vue
npm install
npm run dev


Frontend runs at: http://localhost:5173

Note: Make sure API URLs in src/App.vue point to your backend:

axios.get('http://127.0.0.1:8000/api/dashboard-tasks')

Running Tests

Backend (Laravel 12)

php artisan test


Frontend (Vue 3 + Vitest)

npm run test:unit
