# VentureHub - Setup Instructions

## Requirements
- XAMPP (Apache + PHP 8+ + MySQL)
- phpMyAdmin

---

## 1. Place the project folder

Copy the `venturehub` folder into:

    C:\xampp\htdocs\venturehub\

---

## 2. Set up the database

1. Start XAMPP (Apache + MySQL).
2. Open phpMyAdmin: http://localhost/phpmyadmin
3. Click **Import** in the top menu.
4. Choose the file: `venturehub/data/setup.sql`
5. Click **Go**.

This creates the `venturehub` database with all 5 tables and sample data.

---

## 3. Open the site

Public site:   http://localhost/venturehub/
Admin login:   http://localhost/venturehub/pages/admin/login.php

Default admin credentials:
- Username: `admin`
- Password: `password`

> Change the password hash in setup.sql before deploying anywhere.
> Generate a new hash with: `echo password_hash('yourpassword', PASSWORD_DEFAULT);`

---

## File Structure

    venturehub/
    ├── index.php                  # Public single-scroll page
    ├── css/
    │   ├── style.css              # Main styles
    │   └── admin.css              # Admin panel styles
    ├── js/
    │   ├── main.js                # Public JS (animations, form AJAX)
    │   └── admin.js               # Admin CRUD AJAX
    ├── api/
    │   ├── auth.php               # Login / logout (Matthew)
    │   ├── members.php            # Sign-up form API (Gurehmat)
    │   ├── events.php             # Past events CRUD API (Matthew)
    │   ├── partners.php           # Partners CRUD API (Matthew)
    │   └── executives.php         # Executives CRUD API (Aleesha)
    ├── includes/
    │   ├── db.php                 # PDO database connection
    │   └── auth.php               # Session guard helper
    ├── pages/
    │   └── admin/
    │       ├── login.php          # Admin login page (Matthew)
    │       └── dashboard.php      # Admin CRUD dashboard
    └── data/
        └── setup.sql              # Database creation + seed data

---

## Team Feature Map

| Feature                        | Owner    | Files                                      |
|-------------------------------|----------|--------------------------------------------|
| Club History & Stats           | Caden    | index.php (#history), js/main.js           |
| Past Events + Admin Panel      | Matthew  | api/events.php, js/admin.js                |
| Partners Ribbon + Admin Panel  | Matthew  | api/partners.php, js/admin.js              |
| Admin Login + Session Guard    | Matthew  | api/auth.php, pages/admin/login.php        |
| Member Sign-Up Form            | Gurehmat | api/members.php, js/main.js                |
| Executive Cards + Admin Panel  | Aleesha  | api/executives.php, js/admin.js            |
| Contact Us Section             | Aleesha  | index.php (#contact)                       |
