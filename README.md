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

- 参考) [\Illuminate\Database\Eloquent\Model](https://github.com/ShoheiImamura/laravel-chapter5/blob/master/chapter05/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php)

---

### 5-3-2規約とプロパティ

- Eloquent クラスはデフォルトで、対応するテーブルや、プライマリーキーが設定されている
- プロパティを指定することで、任意の設定に変更できる


| プロパティ    | 説明                             | デフォルト値                                    |
|---------------|----------------------------------|-------------------------------------------------|
| $table        | 紐づくテーブル                   | (※クラス名の複数形)                             |
| $primaryKey   | プライマリキー                   | 「id」カラム                                    |
| $fillable     | 一括編集を許可するカラム         | 未設定                                          |
| $guarded      | 一括編集を許可しないカラム       | 未設定                                          |
| $connection   | データベース接続                 | 設定ファイル database.phpで設定されたデフォルト |
| $dateFormat   | タイムスタンプのフォーマット     | Y-m-d H:i:s                                     |
| $incrementing | プライマリキーが自動増加かどうか | true                                            |

---

### データ検索・データ更新の基本

- eloquent クラスのメソッドを用いて、データ検索や登録、更新ができる

| メソッド         | 説明                                   | 補足                                 |
|------------------|----------------------------------------|--------------------------------------|
| all              | テーブルの全レコードを取得             | Collectionクラスのインスタンスが返る |
| find ,findOrFail | プライマリキーが合致するレコードを取得 | Eloauent のインスタンスが返る        |
| whereXXX         | 条件を引数に指定して、絞り込みを行う   | Collection                           |
| create, save     | データを配列で登録する                 | 配列を引数とする                     |
| update, save     | データを更新する                       |                                      |
| delete, destroy  | データを削除する                       |                                      |

---

### データ操作の応用

- 「クエリビルダ」を用いると PHP でデータ抽出可能
- データ抽出結果を JSON 形式に変換
- 「アクセサ」「ミューテータ」により、カラム固定の編集可能
- データがない場合に登録するという処理
- 論理削除

--

### リレーション

- Eloquent はテーブル同士のリレーションを記述できる
- リレーション機能を用いると、データ取得がシンプルに記述できる
- [
Laravel 5.5 Eloquent：リレーション
](https://readouble.com/laravel/5.5/ja/eloquent-relationships.html)
  - 1:1 の関係
  - 1:N の関係
  - ...

--

### 一対一関係の定義

- 「hasOne」メソッド、「belongsTo」メソッドを用いる　　　
  - 親は子を「hasOne」する
  - 子は親に「belongsTo」する
- 例）書籍(books)テーブルと書籍詳細(bookdetails)テーブル

--

### リレーション定義されたカラムの呼び出し

```php
$book = \App\Book::find(1);
echo $book->detail->isbn; // 書籍から書籍詳細を経由してISBNを取得することが可能
```

--

### 一対他関係の定義

- 「hasMany」メソッド、「belongsTo」メソッドを用いる　　
  - 親は子を「hasMany」する
  - 子は親に「belongsTo」する
- 例）著者(authors)テーブルと書籍(books)テーブル

--

### リレーション定義されたカラムの呼び出し2

```php
$books = \App\Author::find(1)->books;

foreach($books as $book) {
    echo $author->books->name; // Autor モデルから書籍名を取得する
}
```

--

### その他の関係

- [公式リファレンス参照](https://laravel.com/api/5.5/Illuminate/Database/Eloquent/Relations/BelongsTo.html)

--

### 実行されるSQLの確認

- 「クエリビルダ」を用いても、SQLが発行されデータベースへの問い合わせを実行
- レコード数が多い場合は、そのパフォーマンスに留意する必要がある
  - 「toSql」メソッドは、実行前の SQL を取得できる（実行は行われない）
  - 「getQueryLog」メソッドは、実行された SQL を取得できる

---

### 5-4 クエリビルダ

- クエリビルダは、メソッドチェーンを使って SQL を発行する仕組み

![](https://user-images.githubusercontent.com/39234750/63050221-8bab7980-bf15-11e9-80d2-9dc369fd0364.png)

--

### クエリビルダ

- クエリビルダの書式は、以下３段階に分かれる
  - クエリビルダインスタンスの取得
  - メソッドチェーンによる処理対象や処理内容の特定
  - クエリ実行
- データ検索、更新、削除のいずれも共通の書式

```php
// ベースとなるクエリビルダオブジェクト
$results = DB::table('books')
// メソッドチェーンによる処理対象や処理内容の特定
->select(['bookdetails.isbn', 'books.name', 'authors.name', 'bookdetails.price'])
->leftJoin('bookdetails', 'books.bookdetail_id', '=', 'bookdetails.id')
->leftJoin('authors', 'books.author_id', '=', 'authors.id')
->where('bookdetails.price', '>=', 1000)
->where('bookdetails.published_date', '>=', '2011-01-01')
->orderBy('bookdetails.published_date', 'desc')
// クエリ実行
->get()
```

### クエリビルダの取得

- DBファサードから取得
- Illuminate\Database\Connection (DBファサードの実体)から取得
  - コンストラクタインジェクションを利用する場合も

--

### クエリビルダ取得例

```php
// DBファサードからbooksテーブルのクエリビルダ取得
$query = DB::table('books');

// サービスコンテナDatabaseManagerクラスのインスタンスを取得
$db = \Illuminate\Foundation\Application::getInstance()->make('db');
// 上記インスタンスからConnection クラスのインスタンスを取得
$connection = $db->connection();
// 上記インスタンスからクエリビルだを取得
$query = $connection->table('books');
```

### データ操作専用のクラス1

- コンストラクタインジェクションを用いて、提供元クラスを与える

```php
namespace App\DataAccess;

use Illuminate\Database\DatabaseManager;

class BookDataAccessObject
{
    protected $db;
    protected $table = 'books';

    public function __construct(DatabaseManager $db)
    {
        $this->db = "db";
    }

    public function find($id)
    {
        $query = $this->db->connection()
        ->table($this->table);
    }
}
```

---

### 処理対象や内容の特定

- 取得したクエリビルダに対して、処理対象や処理内容を追記
- 以下の機能を紹介
  - 取得対象のカラムを特定する
  - 処理対象のデータを特定する
  - レコードの取得数や取得開始位置を指定する
  - 集計系
  - テーブルを結合する

--

### 取得対象のカラムを特定する

- Select 系メソッド

```php
// 取得対象のカラム名を指定する
$result = DB::table('books')->select('id', 'name as title')->get();
// select 文の中身を SQL で直接指定する
$result = DB::table('books')->selectRaw('id , name as title')->get();
```

--

### 処理対象のデータを特定する

- where 系メソッド

```php
$reslts = DB::table('books')
    // カラム名, 比較演算子, 条件値
    ->where('id', '>=', '2018-01-01')
    // or 条件を追加する場合は、orXXXX メソッドを利用する
    ->orWhere('created_at', '>=', '2018-01-01')
    ->get();
```

--

### レコードの取得数や取得開始位置を指定する

- limit (take) メソッド
- offset (skip) メソッド

```php
$results = DB::table('books')
    // limit 値を指定する
    ->limit(10)
    // offset 値を指定する
    ->offset(6)
    ->get();
```

--

### 集計系

- orderBy メソッド
- groupBy メソッド
- having メソッド
- havingRaw メソッド

```php
// orderBy メソッドによる複数カラムソート
$results = DB::table('books')
    // 第一ソートキー
    ->orderBy('id')
    // 第二ソートキー
    ->orderBy('updated_at', 'desc')
    ->get();
```

--

### テーブルを結合する

- join 系メソッド

```php
$results = DB::table('books')
    ->leftJoin('authors', 'books.author_id', '=', 'authors.id')
    ->leftJoin('publishers', 'books.publisher_id', '=', 'publishers.id')
    ->get();
```

```sql
# 上記のクエリビルダにて発行されるSQL
SELECT * FROM 'books'
    LEFT JOIN 'authors'
        ON 'books'.'author_id' = 'authors'.'id'
    LEFT JOIN 'publishers'
        ON 'books'.'publisher_id' = 'publishers'.'id'
```

---

### クエリの実行

- クエリビルダの処理内容を指定後、実行するメソッド
- 以下メソッドを紹介
  - データを取得
  - データの集計値を取得
  - データを登録、更新、削除

--

### データの取得

- get()
- first()

```php
// get メソッドでbooks テーブルの データを取得
$results = DB::table('books')->select('id', 'name')->get();
// get メソッドで取得した値は、stdClass のコレクション
foreach ($results as $book) {
    echo $book->id;
    echo $book->name;
}
```

### データの集計値を取得

- count()
- max()
- min()
- avg()

```php
// count メソッドでデータの件数を取得する
$count = DB::table('books')->count();
echo $count;
```

--

### データの登録、更新、削除

- insert()
- update()
- delete()
- truncate()

```php
// bookdetails テーブルの id = 1 レコードを更新する
DB::table('bookdetails')->where('id', 1)->update(['price' => 10000]);
```

```sql
# 上記クエリビルダにて発行されるSQL
UPDATE 'bookdetails' SET 'price' = 10000 WHERE 'id' = 1
```

---

### トランザクションとテーブルロック

- トランザクション処理やテーブルロック関連の処理も記述可能

| メソッド名                  | 説明                                       |
|-----------------------------|--------------------------------------------|
| DB::beginTransaction()      | 手動でトランザクションを開始               |
| DB::rollback()              | 手動でロールバックを実行                   |
| DB::commit()                | 手動でトランザクションをコミット           |
| DB::transaction(クロージャ) | クロージャの中でトランザクションを実施する |
| sharedLock()                | select された行に共有ロック                |
| lockForUpdate()             | select された行に排他ロック                |

--

### その他のクエリビルダメソッド

- [公式サイトリファレンス]()

---

### ベーシックなデータ操作

- SQL 文をそのまま記述して実行するメソッドも利用可能
  ｰ 第１引数：SQL 文
  - 第２引数：プリペアドステートメント（クエリに結合する引数）
- 処理速度は速くなるが、コーディングや可読性とのトレードオフ

| メソッド名                      | 説明                      |
|---------------------------------|---------------------------|
| DB::select('select クエリ', []) | select 文によるデータ抽出 |
| DB::insert('insert クエリ', []) | insert 文によるデータ登録 |
| DB::update('update クエリ', []) | update 文によるデータ更新 |
| DB::delete('delete クエリ', []) | delete 文によるデータ更新 |
| DB::statement('SQL クエリ', []) | 上記意外の SQL を実行     |

--

### DB::select を使用したデータ抽出

```php
// select クエリ
$sql = 'SELECT bookdetails.isbn book.name '
    . 'FROM books '
    .'LEFT JOIN bookdetails ON books.bookdetail_id = bookdetails.id '
    .'WHERE bookdetails.price >= ? AND bookdetails.published_date >= ? '
    .'ORDER BY bookdetails.published_date DESC';
$results = DB::select($sql, ['1000', '2011-01-01']);
```

---

### 5-5 リポジトリパターン

--

### リポジトリパターンの概要

- データストアの参照先が変更された場合の、プログラム変更を限定する
  - 「ビジネスロジック」と「データアクセス処理」を切り離す
  - ビジネスロジックは、データストア先を意識しない
- クラス数が増えるため、デモプログラムや期間限定のプログラムには不適

---

### リポジトリパターンの実装

- 「いいね」機能で実装例を確認
  - WebAPI で実現
- 密結合の状態から疎結合へリファクタリング

![](https://user-images.githubusercontent.com/39234750/63089647-812fc500-bf93-11e9-8d67-8c85b7c9339e.png)
- いいね機能を追加します

--

### リポジトリパターンの考え方

![](https://user-images.githubusercontent.com/39234750/63089880-57c36900-bf94-11e9-9d8c-c8b1995d8ded.png)

--

