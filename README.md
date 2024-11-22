# Library Management System

## Project Overview
A Laravel-based library management system with user authentication,
book CRUD operations, and API endpoints.

## Prerequisites
- PHP 8.1+
- Composer
- Laravel 11.x
- MySQL

## Setup Instructions
1. Clone the repository
git clone https://github.com/Ehouman09/jac_php_laravel_coding_test_assignment
2. Run `composer install`
3. Copy `.env.example` to `.env`
4. Configure database settings
5. Run migrations: `php artisan migrate`
6. Generate app key: `php artisan key:generate`
7. Run seeders: `php artisan db:seed`
8. Run this for the storage: `php artisan storage:link`
9. Cache the event mappings: `php artisan event:cache`
10. Start server: `php artisan serve`

## Features
- User Registration & Authentication
- Book CRUD Operations
- RESTful API Endpoints
- API Token Authentication

## API Documentation
- <a href="https://documenter.getpostman.com/view/2660748/2sAYBSkDSQ">
https://documenter.getpostman.com/view/2660748/2sAYBSkDSQ
</a>

## Testing
Run tests with: `php artisan test`