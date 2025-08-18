# 🇻🇳 VIET-SPORT WordPress Project

## 🧭 Introduction

**VietSport** is a WordPress platform utilizing the **Swell Child** theme, customized to serve the sports website [viet-sport.net](https://viet-sport.net). This repository manages the **entire theme source code** (database export is no longer included).

---

## ⚙️ Key Features

1. **Submit content requests to the Admin Board**.  
2. **Search & discover clubs or events**.  
3. **Create sports playgrounds**.  
4. **Register to join a playground**.  
5. **Host a match (create public matches when users already have a playground)**.

---

## 🧱 Repository Structure

```
viet-sport/
├── wp-content/
│   └── themes/
│       └── swell_child/         ← Main theme (Swell child theme)
├── .gitignore                   ← Configured to exclude db-dumps/
├── README.md                    ← (This file)
```

> 📦 Note: The db-dumps/ folder is now **excluded from Git tracking** (added to .gitignore). Please manually export and keep your own .sql database backup when working. Git-tracking the database often leads to issues such as exposing Google OAuth Access Tokens used in form plugins that integrate with Gmail.

---

## 🌐 URL Structure

| URL                            | Purpose                                                  |
|--------------------------------|----------------------------------------------------------|
| viet-sport.net                 | Main website, landing page, intro                        |
| viet-sport.net/sport_team/     | Custom Post Type (CPT) page for each club                |
| viet-sport.net/sport_event/    | CPT page for each event                                  |
| viet-sport.net/match           | **Match** feature (pure PHP) – create a public match     |
| viet-sport.net/dev             | Staging site (via WP Staging plugin)                     |
| match.viet-sport.net           | Standalone **match-match** web app (separate – planned) |

---

## 🖥️ Local Setup

1. Clone into your LocalWP directory:

```bash
git clone https://github.com/hakudang/viet-sport-wp.git
```

2. Place it into: `C:/Users/shaku/Local Sites/viet-sport`  
3. Activate Swell Child theme in WP Admin.  
4. Manually import the database from a private `.sql` file (not included in repo).  
5. Install the required plugins:
    - WP Staging
    - Custom Post Type UI (or register CPT via code)

---

## 🔃 Git Workflow

| Branch       | Purpose                      |
|--------------|------------------------------|
| main         | Reviewed production-ready code |
| dev          | Development & staging branch |
| feature/xxx  | New features                 |
| hotfix/xxx   | Emergency bug fixes          |

---

## 🚀 Staging Deployment (Manual from Local → `viet-sport.net/dev`)

Staging is deployed **manually** in two parts:  
**A)** Child theme via **SSH + Git** (branch `dev`)  
**B)** Everything else via **cPanel** (DB, uploads, plugins, environment config)

### A) Deploy child theme via SSH + Git (branch `dev`)
**Requirement:** host has SSH + Git, staging path: `/public_html/dev/`

```bash
# SSH into host
ssh <cpanel-user>@viet-sport.net

# Go to staging theme directory
cd /home/<cpanel-user>/public_html/dev/wp-content/themes

# If NOT existing yet:
git clone --branch dev --depth 1 https://github.com/hakudang/viet-sport-wp.git tmp_repo
rsync -a --delete tmp_repo/wp-content/themes/swell_child/ ./swell_child/
rm -rf tmp_repo

# If already exists and is a git working copy:
cd swell_child
git fetch origin dev
git checkout dev
git reset --hard origin/dev
```

**Permissions (recommended):**
```bash
find /home/<cpanel-user>/public_html/dev/wp-content/themes/swell_child -type d -exec chmod 755 {} \;
find /home/<cpanel-user>/public_html/dev/wp-content/themes/swell_child -type f -exec chmod 644 {} \;
```

### B) Upload DB, uploads, plugins, `wp-config.staging.php` via cPanel

#### 1) Prepare on Local
```bash
# Pack uploads
tar -czf uploads-$(date +%F).tar.gz -C wp-content uploads

# Pack plugins (exclude caches/node_modules/logs)
tar -czf plugins-$(date +%F).tar.gz -C wp-content plugins   --exclude='**/cache' --exclude='**/node_modules' --exclude='**/*.log'

# Export database.sql from LocalWP/phpMyAdmin
```

