<div align="center">

# 🧪 SQL Injection Lab

### Deliberately Vulnerable PHP Application for Learning SQL Injection

A custom-built, framework-free PHP MVC application designed as a **safe, self-hosted training ground** for understanding, finding, and exploiting SQL Injection vulnerabilities — from classic error-based and UNION-based attacks to Second-Order Injection and Stacked Queries.

[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Composer](https://img.shields.io/badge/Composer-Dependency%20Manager-885630?style=flat-square&logo=composer&logoColor=white)](https://getcomposer.org/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square)](#license)
[![Status](https://img.shields.io/badge/Status-Intentionally%20Vulnerable-critical?style=flat-square)](#%EF%B8%8F-disclaimer--legal-notice)

[Overview](#-overview) •
[Vulnerabilities](#-vulnerabilities-covered) •
[Architecture](#-project-architecture) •
[Installation](#-installation--setup) •
[Exploitation Guide](#-exploitation-walkthroughs) •
[Disclaimer](#%EF%B8%8F-disclaimer--legal-notice)

</div>

<br>

<p align="center">
  <img src=".github/assets/preview.png" alt="SQL Injection Lab — Home Page Preview" width="100%">
</p>

<br>

---

## 📖 Overview

**SQL Injection Lab** is a deliberately vulnerable e-commerce-style web application built from scratch in **plain PHP** (no Laravel, no Symfony — just a lightweight custom MVC skeleton) to teach how **SQL Injection (SQLi)** vulnerabilities are introduced, discovered, and exploited in real-world-style code.

Unlike many "vulnerable-by-default" training apps that hide the bug in obscure corners, this lab mirrors realistic patterns: a product catalog, a search bar, user registration/login, a profile dashboard, and a notes feature — each one wired to raw, unsanitized SQL string concatenation instead of parameterized queries.

It's intended for:

- 🎓 Students learning web application security fundamentals
- 🛡️ Aspiring penetration testers practicing manual SQLi exploitation
- 🧑‍💻 Developers who want to **see** what insecure code looks like before learning to avoid it
- 🏫 Instructors building hands-on cybersecurity labs / CTF-style exercises

> **This is a training application, not production software.** Every model class is intentionally built without prepared statements so the vulnerable code paths are easy to study. See [Disclaimer](#%EF%B8%8F-disclaimer--legal-notice).

---

## ✨ Key Features

| Feature | Description |
|---|---|
| 🛒 **Product Catalog** | Browse a list of products seeded in MySQL, each with a dedicated detail page |
| 🔍 **Search Engine** | Free-text product search powered by a raw `LIKE` query |
| 👤 **Authentication** | Username/password registration and login (no hashing — by design) |
| 📊 **User Dashboard** | Profile page exposing account details, session info, and a notes manager |
| 📝 **Notes (CRUD)** | Add, edit, and delete personal notes — used to demonstrate **Second-Order SQL Injection** |
| 🔑 **Password Change** | First-order password update endpoint vulnerable to injection |
| 🎨 **Modern Dark UI** | Bootstrap 5 + custom CSS dark theme, fully responsive |

---

## 🕷️ Vulnerabilities Covered

This lab was built around **one central vulnerability class — SQL Injection** — but demonstrates several distinct *flavors* of it across different features, plus a few secondary issues you'll naturally bump into while exploiting it. The table below maps each vulnerability to its exact location in the source code.

### 1. Classic / In-Band SQL Injection (Error & UNION-Based)

| Endpoint | File | Vulnerable Code |
|---|---|---|
| `GET /product?id=` | `App/Controllers/ShowProductController.php` → `App/Models/BaseModel.php` | `$id` from `$_GET['id']` is concatenated directly into `WHERE id = {$id}` via the `where()` method, with **no quotes, no casting, no escaping** |
| `GET /search?query=` | `App/Controllers/SearchController.php` → `App/Models/ProductsModel.php` | `search()` builds `WHERE name LIKE '%$query%'` by direct string interpolation |
| `GET /profile?user_id=` | `App/Controllers/ProfileController.php` | `$_GET['user_id']` is passed straight into `find()` (`WHERE id = {$id}`) — also a textbook **IDOR** (Insecure Direct Object Reference), since no session ownership check is performed |

**Why it matters:** the `/product` endpoint is the most "open" entry point — `id` is injected with **no surrounding quotes**, making it trivially exploitable with classic UNION-based payloads (no need to balance quotes), e.g.:

```
/product?id=0 UNION SELECT 1,username,password,4,5,6,7 FROM users-- -
```

This single endpoint is enough to dump the entire `users` table (usernames + plaintext passwords) without ever logging in.

### 2. Authentication Bypass via SQL Injection

| Endpoint | File | Vulnerable Code |
|---|---|---|
| `POST /login` | `App/Controllers/LoginController.php` → `App/Models/UsersModel.php::login()` | `SELECT * FROM users WHERE username = '{$username}' AND password = '{$password}'` — both fields are interpolated raw inside single quotes |

**Why it matters:** because both `username` and `password` are wrapped in single quotes and concatenated directly, the classic **authentication bypass** payload works:

```
Username: admin'#
Password: tesing
```

or the boolean tautology:

```
Username: ' OR '1'='1
Password: ' OR '1'='1
```

This logs the attacker in as the **first user in the table** without knowing any valid credentials.

### 3. First-Order SQL Injection (Password Update)

| Endpoint | File | Vulnerable Code |
|---|---|---|
| `POST /change-password` | `App/Controllers/ChangePasswordController.php` → `App/Models/BaseModel.php::UpdatePassword()` | `UPDATE users SET password = '{$password}' WHERE id = {$id}` — **both** the new password value *and* the `id` are interpolated unsanitized |

**Why it matters:** this is a clean **first-order** example — the payload is injected and executed in the *same* request/query. It's also a great place to practice **Stacked Queries** (see below), since the `id` parameter sits outside quotes.

### 4. Second-Order SQL Injection (Stored / Notes Feature)

| Endpoint(s) | File | Vulnerable Code |
|---|---|---|
| `POST /notes/add` | `App/Models/NotesModel.php::CreateNote()` | Performs a naive `str_replace("'", "\\'", $note)` — **not real escaping**, and trivially bypassed (e.g. with backslash tricks or by avoiding the need for a quote entirely) |
| `GET /notes/edit?note_id=&user_id=` | `App/Controllers/NoteController.php::edit()` | `SELECT * FROM notes WHERE id = '{$note_id}' AND user_id = '{$user_id}'` — raw interpolation, fully exploitable |
| `POST /notes/update` | `App/Models/NotesModel.php::UpdateNote()` | `UPDATE notes SET note = '{$note}' WHERE id = '{$note_id}'` — raw interpolation |
| `POST /notes/delete` | `App/Models/NotesModel.php::DeleteNote()` | `DELETE FROM notes WHERE id = '{$note_id}' AND user_id = '{$user_id}'` — raw interpolation |

**Why it matters:** this is the textbook definition of **Second-Order (Stored) SQL Injection** — malicious input is first *safely stored* (or seemingly so) via one endpoint (`/notes/add`), and only becomes dangerous when it is later *read back out and re-used* in a different, unsanitized query (`/notes/edit`, `/notes/update`, `/notes/delete`). This pattern is far harder to spot via static "does this query look safe?" review, because the injection and the impact happen in two different code paths entirely.

### 5. Stacked Queries (Multi-Statement Injection)

| File | Detail |
|---|---|
| `src/Database/Connect.php` | The PDO connection is explicitly configured with `PDO::ATTR_EMULATE_PREPARES => true` and **`Pdo\Mysql::ATTR_MULTI_STATEMENTS => true`** |

**Why it matters:** enabling multi-statement execution means an attacker who finds *any* injectable numeric parameter (e.g. `id` on `/product` or `/change-password`) isn't limited to read-only `UNION SELECT` tricks — they can chain entirely separate statements in a single request, for example:

```
id=0; UPDATE users SET password='hacked' WHERE id=1; -- 
```

This turns a read-oriented injection point into a full read/write primitive against the database, and is the reason this lab explicitly enables that driver option rather than leaving it at PDO's (safer) default.

### 6. Insecure Direct Object Reference (IDOR)

| Endpoint | File | Detail |
|---|---|---|
| `GET /profile?user_id=` | `App/Controllers/ProfileController.php` | The controller trusts `$_GET['user_id']` completely — it never checks that the requested `user_id` matches `$_SESSION['id']`. Any logged-in user can view (and, combined with SQLi, manipulate) another user's profile and notes simply by changing the query string |

### 7. Plaintext Password Storage

| File | Detail |
|---|---|
| `sqlinjection.sql`, `App/Models/UsersModel.php`, `App/Models/BaseModel.php` | Passwords are stored and compared as **plaintext** — no `password_hash()` / `password_verify()` anywhere in the codebase. The seeded accounts (`admin:admin123`, `test:test123`) are stored in clear text, and the profile page even renders the plaintext password back to the user |

### 8. Sensitive Configuration Committed to Version Control

| File | Detail |
|---|---|
| `.env` | The project ships with a real `.env` file **committed to git** (no `.gitignore` excludes it), containing database credentials. In this lab the values are local/dummy (`root` / `password`), but this is flagged here as a **process vulnerability**: in any real project, `.env` must never be tracked in version control |

### 9. Client-Side-Only Input Validation

| File | Detail |
|---|---|
| `Public/js/script.js` | Username/password length constraints (`< 8 characters`) are enforced **only in JavaScript**. They are cosmetic UX hints, not security controls — every server endpoint happily accepts longer values via a direct HTTP request (`curl`, Burp Repeater, Postman, etc.), which is precisely how all the payloads above are actually delivered |

### 10. Verbose Error Reporting / Information Disclosure

| File | Detail |
|---|---|
| `Public/index.php` | `display_errors` is forced on and `error_reporting(E_ALL)` is set, while `log_errors` is disabled. Combined with `symfony/var-dumper`'s `dd()` helper (used in `SearchController`), this surfaces full result sets, stack traces, and internal structure directly in the HTTP response — useful for *this lab's* learning purpose, but a serious information-disclosure risk in any real deployment |

> 💡 **Out-of-the-box, not (yet) wired up:** the codebase also ships `Agent` and `Cookie` model classes (mapped to `agents` and `user_cookies` tables in the schema) that aren't called from any controller yet. They're left in as a hook for extending the lab — e.g. building a User-Agent logging feature with its own injection point, or a "remember me" cookie token feature vulnerable to predictable token attacks.

---

## 🏗️ Project Architecture

The application follows a lightweight, hand-rolled **MVC** pattern with no external framework — only a routing layer, a base model, a tiny view engine, and a handful of helper functions, all autoloaded via Composer's PSR-4.

```
sql_injection/
├── App/
│   ├── Controllers/          # Request handlers (1 per route group)
│   │   ├── HomeController.php
│   │   ├── LoginController.php
│   │   ├── RegisterController.php
│   │   ├── ProfileController.php
│   │   ├── LogoutController.php
│   │   ├── SearchController.php
│   │   ├── ShowProductController.php
│   │   ├── ChangePasswordController.php
│   │   └── NoteController.php
│   └── Models/                # Active-Record-style data layer (raw SQL)
│       ├── BaseModel.php       # all() / query() / create() / where() / find() / get() / first()
│       ├── UsersModel.php
│       ├── ProductsModel.php
│       ├── NotesModel.php
│       ├── Agent.php           # unused hook table
│       └── Cookie.php          # unused hook table
│
├── src/                        # Framework "core" (routing, HTTP, DB, views)
│   ├── Application/Application.php
│   ├── Database/Connect.php    # PDO connection (multi-statement emulation enabled)
│   ├── Http/
│   │   ├── Request.php
│   │   ├── Response.php
│   │   └── Route.php
│   ├── Support/helper.php      # global helper functions (view(), env(), dd(), etc.)
│   └── View/View.php           # minimal template engine (layout + content swap)
│
├── Routes/
│   └── web.php                 # all route definitions
│
├── View/
│   ├── layout/main.php         # shared HTML shell (navbar, theme, footer)
│   └── home/
│       ├── index.php           # product listing / home page
│       ├── product.php         # single product detail page
│       ├── profile.php         # user dashboard (notes, password change)
│       └── EditNote.php        # note editing form
│
├── Public/
│   ├── index.php               # single front controller / entry point
│   ├── img/                    # product images (SVG)
│   └── js/script.js            # client-side form helpers (cosmetic validation)
│
├── vendor/                     # Composer dependencies
├── composer.json / composer.lock
├── sqlinjection.sql            # database schema + seed data
└── .env                        # local DB configuration (see note above)
```

### Request Lifecycle

```
Browser
   │
   ▼
Public/index.php  ──▶  app()->run()
   │
   ▼
src/Http/Route.php  ──▶  resolves (method, path) to [Controller, action]
   │
   ▼
App/Controllers/*.php  ──▶  reads request()->input(...) and/or $_GET / $_POST
   │
   ▼
App/Models/*.php (extends BaseModel)  ──▶  builds raw SQL string ──▶ PDO::query()/exec()
   │
   ▼
src/View/View.php  ──▶  renders View/home/*.php inside View/layout/main.php
   │
   ▼
HTML Response
```

### Tech Stack

| Layer | Technology |
|---|---|
| Language | PHP 8.x |
| Database | MySQL / MariaDB (PDO driver) |
| Dependency Manager | Composer |
| Environment Config | [`vlucas/phpdotenv`](https://github.com/vlucas/phpdotenv) |
| Debug Output | [`symfony/var-dumper`](https://github.com/symfony/var-dumper) |
| Frontend | Bootstrap 5.3, vanilla JS, custom CSS (dark theme) |
| Routing / MVC Core | 100% custom, framework-free |

---

## ⚙️ Installation & Setup

### Prerequisites

- PHP **8.0+** with the `pdo_mysql` extension enabled
- MySQL or MariaDB server
- [Composer](https://getcomposer.org/)
- A local PHP server (built-in `php -S`, XAMPP, Laragon, MAMP, Docker, etc.)

### 1. Clone the repository

```bash
git clone https://github.com/<your-username>/sql_injection_lab.git
cd sql_injection_lab
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Configure environment variables

Create (or edit) the `.env` file in the project root:

```env
### database ###
DB_HOST=localhost
DB_NAME=sqlinjection
DB_USERNAME=root
DB_PASSWORD=your_local_password
DB_CHARSET=utf8

### Application ###
APP_NAME='SQL Injection Lab | Products'
```

> ⚠️ **Never commit a real `.env` with real credentials.** Add it to `.gitignore` before pushing to any public repository — see [Security Hardening Checklist](#-security-hardening-checklist-for-learners).

### 4. Import the database schema

```bash
mysql -u root -p < sqlinjection.sql
```

This creates the `sqlinjection` database along with the `users`, `products`, `notes`, `user_cookies`, and `agents` tables, plus seed data (6 products, 2 demo users: `admin`/`admin123` and `test`/`test123`).

### 5. Point your web server's document root to `Public/`

**Using PHP's built-in server:**

```bash
php -S localhost:8000 -t Public
```

Then open **http://localhost:8000**

**Using Apache/Nginx:** configure the virtual host's document root to the `Public/` directory and ensure `index.php` is the default front controller.

---

## 🎯 Exploitation Walkthroughs

> All examples below assume the app is running at `http://localhost:8000`. Run this **only against your own local instance**.

### A. UNION-Based Injection on `/product`

1. Find the number of columns returned by the original query:
   ```
   /product?id=0 ORDER BY 7-- -
   ```
   (binary-search the column count until it errors out)

2. Once you know the column count (the `products` table has 8 columns), dump credentials:
   ```
   /product?id=0 UNION SELECT 1,username,password,4,5,6,7,8 FROM users-- -
   ```

### B. Authentication Bypass on `/login`

Submit the login form with:

```
Username: admin' -- 
Password: (leave anything)
```

The trailing `-- ` comments out the password check entirely, logging you in as `admin` without knowing the real password.

### C. Boolean-Based Blind Injection on `/search`

```
/search?query=Laptop' AND '1'='1
/search?query=Laptop' AND '1'='2
```

Compare the two responses to confirm the injection point, then escalate to extracting data character-by-character with `SUBSTRING()` + conditional payloads.

### D. Stacked Query on `/change-password`

Because `Pdo\Mysql::ATTR_MULTI_STATEMENTS` is enabled, the numeric `user_id` field accepts chained statements:

```
user_id=1; UPDATE users SET password='pwned' WHERE id=1
```

### E. Second-Order Injection via Notes

1. Register/login, then submit a note containing a payload designed to break out of the quoted context once it's **re-read** by the vulnerable `edit`/`update`/`delete` queries (rather than the initial insert).
2. Use `/notes/edit?note_id=...` with a crafted `note_id` to confirm the stored payload executes in a *second*, different query context than the one that originally stored it.

### F. IDOR on `/profile`

While logged in as `test` (id=2), simply change the URL:

```
/profile?user_id=1
```

...to view (and, if combined with the password-update injection, modify) the `admin` account, with zero ownership validation.

> 🧠 **Learning tip:** try each of these manually first with `curl` or your browser's dev tools before reaching for automated tools like `sqlmap`. Understanding *why* each payload works is the actual goal of this lab — automation comes second.

---

## 🛡️ Security Hardening Checklist (For Learners)

Once you've successfully exploited every vulnerability above, the natural next exercise is to **fix them**. Here's the remediation checklist this lab is designed to teach:

- [ ] Replace every raw string-concatenated query with **PDO prepared statements** (`prepare()` + `bindParam()`/`bindValue()`, or parameterized `execute([...])`)
- [ ] Remove `PDO::ATTR_EMULATE_PREPARES => true` and `Pdo\Mysql::ATTR_MULTI_STATEMENTS => true` from `src/Database/Connect.php`
- [ ] Hash passwords with `password_hash()` (bcrypt/Argon2) and verify with `password_verify()` — never store or display plaintext passwords
- [ ] Add **server-side** ownership checks on `/profile`, `/notes/edit`, `/notes/update`, and `/notes/delete` (compare the resource's `user_id` against `$_SESSION['id']`, not a client-supplied parameter)
- [ ] Add `.env` to `.gitignore` and rotate any credentials that were ever committed
- [ ] Disable `display_errors` and enable `log_errors` in any environment beyond local development
- [ ] Treat client-side validation as a UX nicety only — duplicate every constraint as **server-side validation**
- [ ] Apply the principle of least privilege to the database user (the app should not run as `root` in any environment beyond a local lab)

---

## ⚠️ Disclaimer & Legal Notice

This project is **intentionally insecure** and was built **exclusively for educational purposes** — to teach SQL Injection concepts in a controlled, local environment.

- ❌ **Do not** deploy this application to a public-facing server or shared hosting.
- ❌ **Do not** use the techniques demonstrated here against any system, application, or database you do not own or do not have **explicit written authorization** to test.
- ✅ **Do** run it locally (`localhost`), inside a VM, or in an isolated/offline lab network.
- ✅ **Do** use it to practice for certifications (OSCP, eJPT, CEH, etc.), bug bounty training, or classroom instruction.

Unauthorized access to computer systems is illegal in most jurisdictions under laws such as the **Computer Fraud and Abuse Act (CFAA)** (United States), the **Computer Misuse Act** (United Kingdom), and equivalent legislation worldwide. The author(s) of this repository assume **no liability** for misuse of this code.

---

## 🤝 Contributing

Contributions that **extend the lab's educational value** are welcome — for example:

- Wiring up the unused `Agent` / `Cookie` models into new vulnerable (and later, fixed) features
- Adding new vulnerability classes (e.g. a deliberately vulnerable "remember me" token, blind time-based injection points, NoSQL-style injection for comparison)
- Writing a companion "fixed" branch demonstrating the secure version of every endpoint above
- Improving the UI/UX of the training interface

Please open an issue or pull request describing your proposed addition.

---

## 📄 License

This project is licensed under the **MIT License**. Feel free to use it for personal study, classroom training, or CTF events — see the [LICENSE](LICENSE) file for details (or add one matching your preferred terms before publishing).

---

<div align="center">

**Built as a hands-on companion for learning offensive & defensive web security.**

If this lab helped you understand SQL Injection, consider ⭐ starring the repository.

</div>
