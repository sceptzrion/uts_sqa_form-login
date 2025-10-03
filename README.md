# UTS SQA - Simple Login Module

This project is a **simple login module** built for the **Software Quality Assurance (SQA) midterm assignment**.  
It demonstrates the implementation of a secure login form using **PHP (Native)**, **TailwindCSS**, and **MySQL** with the following security features:
- Input validation (server-side)
- Password hashing (`password_hash` / `password_verify`)
- CSRF protection
- Prepared statements (SQL Injection prevention)
- Session handling with `session_regenerate_id`
- Rate-limit (lockout after 5 failed attempts)
- Activity logging (`app.log`)

---

## 📂 Project Structure

UTS_SQA_FORM-LOGIN/
├── css/ # TailwindCSS build files (input.css, output.css)
├── dashboard/ # Dashboard page after successful login
├── node_modules/ # Node.js dependencies (for TailwindCSS build)
├── public/ # Public assets (e.g., banner.jpg)
├── reset/ # Reset lockout helper (dev only)
├── src/ # Core PHP source files
│ ├── config.php
│ ├── csrf.php
│ ├── db.php
│ └── init_db.php
├── storage/ # Log files and DB storage
│ └── app.log
├── index.php # Main login page
├── logout.php # Logout functionality
├── package.json # Node.js config for TailwindCSS
├── package-lock.json
└── uts_sqa_db.sql # SQL file to create database & users table

---

## ⚙️ Requirements

- PHP 8.x or later  
- MySQL / MariaDB (via Laragon, XAMPP, or similar)  
- Node.js & npm (for TailwindCSS build)  
- Composer (optional, not required in this project)  

## 🧪 Features to Test

- Login with valid credentials → should redirect to dashboard.
- Invalid login → error message.
- Password too short (<6 chars) → validation error.
- SQL Injection attempt → rejected.
- 5 failed login attempts → lockout with warning.