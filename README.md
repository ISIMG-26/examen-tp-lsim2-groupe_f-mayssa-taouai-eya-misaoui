# DigiMarket — Digital Products Marketplace

A full-stack e-commerce web application for **buying and selling digital products** (gaming accounts, social media pages, dev/design services, marketing & consulting), built with **native PHP, MySQL, Vanilla JavaScript, HTML5 & CSS3** — no frameworks, no libraries.

> 🎓 University project — strict native-stack requirements.

---

## 👥 Team

- Member 1 — _Your Name_
- Member 2 — _Your Name_
- Member 3 — _Your Name_

---

## 🧱 Tech stack

| Layer       | Tech                         |
|-------------|------------------------------|
| Frontend    | HTML5, CSS3, Vanilla JS      |
| Backend     | PHP 7.4+ (PDO)               |
| Database    | MySQL / MariaDB              |
| Server      | Apache (WAMP / XAMPP)        |

No Bootstrap, no Tailwind, no React, no jQuery, no Laravel.

---

## ✨ Features

### User interface
- Home landing page with hero, categories and featured products
- Authentication: register, login, logout (sessions + `password_hash`)
- Shop page with category filter + AJAX live search
- Cart & checkout (orders persisted in DB)
- Responsive design (desktop / tablet / mobile)

### Admin dashboard (`/admin.php`)
- Tabs: Products / Users / Orders (interactive, JS-powered)
- Full **CRUD** on products (create, list, update price, delete)
- Manage users: delete, toggle role admin↔user
- View all orders

### Technical highlights
- **PDO + prepared statements** (SQL-injection safe)
- **Session-based auth** with role check (`user`/`admin`)
- **Two AJAX endpoints**: `/api/search.php` (live search) and `/api/check_email.php` (availability check)
- **Client-side validation** with regex + dynamic DOM error messages
- Two related MySQL tables minimum: `users`, `categories`, `products`, `orders` with foreign keys

---

## 📁 Project structure

```
digimarket/
├── index.php                # Home / landing
├── shop.php                 # Catalog + filtering + live search
├── cart.php                 # User cart + checkout
├── login.php                # Auth — login
├── register.php             # Auth — register
├── logout.php               # Destroy session
├── admin.php                # Admin dashboard (CRUD)
├── database.sql             # Schema + sample data
├── README.md
├── config/
│   └── db.php               # PDO connection + helpers
├── includes/
│   ├── header.php           # Shared header + nav
│   └── footer.php           # Shared footer + JS load
├── actions/
│   ├── cart.php             # Add/remove cart items
│   └── admin_actions.php    # Admin CRUD endpoints
├── api/
│   ├── search.php           # AJAX product search (JSON)
│   └── check_email.php      # AJAX email availability (JSON)
└── assets/
    ├── css/style.css        # All styles
    └── js/script.js         # All JS (validation + AJAX + tabs)
```

---

## ⚙️ Local setup (WAMP / XAMPP)

### 1. Install
- Download **XAMPP** (https://www.apachefriends.org) or **WAMP** (https://www.wampserver.com)
- Start **Apache** and **MySQL** from the control panel.

### 2. Drop the project
- Copy the entire `digimarket/` folder into:
  - **XAMPP:** `C:\xampp\htdocs\digimarket\`
  - **WAMP:**  `C:\wamp64\www\digimarket\`

### 3. Create the database
1. Open phpMyAdmin → http://localhost/phpmyadmin
2. Click **Import**, choose `database.sql`, and run it.
   This creates the `digimarket` database with all tables and sample data.

### 4. Configure DB credentials
Open `config/db.php` and adjust if needed (defaults work on stock WAMP/XAMPP):
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'digimarket');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 5. Open the app
Visit: **http://localhost/digimarket/**

### 6. Create your admin user (recommended)
The cleanest way:
1. Register a normal account through the UI (`/register.php`)
2. In phpMyAdmin, run:
   ```sql
   UPDATE users SET role='admin' WHERE email='you@example.com';
   ```
3. Log in again — you'll see the **Admin** link in the navigation.

> _The `database.sql` seeds 3 example users but with placeholder hashes. Use the step above (or register fresh users) to log in._

---

## ✅ Test checklist

| What to test | Where |
|---|---|
| Home page loads, categories appear | `/index.php` |
| Register a new user with weak password → JS errors block submit | `/register.php` |
| Live AJAX email check (no reload) | Home page, footer form |
| Login / Logout | `/login.php`, nav |
| Browse shop, filter by category, **live search** (AJAX) | `/shop.php` |
| Add to cart, checkout, order appears in DB | `/cart.php` |
| Admin: add product, update price, delete product | `/admin.php` (Products tab) |
| Admin: toggle user role, delete user | `/admin.php` (Users tab) |
| Admin: see all orders | `/admin.php` (Orders tab) |
| Responsive layout | Resize browser |

---

## 🔒 Security notes
- Passwords stored hashed via `password_hash` (bcrypt).
- All SQL via PDO **prepared statements** — no string concatenation.
- Output escaped with `htmlspecialchars` (`e()` helper).
- Admin endpoints protected by `require_admin()`.
- Sessions used for auth; logout destroys the session.

---

## 📜 License
Educational project — free to use and modify.
