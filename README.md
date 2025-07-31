# 🇻🇳 VIET-SPORT WordPress Theme (Swell Child)

> Dự án nền tảng tạo website cho CLB thể thao, hỗ trợ đặt sân & mời bạn chơi.  
> Codebase của child theme Swell, tùy biến sâu các tính năng đặc biệt cho `viet-sport.com`.

---

## 🏗️ Kiến trúc hệ thống

```
wp-content/themes/swell-child/
├── functions.php             ← Khởi động hệ thống (require init)
├── style.css                 ← Custom style (tuân theo Swell)
├── includes/                 ← Code chính: CPT, taxonomy, config
│   ├── init.php              ← Require toàn bộ file CPT/taxonomy
│   ├── cpt/
│   │   └── sport_team.php    ← Custom Post Type CLB
│   └── taxonomies/
│       └── sport_type.php    ← (nếu có taxonomy loại hình thể thao)
├── booking/                  ← Tính năng đặt sân (PHP thuần)
│   ├── index.php             ← Route chính: viet-sport.com/booking
│   ├── form.php              ← Form đặt sân
│   ├── handler.php           ← Xử lý lưu dữ liệu booking
│   └── mail.php              ← Gửi email mời bạn
├── .gitignore
└── README.md                 ← (tập tin này)
```

---

## 🌐 URL Structure

| URL                          | Mục đích                                           |
|-----------------------------|----------------------------------------------------|
| `viet-sport.com`            | Web chính, landing page, giới thiệu                |
| `viet-sport.com/sport_team/`| Trang CPT của từng CLB                             |
| `viet-sport.com/booking`    | Tính năng đặt sân (PHP thuần)                      |
| `viet-sport.com/dev`        | Staging site (plugin WP Staging)                   |
| `booking.viet-sport.com`    | Web-app booking riêng (tách codebase – tương lai)  |

---

## 🔧 Cài đặt local

1. Clone về:
    ```bash
    git clone https://github.com/hakudang/viet-sport-wp.git
    ```
2. Đặt trong thư mục `wp-content/themes/swell-child`
3. Kích hoạt Swell Child trong admin WP
4. Cài plugin:
    - WP Staging (nếu muốn tạo bản test)
    - Custom Post Type UI / hoặc tự code CPT
5. Import dữ liệu mẫu (nếu có)

---

## 🔃 Git Workflow

| Branch         | Mục đích                       |
|----------------|-------------------------------|
| `main`         | Code đã test kỹ, deploy production |
| `dev`          | Code đang test trên staging    |
| `feature/xxx`  | Tính năng mới                  |
| `hotfix/xxx`   | Sửa lỗi production             |

### Lệnh git cơ bản:

```bash
git checkout -b feature/booking-form
git add .
git commit -m "✨ Add booking form UI"
git push origin feature/booking-form
```

---

## 🧪 Môi trường phát triển

| Môi trường | Domain                   | Ghi chú             |
|------------|--------------------------|---------------------|
| Local      | `viet-sport.local`       | Phát triển cá nhân  |
| Staging    | `viet-sport.com/dev`     | Dùng WP Staging     |
| Production | `viet-sport.com`         | Live site           |
| Booking    | `booking.viet-sport.com` | (Tùy chọn mở rộng)  |

---

## 🚀 Roadmap (Tầm nhìn mở rộng)

- [x] Hiển thị thông tin từng CLB
- [x] Tạo form đặt sân
- [x] Gửi email mời bạn chơi
- [ ] Quản lý tài khoản người dùng
- [ ] Tự động xác nhận & nhắc lịch qua email
- [ ] Webapp booking tách riêng (SPA + API)

---

## 👨‍💻 Dev Notes

- Không dùng WordPress Multisite
- CPT `sport_team` được định nghĩa tại `includes/cpt/sport_team.php`
- Booking form hoạt động tại route `/booking` (routing PHP thuần)
- Ưu tiên PHP thuần để dễ tùy biến logic

---

## ⚙️ CI/CD (GitHub Actions)

Tự động deploy theme lên server khi push lên `main`.

### 1. Tạo GitHub Secrets

Vào **Settings → Secrets and variables → Actions**, thêm:

| Tên secret           | Mô tả                                     |
|----------------------|--------------------------------------------|
| `FTP_HOST`           | IP hoặc domain FTP server                  |
| `FTP_USERNAME`       | Tên đăng nhập FTP                         |
| `FTP_PASSWORD`       | Mật khẩu đăng nhập                        |
| `FTP_TARGET_DIR`     | Đường dẫn tới thư mục `swell-child` trên server |

### 2. Tạo file workflow

Tạo file `.github/workflows/deploy.yml`:

```yaml
name: 🚀 Deploy WordPress Theme

on:
  push:
    branches:
      - main

jobs:
  deploy:
    name: 🔁 Upload to Server via FTP
    runs-on: ubuntu-latest

    steps:
      - name: 📥 Checkout repo
        uses: actions/checkout@v3

      - name: 📤 Deploy via FTP
        uses: SamKirkland/FTP-Deploy-Action@v4.3.4
        with:
          server: ${{ secrets.FTP_HOST }}
          username: ${{ secrets.FTP_USERNAME }}
          password: ${{ secrets.FTP_PASSWORD }}
          server-dir: ${{ secrets.FTP_TARGET_DIR }}/
          local-dir: ./swell-child/
          protocol: ftp
```

---

## 📄 License

MIT — Tự do chỉnh sửa & sử dụng. Nếu public hãy giữ credit giúp nha 😎

---

> Made with 💪 in Vietnam – [viet-sport.com](https://viet-sport.com)
