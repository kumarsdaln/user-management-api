
# User Management API

A **Laravel 12 RESTful API** for managing users with authentication, role-based access control (RBAC), audit logging, password reset via email, queued jobs, and rate limiting.

This project demonstrates **clean architecture, security best practices, and scalable backend design**.

---

# Features

- Laravel Sanctum authentication
- Role-based access control using Spatie Permission
- User management APIs
- Soft deletes
- Password reset via email with token
- Queued email jobs
- Audit logging system
- Rate limiting for security
- Validation using Form Requests
- Clean API response format
- PHPUnit feature tests
- Database seeders
- Laravel Telescope monitoring

---

# Tech Stack

- Laravel 12
- Laravel Sanctum
- Spatie Laravel Permission
- MySQL
- Laravel Queue
- Laravel Telescope
- PHPUnit

---

# Installation

## 1. Clone the repository

```bash
git clone https://github.com/kumarsdaln/user-management-api.git
cd user-management-api
```

## 2. Install dependencies

```bash
composer install
```

## 3. Copy environment file

```bash
cp .env.example .env
```

## 4. Generate application key

```bash
php artisan key:generate
```

## 5. Configure database

Update `.env`

DB_DATABASE=user_management
DB_USERNAME=root
DB_PASSWORD=

## 6. Run migrations and seed database

```bash
php artisan migrate --seed
```

## 7. Start queue worker

```bash
php artisan queue:work
```

## 8. Run application

```bash
php artisan serve
```

---

# Default Users
```bash
php artisan db:seed
```
Seeder creates 12 default users 1 Admin, 1 Manager and 10 random user.

### Admin

email: admin@example.com  
password: password  
role: admin  

### Manager

email: manager@example.com  
password: password  
role: manager  

---

# Authentication

Authentication uses **Laravel Sanctum token-based authentication**.

Include the token in request headers:

Authorization: Bearer TOKEN  
Accept: application/json  

---

# Role Based Access Control

Roles are managed using **Spatie Laravel Permission**.

### Roles

- admin
- manager

### Permission

- manage_users

### Access Rules

| Role | Permission | Access |
|-----|-----|-----|
| Admin | manage_users,audit_log | Full access |
| Manager | manage_users | Limited access |

---

# Rate Limiting

Custom rate limiters are implemented using Laravel's **RateLimiter**.

| Endpoint | Limit |
|------|------|
Login | 6 requests per minute |
Reset Password | 6 requests per minute |
Users | 60 requests per minute |
Audit Log | 60 requests per minute |

Example error response when limit is exceeded:

```json
{
  "success": false,
  "message": "Too many requests. Please try again later.",
  "error_code": 429
}
```

---

# API Response Format

### Success

```json
{
 "success": true,
 "message": "Operation successful",
 "data": {}
}
```

### Error

```json
{
 "success": false,
 "message": "Validation failed",
 "errors": {}
}
```

---

# API Endpoints

## Authentication

### Login

POST /api/login

Body:

```json
{
 "email": "admin@example.com",
 "password": "password"
}
```

Response:

```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "NAME",
            "email": "admin@example.com",
            "email_verified_at": null,
            "created_at": "2026-03-06T21:38:52.000000Z",
            "updated_at": "2026-03-06T21:47:34.000000Z",
            "deleted_at": null
        },
        "token": "ACCESS TOKEN"
    }
}
```

---

### Logout

POST /api/logout

Header:

Authorization: Bearer TOKEN

---

# User Management

Requires authentication and `manage_users` permission.

### Get Users

GET /api/users

Supports query parameters:

- page
- per_page
- search
- sort_by
- sort_dir

---

### Create User

POST /api/users/create

```json
{
 "name": "John",
 "email": "john@example.com",
 "password": "password",
 "password_confirmation": "password",
 "role": "manager"
}
```

---

### Update User

PUT /api/users/{id}/update

---

### Delete User

DELETE /api/users/{id}/delete

Response:

```json
{
    "success": true,
    "message": "Logout successful",
    "data": []
}
```

---

# Password Reset Flow

The password reset system works in **two steps**.

### 1. Request Password Reset

POST /api/reset-password

```json
{
 "email": "user@example.com"
}
```

This generates a reset token and sends an email.

---

### 2. Confirm Password Reset

POST /api/reset-password/confirm

```json
{
 "email": "user@example.com",
 "token": "RESET_TOKEN",
 "password": "newpassword",
 "password_confirmation": "newpassword"
}
```

---

# Audit Logs

Admin-only endpoint.

GET /api/audit-logs

Logs include:

- actor_user_id
- action
- target_user_id
- payload_diff
- ip_address
- user_agent

Example actions:

- created_user
- updated_user
- deleted_user

---

# Queue

Emails are sent through Laravel queue.

Run the worker:

```bash
php artisan queue:work
```

---

# Laravel Telescope

Telescope is used for monitoring.

Access:

/telescope

Tracks:

- requests
- queries
- jobs
- mails
- exceptions

---

# Testing

Run tests:

```bash
php artisan test
```

---
# Security

The project implements:

- token-based authentication
- role-based access control
- permission-based authorization
- rate limiting
- soft deletes
- secure password reset tokens
- queued email delivery

---

# Author

Satendra Kumar  
Backend Developer
