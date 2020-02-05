# laravel_rss_reader
10年以上前に作ったmobile_rss_readerをlaravel勉強がてら移植中

[![codecov](https://codecov.io/gh/akira345/laravel_rss_reader/branch/master/graph/badge.svg)](https://codecov.io/gh/akira345/laravel_rss_reader)

----
動作環境
  * laravel 6.0.0以上
  * PostgreSQL 10、11
  * Memcached 1.5.6
  * PHP 7.2以上

----
デプロイ方法

 1. チェックアウト 
 
    ```
    git clone https://github.com/akira345/laravel_rss_reader.git
    ```

 1. インストール

    ```
    cd laravel_rss_reader
    composer install
    ```

 1. 設定のひな型をコピーしてハッシュ生成

    ```
    cp .env.example .env
    php artisan key:generate
    ```

1. 設定する

    ```
    vi .env
    ```

    ----
    * .envの設定内容について
    以下の内容を変更します。ない項目は追記します。

    ```
    APP_NAME=RSSリーダー
    APP_ENV=production
    APP_DEBUG=false
    APP_URL=https://<動かすサーバのアドレス>
    DB_CONNECTION=pgsql
    DB_HOST=<DBサーバのアドレス>
    DB_PORT=5432
    DB_DATABASE=<データベース名>
    DB_USERNAME=<データベースユーザ名>
    DB_PASSWORD=<データベースパスワード>
    MEMCACHED_HOST=<memcachedサーバ名>
    MEMCACHED_PORT=11211
    SESSION_STORE=memcached
    MAIL_DRIVER=smtp
    MAIL_HOST=<メールサーバアドレス>
    MAIL_PORT=25
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS=<メール送信時に送るFromのアドレス>
    MAIL_FROM_NAME=<上記メアドのユーザ名>
    ```

1. ログファイルやキャッシュに書き込めるようパーミッションを設定します。

    ```
    chmod -R 777 ./storage ./bootstrap/cache
    ```

1. DBにマイグレーションをします。本番環境だけどいい？と聞かれますので、YESとします。

    ```
    php artisan migrate
    ```

1. publicディレクトリをドキュメントルートに設定します。サーバによるので、各自調べてください。
1. Cronに以下の内容を設定します。

    ```
    * * * * * php /<インストールしたディレクトリ>/laravel_rss_reader/artisan schedule:run >> /dev/null 2>&1
    ``` 
