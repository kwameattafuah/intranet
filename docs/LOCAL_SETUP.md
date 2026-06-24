# Running the GACL Intranet Locally

This guide gets the full PHP + MySQL intranet running on your own computer — no cPanel needed.

---

## 1. Install a local server

Pick ONE (both are free and bundle PHP + MySQL + Apache):

| Tool | OS | Download |
|------|----|----|
| **Laragon** (recommended, easiest) | Windows | https://laragon.org/download |
| **XAMPP** | Windows / Mac / Linux | https://www.apachefriends.org/download.html |

Install with default options and launch it. Click **Start All** so Apache and MySQL both show green/running.

---

## 2. Put the project in the web root

Copy the whole `intranet` folder into your server's web directory:

- **Laragon:** `C:\laragon\www\intranet`
- **XAMPP (Windows):** `C:\xampp\htdocs\intranet`
- **XAMPP (Mac):** `/Applications/XAMPP/htdocs/intranet`

> Tip: you can just `git clone` your repo directly into that folder.

---

## 3. Create the database

Open the database admin tool in your browser:

- **Laragon:** right-click the Laragon window → Database, **or** go to http://localhost/phpmyadmin
- **XAMPP:** click **Admin** next to MySQL, **or** go to http://localhost/phpmyadmin

Then:

1. Click **New** (left sidebar) → create a database named **`gacl_db`**
2. Select `gacl_db`, click the **Import** tab
3. Import these files **in order** (Choose File → Go, one at a time):
   1. `Dump20260622.sql` — the main GACL database *(see note below)*
   2. `docs/hcos_schema.sql` — HCOS service portal tables
   3. `docs/departments_schema.sql` — departments, airports, forms, policies

> **Note on the main dump:** `Dump20260622.sql` is your existing GACL database
> export (staff, login accounts, news, etc.). It is **not** in the Git repo.
> Find it wherever you last exported it from the old server. If you don't have
> it, you can still run the new sections — see "Running without the main dump" below.

---

## 4. Point the app at your local database

The defaults in `config.php` already match a standard local install:

```php
DB_HOST = 127.0.0.1
DB_PORT = 3306
DB_USER = root
DB_PASS = Aa123456   ← change this
DB_NAME = gacl_db
```

Edit **`config.php`** and set:

- `DB_PASS` → your local MySQL root password.
  **Laragon & XAMPP default to an EMPTY password**, so set it to `''`:
  ```php
  define('DB_PASS', getenv('DB_PASS') ?: '');
  ```
- `APP_URL` → the address you'll open in the browser:
  ```php
  define('APP_URL', getenv('APP_URL') ?: 'http://localhost/intranet');
  ```
  *(Laragon pretty-URLs: use `http://intranet.test` instead.)*

---

## 5. Open it

Go to **http://localhost/intranet** (or `http://intranet.test` on Laragon).

You should see the login page. Log in with an account from your database dump.
Then check the new sections in the sidebar:

- **Organisation → Departments** (15 departments, real directory contacts)
- **Organisation → Airports** (6 airports)
- **Resources → Company Forms**
- **Resources → Policies & Procedures**
- **Human Capital → HCOS Portal**

---

## Running without the main dump

If you don't have `Dump20260622.sql`, the **login system won't have any users**,
so you can't log in normally. Two quick fixes:

1. **Easiest:** temporarily bypass the login check to preview the new pages.
   At the top of `layout/nav.php`, comment out the redirect block:
   ```php
   // if(!isset($_SESSION['aj.gaclintra']['user'])){
   //   header("Location: ".__url__."/login/");
   //   exit;
   // }
   ```
   Then visit `http://localhost/intranet/departments/` directly.
   **Remember to undo this before deploying.**

2. Or create a single test user row in your `users`/login table via phpMyAdmin.

---

## Common issues

| Problem | Fix |
|---------|-----|
| "Access denied for user root" | Wrong `DB_PASS` in `config.php` — Laragon/XAMPP usually want `''` |
| Pages show but no data | SQL files not imported, or imported into wrong DB name |
| 404 on `/departments/` | `mod_rewrite` not enabled — in XAMPP it is by default; ensure you're using Apache, not PHP's built-in server |
| Blank white page | Set `display_errors = 1` in `definition.php` temporarily to see the error |
