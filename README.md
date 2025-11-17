# ToDo App — Laravel API + Web Version

A complete ToDo application built with Laravel, featuring:

-   Sanctum Authentication
-   Task CRUD (Create, Read, Update, Delete)
-   Toggle task completion
-   Task tags
-   Pagination and filtering (active / completed)
-   Web frontend version
-   Fully documented Postman API

---

## API Documentation (Postman)

Full API documentation is available here:

https://documenter.getpostman.com/view/34876011/2sB3WwqcyP

This includes:

-   Authentication
-   Task endpoints
-   Request & response examples
-   Error messages

---

## Installation & Setup

### 1. Clone the project

```
git clone https://github.com/Ahmed-Bakr611/ToDoApp.git
cd ToDoApp
```

### 2. Install dependencies

```
composer install
npm install
npm run build
```

### 3. Environment setup

```
cp .env.example .env
php artisan key:generate
```

### 4. Configure database

Edit `.env`:

```
DB_DATABASE=todo_app
DB_USERNAME=root
DB_PASSWORD=
```

Then migrate:

```
php artisan migrate --seed
```

### 5. Start server

```
php artisan serve
```

---

## Authentication (Sanctum)

The API uses Sanctum token-based authentication.

Endpoints:

-   POST /register
-   POST /login
-   POST /logout

Send the token using:

```
Authorization: Bearer <token>
```

---

## Web Version

This project includes a web UI built using:

-   Blade / Livewire / React / Vue (depending on your implementation)
-   TailwindCSS
-   Sanctum authentication

---
