---
theme: "white"
transition: "default"
progress: true
slideNumber: true
loop: true
backgroundTransition: 'zoom'



---

# 5章

## データベース

---

## はじめに

- 本資料は、Weeyble Laravel 輪読会用の資料です
- 対応箇所は、5章の後半部分のみです
- 権利関係で問題がございましたら、ご指摘ください
- このスライドは reveal.js で閲覧することを前提に作成しています
  - 参考：[非エンジニアのためのお手軽reveal.js入門](https://jyun76.github.io/revealjs-vscode/)

--

## 発表者自己紹介

- 今村昌平と申します。
- Web業務システム受託の会社で勤務(1年半)
- Webアプリ作成中(マニュアル共有アプリ)

--

## 本日の概要

### 前半

- 5-1 マイグレーション　　　　　　
- 5-2 シーダー　　　　　　　　　　

### 後半

- 5-3 Eloquent　　　　　　　　　　
- 5-4 クエリビルダ　　　　　　　　
- 5-5 リポジトリパターン　　　　　

---

### 5-3 Eloquent

- ORM (Object Relational Mapping) である
- データベースとモデルを関連付ける
- テーブルと Eloquent クラスは、1:1

--

### ORM (Object Relational Mapping)

- RDB （関係データベース）のレコードを、オブジェクトとして扱う

| ORM          | フレームワーク |
|--------------|----------------|
| ActiveRecord | Ruby on Rails  |
| Core Data    | Mac OS X、 iOS |
| ...          | ...            |

--

### Eloquent クラスの生成

- artisan コマンド で生成
- デフォルトは /app 直下に作成

    php artisan make:model (クラス名)

--

### 作成直後のクラス

```php
namespace App;

use Illuminate\Database\Eloquent\Model;

// Illuminate\Database\Eloquent\Model を継承したクラスが作成される
class Author extends Model
{
    //
}
```
