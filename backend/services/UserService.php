<?php
/**
 * Created by PhpStorm.
 * Date: 06.05.18
 * Time: 14:57
 */

namespace app\services;

use app\exceptions\BookIssueException;
use app\exceptions\BookReturnException;
use app\exceptions\ModelValidationException;
use app\exceptions\UserAddException;
use app\models\Book;
use app\models\BookIssueLog;
use app\models\User;
use app\repositories\BookRepository;
use app\repositories\UserRepository;

/**
 * Class UserService
 * @package app\services
 * @author Andrey Sedyshev <a.sedyshev@s-cabinet.ru>
 */
class UserService
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
     * @param $userData
     * @return User
     * @throws ModelValidationException
     * @throws UserAddException
     */
    public function addUser($userData): User
    {
        $user = new User();
        $user->setAttributes($userData);
        if (!$user->validate()) {
            throw new ModelValidationException($user, 'Invalid user data');
        }
        try {
            $user->save(false);
        } catch (\yii\db\Exception $e) {
            throw new UserAddException('Can\'t add user due to DB problem', $e->getCode(), $e);
        } catch (\Throwable $e) {
            throw new UserAddException('Can\'t add user due to unknown problem', $e->getCode(), $e);
        }

        return $user;
    }

    /**
     * @param User $user
     * @param Book $book
     * @throws BookIssueException
     * @throws ModelValidationException
     */
    public function takeBook(User $user, Book $book): BookIssueLog
    {
        if ($user->getBooks()->count() > 1) {
            throw new BookIssueException('User already has 2 books or more');
        }
        if ($book->taken_by !== null) {
            throw new BookIssueException('Book is already taken');
        }
        $book->taken_by = $user->id;
        $book->issued_at = (new \DateTime())->format('Y-m-d H:i:s');
        if (!$book->validate()) {
            throw new ModelValidationException($book, 'Invalid book data');
        }
        $logEntry = new BookIssueLog(
            [
                'book_id' => $book->id,
                'user_id' => $user->id,
                'date' => $book->issued_at,
                'type' => BookIssueLog::TYPE_ISSUE,
            ]
        );
        if (!$logEntry->validate()) {
            throw new ModelValidationException($logEntry, 'Invalid logEntry data');
        }
        $transaction = $book::getDb()->beginTransaction();
        try {
            $book->save(false);
            $logEntry->save(false);
            $transaction->commit();
        } catch (\yii\db\Exception $e) {
            $transaction->rollBack();
            throw new BookIssueException('Can\'t save book due to DB problem', $e->getCode(), $e);
        } catch (\Throwable $e) {
            throw new BookIssueException('Can\'t save book due to unknown problem', $e->getCode(), $e);
        }

        return $logEntry;
    }

    /**
     * @param User $user
     * @param Book $book
     * @throws BookReturnException
     * @throws ModelValidationException
     */
    public function returnBook(User $user, Book $book): BookIssueLog
    {
        if ($book->taken_by !== $user->id) {
            throw new BookReturnException('Book is not taken by this user');
        }
        $book->taken_by = null;
        $book->issued_at = null;
        if (!$book->validate()) {
            throw new ModelValidationException($book, 'Invalid book data');
        }
        $logEntry = new BookIssueLog(
            [
                'book_id' => $book->id,
                'user_id' => $user->id,
                'date' => (new \DateTime())->format('Y-m-d H:i:s'),
                'type' => BookIssueLog::TYPE_RETURN,
            ]
        );
        if (!$logEntry->validate()) {
            throw new ModelValidationException($logEntry, 'Invalid logEntry data');
        }
        $transaction = $book::getDb()->beginTransaction();
        try {
            $book->save(false);
            $logEntry->save(false);
            $transaction->commit();
        } catch (\yii\db\Exception $e) {
            $transaction->rollBack();
            throw new BookReturnException('Can\'t save book due to DB problem', $e->getCode(), $e);
        } catch (\Throwable $e) {
            throw new BookReturnException('Can\'t save book due to unknown problem', $e->getCode(), $e);
        }

        return $logEntry;
    }
}