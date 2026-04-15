# 🐳 Docker Deploy – Jewelry CMS

## Cấu trúc Docker

```
docker/
├── entrypoint.sh          # Script khởi động: migrate, cache, storage:link
├── nginx/
│   └── default.conf       # Nginx server config
└── mysql/
    └── my.cnf             # MySQL tuning config

Dockerfile                 # Multi-stage build (Node → Composer → PHP-FPM)
docker-compose.yml         # 5 services: app, queue, nginx, mysql, phpmyadmin
.env.docker                # Template env cho Docker (copy thành .env)
```

## Services

| Service | Image | Port |
|---------|-------|------|
| `app` | PHP 8.3-FPM Alpine | 9000 (internal) |
| `queue` | PHP 8.3-FPM Alpine | — |
| `nginx` | Nginx 1.27 Alpine | **8100** |
| `mysql` | MySQL 8.0 | **33306** (host) |
| `phpmyadmin` | phpMyAdmin 5 | **8181** (dev only) |

## Deploy lần đầu

```bash
# 1. Copy và chỉnh sửa env
cp .env.docker .env
# Sửa ít nhất: APP_KEY, DB_PASSWORD, DB_ROOT_PASSWORD, APP_URL

# 2. Tạo APP_KEY
docker run --rm php:8.3-alpine php -r "echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;"
# Copy giá trị vào APP_KEY trong .env

# 3. Build và start
docker compose up -d --build

# 4. Kiểm tra logs
docker compose logs -f app
```

## Lệnh thường dùng

```bash
# Xem trạng thái
docker compose ps

# Xem logs realtime
docker compose logs -f app
docker compose logs -f queue

# Artisan commands
docker compose exec app php artisan migrate
docker compose exec app php artisan cache:clear
docker compose exec app php artisan queue:restart

# Vào shell
docker compose exec app bash

# Restart service
docker compose restart app nginx

# Stop tất cả
docker compose down

# Stop + xóa volumes (CẢNH BÁO: mất data)
docker compose down -v
```

## Mở phpMyAdmin (chỉ dev/staging)

```bash
# Start với profile dev
docker compose --profile dev up -d

# Truy cập: http://localhost:8080
```

## Cập nhật code (redeploy)

```bash
# Rebuild image và restart
docker compose up -d --build app queue

# Clear cache sau deploy
docker compose exec app php artisan config:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
```

## Lưu ý

- `storage/app/public` được mount qua Docker Volume `app_storage` — **không mất khi redeploy**
- MySQL data lưu trong volume `mysql_data` — bền vững
- Entrypoint tự động chạy `migrate` và `storage:link` mỗi khi start
- Lần đầu deploy, entrypoint tự seed DB nếu bảng `users` trống
