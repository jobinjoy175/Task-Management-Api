# Task Management Api

A Task Management System built with **Laravel** backend and **Vue.js 3** frontend.  
It allows users to manage tasks with filtering, sorting, assignment, and project-level overview.

---

## Requirements

- PHP >= 8.1  
- Composer  
- Node.js >= 18  
- npm >= 10  
- Database server (MySQL )  
- Git  

---

## Setup Instructions

### 1. Clone the repository


git clone https://github.com/yourusername/task-management.git
cd Task-Management-Api
2. Backend (Laravel) Setup
cd backend-laravel/laravel
composer install
cp .env.example .env
php artisan key:generate
Edit .env to set your database connection:

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

3. Frontend (Vue.js) Setup
cd ../../frontend-vue
npm install
npm run dev
Frontend runs at: http://localhost:5173

Make sure API URLs in src/App.vue point to the backend:

axios.get('http://127.0.0.1:8000/api/dashboard-tasks')
Running Tests
Backend (Laravel)
php artisan test
Frontend (Vue.js)
npm run test:unit
Useful Commands
Laravel Backend
php artisan serve
php artisan migrate
php artisan db:seed
php artisan test
Vue.js Frontend
npm run dev
npm run build
npm run test:unit
