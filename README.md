# 🇻🇳 VIET-SPORT WordPress Project

## 🧭 Introduction

**VietSport** is a WordPress platform utilizing the **Swell Child** theme, customized to serve the sports website [viet-sport.com](https://viet-sport.com). This repository manages the **entire theme source code** (database export is no longer included).

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
| viet-sport.com                 | Main website, landing page, intro                        |
| viet-sport.com/sport_team/     | Custom Post Type (CPT) page for each club                |
| viet-sport.com/sport_event/    | CPT page for each event                                  |
| viet-sport.com/match           | **Match** feature (pure PHP) – create a public match     |
| viet-sport.com/dev             | Staging site (via WP Staging plugin)                     |
| match.viet-sport.com           | Standalone **match-match** web app (separate – planned) |

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

## 🚀 CI/CD – Auto Deploy to Staging

Deployment to the **Staging** environment is automated via **GitHub Actions**, allowing for safe testing before pushing to production.

### Workflow: `deploy-staging.yml`

The `deploy-staging.yml` workflow deploys the **Swell Child** theme to the Staging server when changes are made to the `dev` branch. This process is triggered manually via the GitHub UI.

#### Deployment Steps:
1. **Checkout Code**: Source code is pulled from the GitHub repository.  
2. **Deploy to Staging**: Code is deployed to the staging server via FTP.

#### Sample Configuration in `deploy-staging.yml`:

```yaml
name: 🚀 Deploy Swell Child Theme to Staging

on:
  workflow_dispatch:

jobs:
  deploy:
    runs-on: ubuntu-latest

    steps:
      - name: ✅ Checkout code
        uses: actions/checkout@v3

      - name: 🚀 Deploy to staging
        uses: SamKirkland/FTP-Deploy-Action@v4.3.4
        with:
          server: ${{ secrets.STAGING_FTP_HOST }}
          username: ${{ secrets.STAGING_FTP_USERNAME }}
          password: ${{ secrets.STAGING_FTP_PASSWORD }}
          server-dir: wp-content/themes/swell_child/
          local-dir: ./wp-content/themes/swell_child/
          protocol: ftp
          dangerous-clean-slate: false
          exclude: |
            **/.git*
            **/node_modules/**
```

---

## 🧪 Development Environments

| Environment | Domain                    | Purpose              |
|-------------|---------------------------|----------------------|
| Local       | viet-sport.local          | Personal development |
| Staging     | viet-sport.com/dev        | Testing              |
| Production  | viet-sport.com            | Live website         |
| Match     | match.viet-sport.com    | Separate SPA match-match app |

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

> Made with 💪 in Japan – [viet-sport.com](https://viet-sport.com)

---

## 🧾 Meaning of “Match”

> ✅ The player **already has a playground**  
> ✅ Creates an **open match** for others to join  
> ✅ **Publicly listed** to invite participants  
