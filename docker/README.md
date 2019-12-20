## Usage

```
make
make run
```

## URL

* http://192.168.33.10:8080/

## 注意

1. RedisとUwsgiを使うように変更したのを取り込んでない!

  * ansible/roles/redis/tasks/main.yml
  * ansible/roles/python/tasks/main.yml

2. PHPとNginxも同一のコンテナで動かしてるから分離しなきゃいけない!
