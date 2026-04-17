# build lại nginx (có public mới)

```sh
docker compose build nginx
```

# pull/update app image nếu cần

```sh
docker compose pull
```

# Update và restart

```sh
docker compose up -d
```

## Cấp quyền cho docker_data

```sh
sudo chown -R 1000:1000 docker_data/
```

- chỉ chạy khi lệnh trên vẫn không hoạt động:

```sh
sudo chmod -R 777 docker_data/
```
