# 🌸 Cosmetics Management System

A Laravel-based web application for managing cosmetic products,
categories, and suppliers.

------------------------------------------------------------------------

## 🚀 Installation Guide

### 1️⃣ Install Dependencies

composer install

### 2️⃣ Setup Environment

cp .env.example .env php artisan key:generate

Update database credentials inside `.env` file.

### 3️⃣ Run Migrations

php artisan migrate

### 4️⃣ Run Seeders

php artisan db:seed

### 5️⃣ Create Storage Link

php artisan storage:link

### 6️⃣ Start The App

php artisan serve

Open in browser: http://127.0.0.1:8000

------------------------------------------------------------------------

## ✨ Features

### 📦 Products

-   CRUD Operations
-   Image Upload & Preview
-   Search & Filters
-   Soft Delete
-   Trash Page
-   Restore
-   Force Delete

### 🏷 Categories

-   CRUD Operations
-   Soft Delete
-   Trash Page
-   Restore
-   Permanent Delete

### 🚚 Suppliers

-   CRUD Operations
-   Linked to Products

### 🔐 Authorization

Users cannot restore or permanently delete items they do not own.

------------------------------------------------------------------------

## 🧪 Testing

Run: php artisan test

Tests confirm: - Soft delete works - Restore works - Force delete
works - Authorization rules are enforced

------------------------------------------------------------------------

## 🖼 Screenshots

Screenshots are stored inside:

screenshots/

Example:

Dashboard\
Products Index\
Trash Page

------------------------------------------------------------------------

## ✅ Expected Outcome

✔ Soft delete works properly\
✔ Trash page supports Restore + Force Delete\
✔ Authorization prevents cross-user trash actions\
✔ Seeders provide demo data\
✔ Feature tests confirm trash/restore flows\
✔ README + screenshots make the project submission-ready

------------------------------------------------------------------------

Laravel Backend Training\
Task 11 -- Final Submission
