# ✅ To Do App API — Laravel 12 + Sanctum

A simple **RESTful API** built with **Laravel 12** and **Sanctum**, allowing users to register, log in, and manage their personal to-do lists.

---

## 🚀 Features

- 🔐 **User Authentication**
  - Register
  - Login (token-based)
  - Logout

- 📝 **To-Do Management (CRUD)**
  - Create todo
  - View all todos (only for logged-in user)
  - Update todo
  - Delete todo

- 🔒 **Auth Protection**
  - All actions are scoped to the authenticated user
  - Built using Laravel Sanctum

---

## 📦 Tech Stack

- **Backend:** Laravel 12
- **Auth:** Laravel Sanctum
- **Database:** MySQL (or SQLite/Postgres as needed)
- **API Format:** JSON

---

## 📂 API Endpoints

### 🔐 Auth

| Method | Endpoint       | Description        |
|--------|----------------|--------------------|
| POST   | `/api/register` | Register new user |
| POST   | `/api/login`    | Login & get token |
| POST   | `/api/logout`   | Logout (auth only) |

> 🔒 Logout requires `Bearer <token>` in header.

---

### 📝 Todos (`auth:sanctum` protected)

| Method | Endpoint        | Description       |
|--------|------------------|-------------------|
| GET    | `/api/todo`       | List user’s todos |
| POST   | `/api/todo`       | Create a todo     |
| GET    | `/api/todo/{id}`  | Show single todo  |
| PUT    | `/api/todo/{id}`  | Update a todo     |
| DELETE | `/api/todo/{id}`  | Delete a todo     |

> ✅ All todo operations are **user-scoped**.

---

## 🛠️ Installation

```bash
git clone https://github.com/your-username/todo-app-api.git
cd todo-app-api

composer install
cp .env.example .env
php artisan key:generate

# Setup your database
php artisan migrate

# (Optional) Seed initial data
# php artisan db:seed

# Install Sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