**Staging environment config (do NOT commit):** create `wp-config.staging.php` (beside `wp-config.php` on server):
```php
<?php
// wp-config.staging.php (STAGING ONLY)
define('DB_NAME',     'vs_dev');
define('DB_USER',     'vs_dev_user');
define('DB_PASSWORD', '********');
define('DB_HOST',     'localhost');

define('WP_HOME',    'https://viet-sport.net/dev');
define('WP_SITEURL', 'https://viet-sport.net/dev');

// Google Maps key for staging
define('VSP_GOOGLE_MAPS_KEY', 'AIza...stagingKey');

// Light debug on staging
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```
In `wp-config.php` (shared), auto-include when running under `/dev`:
```php
// Auto-include staging config when running under /dev
if (
  isset($_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']) &&
  strpos($_SERVER['HTTP_HOST'], 'viet-sport.net') !== false &&
  strpos($_SERVER['REQUEST_URI'], '/dev') === 0
) {
  $stg = __DIR__ . '/wp-config.staging.php';
  if (file_exists($stg)) require_once $stg;
}
```
> In Google Maps code (e.g. Step 4), read key via:
> ```php
> $YOUR_GOOGLE_MAPS_API_KEY = defined('VSP_GOOGLE_MAPS_KEY') ? VSP_GOOGLE_MAPS_KEY : '';
> ```

#### 2) Upload via cPanel → `/public_html/dev/`
- Upload & extract:
  - `uploads-YYYY-MM-DD.tar.gz` → into `wp-content/`
  - `plugins-YYYY-MM-DD.tar.gz` → into `wp-content/`
- Upload `database.sql` (temporary in `/public_html/dev/` or home)
- Upload `wp-config.staging.php` (beside `wp-config.php`)

#### 3) Create DB & Import
- cPanel → **MySQL® Databases**: create DB `vs_dev`, user, grant **ALL**
- cPanel → **phpMyAdmin**: select `vs_dev` → **Import** `database.sql`

**(Recommended) If WP-CLI is available via SSH:**
```bash
cd /home/<cpanel-user>/public_html/dev
wp db import /path/to/database.sql

# Serialize-safe domain update
wp search-replace 'http://viet-sport.local' 'https://viet-sport.net/dev' --skip-columns=guid --all-tables

# Ensure URLs
wp option update home 'https://viet-sport.net/dev'
wp option update siteurl 'https://viet-sport.net/dev'
```

#### 4) `.htaccess` for subdirectory `/dev`
`/public_html/dev/.htaccess`:
```apache
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /dev/
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /dev/index.php [L]
</IfModule>
# END WordPress
```
Go to WP-Admin → **Settings → Permalinks → Save** to flush permalinks.  
(If you use `includes/flush-rewrite-once.php`, delete option `rewrite_rules_flushed` to re-run once.)

### Google Maps API (avoid `RefererNotAllowed / ApiNotActivated`)
In Google Cloud Console, for the **staging API key**:
1. **Enable**: *Maps JavaScript API*, *Geocoding API* (if you geocode), *Places API* (if autocomplete).  
2. Restrict → **HTTP referrers**:  
   - `https://viet-sport.net/*`  
   - `https://viet-sport.net/dev/*`

Reload `/dev/match/create?step=4` to verify.

### Post-deploy checklist
- `/dev/` loads, admin login OK  
- `/dev/match` + match header/menu render correctly  
- Flow `/dev/match/create?step=1..4` works:  
  - Step 1: **Sport + Title** → **Continue** enabled when both filled  
  - Step 2: auto `stop_date`, defaults `18:00` / `20:00`  
  - Step 3: select `match_prefecture`, `place_name`  
  - Step 4: map renders; **Search** + drag marker → **Confirm location**  
- New `match` post has:  
  - `match_status = doing` (default)  
  - `match_prefecture`, `match_sport` terms set correctly  
  - ACF fields saved correctly  
- Avoid double-seeding menu: check `vsp_match_menu_seeded` option once

> **Note:** You can still keep a **GitHub Actions** job as an *optional* auto-deploy for **theme only** (`wp-content/themes/swell_child/`). If you enable it later, ensure `server-dir` points to `dev/wp-content/themes/swell_child/` and FTP secrets are valid.

---

## 🧪 Development Environments

| Environment | Domain                    | Purpose              |
|-------------|---------------------------|----------------------|
| Local       | viet-sport.local          | Personal development |
| Staging     | viet-sport.net/dev        | Testing              |
| Production  | viet-sport.net            | Live website         |
| Match     | match.viet-sport.net    | Separate SPA match-match app |

---

## 🚀 Roadmap

- [x] Basic setup of SWELL Child theme  
- [x] Add custom post types: Sport Event, Sport Team  
- [x] Integrate taxonomies: Sport name, Category, Location, Status  
- [x] Display detailed club info  
- [ ] Create playground **match** form  
- [ ] Email invitation to play  
- [x] User account management  
- [ ] Automated email confirmations & reminders  
- [ ] Standalone match-match webapp (SPA + API)  

---

## 📄 License

MIT — Free to use, public, credit appreciated.  

> Made with 💪 in Japan – [viet-sport.net](https://viet-sport.net)

---

## 🧾 Meaning of “Match”

> ✅ The player **already has a playground**  
> ✅ Creates an **open match** for others to join  
> ✅ **Publicly listed** to invite participants  
