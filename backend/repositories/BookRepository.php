<?php
/**
 * Created by PhpStorm.
 * Date: 06.05.18
 * Time: 14:55
 */

namespace app\repositories;

use app\models\Book;

/**
 * Class BookRepository
 * @package app\repositories
 * @author Andrey Sedyshev <a.sedyshev@s-cabinet.ru>
 */
class BookRepository
{

    /**
     * @return Book[]
     */
    public function findAll()
    {
        return Book::find()->all();
    }

    public function findOneById($id): Book
    {
        return Book::findOne($id);
    }
}