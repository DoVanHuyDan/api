### Tech stack

-   PHP (Laravel Framework v6.8)
-   Mysql v8.0

### Cách cài đặt môi trường development:

**Recommend:**

-   Cài đặt môi trường Docker runtime
    -   Cài đặt trên Window (https://docs.docker.com/docker-for-windows/install/)
    -   Cài đặt trên Macos (https://docs.docker.com/docker-for-mac/install/)
    -   Cài đặt trên Linux (Ubuntu) (https://docs.docker.com/install/linux/docker-ce/ubuntu/)
-   Clone source dự án về máy (yêu cầu đã cài đặt Git) - hoặc download source theo link (https://gitlab.com/solashi.com/cae/kdc/api/-/archive/develop/api-develop.zip)

```
$ git clone https://gitlab.com/solashi.com/cae/kdc/api.git
```

-   Tại con trỏ thu mục dự án (đang mở bằng bash hoặc shell), sử dụng docker-compose cli

```
$ docker-compose up -d
```

-   Một api server sẽ chạy tại địa chỉ: http://localhost:8000

**Cài đặt thủ công**

-   Cài đặt nginx (web proxy) (https://www.nginx.com/resources/wiki/start/topics/tutorials/install/)

```
File cấu hình tham khảo trong thư mục source: ./docker/nginx/default.conf
```

-   Cài đặt php (php-fpm) (https://www.google.com/search?q=install+php-fpm)

```
File cấu hình tham khảo trong thư mục source: ./docker/php/php.ini
```

-   Cài đặt mysql (https://www.google.com/search?q=install+mysql)

```
File cấu hình tham khảo trong thư mục source: ./docker/mysql/my.cnf
```

**Khởi tạo dữ liệu (demo)**
- Tạo file `.env` từ file `.env.example` trong thư mục source dự án
- Có thể phải khởi động lại môi trường
```
$ docker-compose down
$ docker-compose up -d
```
- Chạy lệnh tạo bảng & dữ liệu demo
```
$ docker-compose exec php-fpm php artisan migrate --seed
```