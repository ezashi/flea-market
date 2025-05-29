# coachtechフリマ

フリーマーケットアプリケーション

## 環境構築

**Dockerビルド**
1. `git clone https://github.com/ezashi/flea-market.git`
2. DockerDesktopアプリを立ち上げる
3. `docker-compose up -d --build`

**Laravel環境構築**
1. PHPコンテナに入る：`docker-compose exec php bash`
2. `composer install`
3. 「.env.example」ファイルから「.env」を作成し、環境環境変数を変更：`cp .env.example .env`
4. `.env`に以下の環境変数を追加
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```
5. アプリケーションキーの作成：`php artisan key:generate`
6. マイグレーションの実行：`php artisan migrate`
7. シーディングの実行：`php artisan db:seed`
8. シンボリックリンクの作成：`php artisan storage:link`

## 使用技術(実行環境)
- PHP 7.4.9
- Laravel 8.83.8
- MySQL 8.0.26
- nginx 1.21.1
- Docker
- Docker-compose

## ER図
```mermaid
erDiagram
    users ||--o{ items : "sells"
    users ||--o{ items : "buys"
    users ||--o{ likes : "creates"
    users ||--o{ comments : "writes"

    items ||--o{ likes : "receives"
    items ||--o{ comments : "receives"
    items ||--o{ category_item : "belongs to"

    categories ||--o{ category_item : "has"

    users {
        bigint id PK
        string name
        string email UK
        timestamp email_verified_at
        string password
        string profile_image
        boolean is_profile_completed
        string postal_code
        string address
        string building
        string remember_token
        timestamp created_at
        timestamp updated_at
    }

    items {
        bigint id PK
        string image
        string condition
        string name
        string brand
        text description
        integer price
        bigint seller_id FK
        bigint buyer_id FK
        boolean sold
        string payment_method
        timestamp created_at
        timestamp updated_at
    }

    categories {
        bigint id PK
        string name
        timestamp created_at
        timestamp updated_at
    }

    conditions {
        bigint id PK
        string name
        timestamp created_at
        timestamp updated_at
    }

    likes {
        bigint id PK
        bigint user_id FK
        bigint item_id FK
        timestamp created_at
        timestamp updated_at
    }

    comments {
        bigint id PK
        bigint user_id FK
        bigint item_id FK
        text content
        timestamp created_at
        timestamp updated_at
    }

    category_item {
        bigint id PK
        bigint category_id FK
        bigint item_id FK
        timestamp created_at
        timestamp updated_at
    }
```

## URL
| 機能 | URL | HTTPメソッド | 認証 |
|------|-----|-------------|------|
| 会員登録 | `/register` | GET/POST | 不要 |
| ログイン | `/login` | GET/POST | 不要 |
| ログアウト | `/logout` | POST | 必要 |
| 商品一覧 | `/` | GET | 不要 |
| 商品詳細 | `/item/{item}` | GET | 不要 |
| マイリスト | `/mylist` | GET | 必要 |
| プロフィール登録・編集 | `/profile` | GET/POST | 必要 |
| マイページ | `/mypage` | GET | 必要 |
| 購入履歴 | `/mypage/buy` | GET | 必要 |
| 出品履歴 | `/mypage/sell` | GET | 必要 |
| プロフィール編集(マイページ) | `/mypage/profile` | GET/POST | 必要 |
| 商品出品 | `/sell` | GET/POST | 必要 |
| 商品購入 | `/purchase/{item}` | GET | 必要 |
| 支払い方法選択 | `/purchase/{item}/payment` | POST | 必要 |
| 住所変更 | `/purchase/address/{item}` | GET/POST | 必要 |
| 購入確定 | `/purchase/{item}` | POST | 必要 |
| いいね機能 | `/items/{item}/like` | POST | 必要 |
| コメント投稿 | `/items/{item}/comment` | POST | 必要 |