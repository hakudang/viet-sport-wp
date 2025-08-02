# 🇻🇳 VIET-SPORT WordPress Theme (Swell Child)

## Giới thiệu

**VietSport** là một nền tảng WordPress sử dụng theme **Swell** và phát triển trên **Child Theme**, được tùy biến để phục vụ website thể thao [viet-sport.com](https://viet-sport.com).

---

## ⚙️ Tính năng chính

1. **Gửi yêu cầu tạo nội dung đến Ban Quản Trị (BQT):**
   - Người dùng có thể gửi yêu cầu tạo các trang:
     - Câu lạc bộ thể thao (CLB)
     - Sự kiện thể thao
     - Thành tích vận động viên
   - Yêu cầu sẽ được kiểm duyệt và xác nhận qua email.
   - BQT sẽ tạo thủ công trang tương ứng và gửi lại URL dưới các định dạng:
     - `viet-sport.net/sport_team/abc`
     - `viet-sport.net/sport_event/abc`
     - `viet-sport.net/athlete/abc`

2. **Tìm kiếm & khám phá CLB hoặc sự kiện:**
   - Cho phép người dùng tìm kiếm các câu lạc bộ hoặc sự kiện để tham gia và giao lưu.

3. **Tạo sân chơi thể thao:**
   - Người dùng có thể tạo sân chơi mới trên hệ thống.
   - Đăng thông tin chi tiết lên diễn đàn VietSport.

4. **Đăng ký tham gia sân chơi:**
   - Cho phép người dùng gửi yêu cầu xin tham gia các sân chơi thể thao có sẵn.

---

## 🔧 Công nghệ sử dụng

- WordPress CMS
- Swell Theme (Parent)
- Swell Child Theme (tùy biến cho VietSport)

---

> **Ghi chú:** Toàn bộ code được viết trong Child Theme và được thiết kế riêng cho `viet-sport.com` để đảm bảo tính ổn định và dễ nâng cấp.


---

## 🏗️ Kiến trúc hệ thống

```
wp-content/themes/swell-child/
├── functions.php                ← Khởi động hệ thống (require init)
├── style.css                    ← Custom style (tuân theo Swell)
├── includes/                    ← Code chính: CPT, taxonomy, config
│   ├── init.php                 ← Require toàn bộ file CPT/taxonomy
│   ├── cpt/
│   │   └── sport_team.php       ← Custom Post Type CLB
│   │   └── sport_event.php      ← Custom Post Type event
│   └── taxonomies/
│       └── team_category.php    ←Đăng ký taxonomy team_category cho sport_team
│       └── team_location.php    ←Đăng ký taxonomy team_location cho sport_team
│       └── team_sport_name.php  ←Đăng ký taxonomy team_sport_name cho sport_team
│       └── team_status.php      ←Đăng ký taxonomy team_status cho sport_team
│       └── event_category.php   ←Đăng ký taxonomy event_category cho sport_event
│       └── event_location.php   ←Đăng ký taxonomy event_location cho sport_event
│       └── event_sport_name.php ←Đăng ký taxonomy event_sport_name cho sport_event
│       └── event_status.php     ←Đăng ký taxonomy event_status cho sport_event
├── booking/                     ← Tính năng đặt sân (PHP thuần)
│   ├── index.php                ← Route chính: viet-sport.com/booking
│   ├── form.php                 ← Form đặt sân
│   ├── handler.php              ← Xử lý lưu dữ liệu booking
│   └── mail.php                 ← Gửi email mời bạn
├── .gitignore
└── README.md                    ← (tập tin này)
```

---

## 🌐 URL Structure

| URL                          | Mục đích                                           |
|------------------------------|----------------------------------------------------|
| `viet-sport.com`             | Web chính, landing page, giới thiệu                |
| `viet-sport.com/sport_team/` | Trang CPT của từng CLB                             |
| `viet-sport.com/sport_event/`| Trang CPT của từng event                           |
| `viet-sport.com/booking`     | Tính năng đặt sân (PHP thuần)                      |
| `viet-sport.com/dev`         | Staging site (plugin WP Staging)                   |
| `booking.viet-sport.com`     | Web-app booking riêng (tách codebase – tương lai)  |

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
