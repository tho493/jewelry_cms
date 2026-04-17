# build lại nginx (có public mới)

```sh
docker compose build nginx
```

# pull/update app image nếu cần

```sh
docker compose pull
```
hoặc tự build
```sh
docker compose build app
```

# Update và restart

```sh
docker compose up -d
```

# Update và build
```sh
docker compose up -d --build
```
