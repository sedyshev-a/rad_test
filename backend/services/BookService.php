<?php
/**
 * Created by PhpStorm.
 * Date: 06.05.18
 * Time: 14:57
 */

namespace app\services;

use app\exceptions\BookAddException;
use app\exceptions\ModelValidationException;
use app\models\Book;
use app\repositories\BookRepository;
use app\repositories\UserRepository;

/**
 * Class BookService
 * @package app\services
 * @author Andrey Sedyshev <a.sedyshev@s-cabinet.ru>
 */
class BookService
{
    private $userRepository;
    private $bookRepository;

    public function __construct(
        UserRepository $userRepository,
        BookRepository $bookRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->bookRepository = $bookRepository;
    }

    /**
     * @param $bookData
     * @return Book
     * @throws BookAddException
     * @throws ModelValidationException
     */
    public function addBook($bookData): Book
    {
        $book = new Book();
        $book->setAttributes($bookData);
        if (!$book->validate()) {
            throw new ModelValidationException($book, 'Invalid book data');
        }
        try {
            $book->save(false);
        } catch (\yii\db\Exception $e) {
            throw new BookAddException('Can\'t add book due to DB problem', $e->getCode(), $e);
        } catch (\Throwable $e) {
            throw new BookAddException('Can\'t add book due to unknown problem', $e->getCode(), $e);
        }

        return $book;
    }


    /**
     * @return Book[]
     */
    public function getAllBooks()
    {
        return $this->bookRepository->findAll();
    }

}