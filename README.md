
# 🇻🇳 VIET-SPORT WordPress Project

## 🧭 Giới thiệu

**VietSport** là một nền tảng WordPress sử dụng theme **Swell Child**, được tùy biến để phục vụ website thể thao [viet-sport.com](https://viet-sport.com). Repo này quản lý **toàn bộ mã nguồn theme + database export**.

---

## ⚙️ Tính năng chính

1. **Gửi yêu cầu nội dung đến Ban Quản Trị (BQT)**.
2. **Tìm kiếm & khám phá CLB hoặc sự kiện**.
3. **Tạo sân chơi thể thao**.
4. **Đăng ký tham gia sân chơi**.

---

## 🧱 Cấu trúc repo

```
viet-sport/
├── wp-content/
│   └── themes/
│       └── swell_child/         ← Theme chính (child theme Swell)
├── db-dumps/                    ← Các bản export database (.sql)
├── .gitignore                   ← Đã cấu hình để chỉ track phần cần thiết
├── README.md                    ← (Tập tin này)
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

## 🖥️ Cài đặt Local

1. Clone về thư mục LocalWP:
    ```bash
    git clone https://github.com/hakudang/viet-sport-wp.git
    ```
2. Đặt đúng vào folder: `C:/Users/shaku/Local Sites/viet-sport`
3. Kích hoạt Swell Child trong WP Admin
4. Import database từ `db-dumps/*.sql`
5. Cài các plugin:
    - WP Staging
    - Custom Post Type UI (hoặc dùng code CPT)

---

## 🔃 Git Workflow

| Branch         | Mục đích                       |
|----------------|-------------------------------|
| `main`         | Code đã kiểm duyệt, production |
| `dev`          | Nhánh phát triển, staging      |
| `feature/xxx`  | Tính năng mới                  |
| `hotfix/xxx`   | Sửa lỗi khẩn cấp               |

---

## 🚀 CI/CD – Deploy tự động lên Staging

Quá trình triển khai tự động lên môi trường **Staging** thông qua **GitHub Actions** giúp kiểm thử các thay đổi trước khi đưa lên môi trường production.

### Workflow: `deploy-staging.yml`

Workflow **`deploy-staging.yml`** sẽ triển khai theme **Swell Child** lên máy chủ Staging khi có thay đổi trong nhánh `dev`. Quy trình này được thực hiện thủ công thông qua GitHub UI.

#### Các bước triển khai:
1. **Checkout Code**: Đầu tiên, mã nguồn sẽ được kiểm tra từ GitHub repository.
2. **Deploy to Staging**: Mã nguồn sẽ được triển khai lên máy chủ Staging qua FTP.

#### Cấu hình trong file `deploy-staging.yml`:

```yaml
name: 🚀 Deploy Swell Child Theme to Staging

on:
  # Chạy thủ công khi cần thiết
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

#### Các thông số chính:
- **`server`**: Địa chỉ máy chủ Staging (lưu trong **GitHub Secrets**).
- **`username` và `password`**: Tài khoản FTP dùng để kết nối đến máy chủ Staging.
- **`server-dir`**: Thư mục trên máy chủ Staging để triển khai theme.
- **`local-dir`**: Thư mục cục bộ chứa theme.
- **`exclude`**: Loại trừ các thư mục như `.git` và `node_modules`.

---

## 🧪 Môi trường phát triển

| Môi trường | Domain                   | Mục đích          |
|------------|--------------------------|-------------------|
| Local      | `viet-sport.local`        | Phát triển cá nhân |
| Staging    | `viet-sport.com/dev`      | Kiểm thử           |
| Production | `viet-sport.com`          | Website chính      |
| Booking    | `booking.viet-sport.com`  | SPA tách biệt      |

---

## 🚀 Roadmap

- [x] Thiết lập theme SWELL Child cơ bản
- [x] Thêm custom post type: Sport Event, Sport Team
- [x] Tích hợp taxonomy: Sport name, Category, Location, Status
- [x] Hiển thị thông tin từng CLB
- [ ] Tạo form đặt sân
- [ ] Gửi email mời bạn chơi
- [x] Quản lý tài khoản người dùng
- [ ] Tự động xác nhận & nhắc lịch qua email
- [ ] Webapp booking tách riêng (SPA + API)

---

## 📄 License

MIT — Free to use, public, credit appreciated.

> Made with 💪 in Japan – [viet-sport.com](https://viet-sport.com)
