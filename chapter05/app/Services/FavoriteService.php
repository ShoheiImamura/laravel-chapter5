<?php
declare(strict_type=1);

namespace App\Services;

use App\DataProvider\Eloquent\Favorite;

// use App\DataProvider\FavoriteRepositryInterface; // リファクタリング後に追加します。

class FavoriteService
{
    // リファクタリング後に追加します

    // private $favorite;

    // public function __construct(FavoriteRepositoryInterface $favorite)
    // {
    //     $this->favorite = $favorite;
    // }

    public function switchFavorite(int $bookId, int $userId, string $createdAt) :int
    {
        return \DB::transaction(
            function () use ($bookId, $userId, $createdAt) {
                $count = Favorite::where('book_id', $bookId)
                ->where('user_id', $userId)
                ->count();
                if($count == 0) {
                    Favorite::create([
                        'book_id' => $bookId,
                        'user_id' => $userId,
                        'created_at' => $createdAt
                    ]);
                    return 1;
                }
                Favorite::where('book_id', $bookId)
                ->where('user_id', $userId)
                ->delete();
                return 0;
            }
        );

        // リファクタリング後
        // public switchFavorite(int $bookId, int $userId, string $createdAt) :int
        // {
        //     return $this->favorite->switch($bookId, $userId, $createdAt);
        // }
    }
}
