<?php
namespace app\controllers\api;
/**
 * Created by PhpStorm.
 * Date: 04.05.18
 * Time: 12:04
 */

use app\exceptions\BookIssueException;
use app\exceptions\BookReturnException;
use app\exceptions\ModelValidationException;
use app\exceptions\UserAddException;
use app\repositories\BookRepository;
use app\repositories\UserRepository;
use app\services\UserService;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class UserController
 * @author Andrey Sedyshev <a.sedyshev@s-cabinet.ru>
 */
class UserController extends BaseApiController
{
    /** @var UserService */
    private $userService;

    /** @var UserRepository */
    private $userRepository;

    /** @var BookRepository */
    private $bookRepository;

    public function init()
    {
        $this->userService = Yii::$container->get(UserService::class);
        $this->userRepository = Yii::$container->get(UserRepository::class);
        $this->bookRepository = Yii::$container->get(BookRepository::class);
    }
    
    public function actionList()
    {
        return \app\models\User::find()->all();
    }

    public function actionAdd()
    {
        $userData = Yii::$app->getRequest()->getBodyParams();
        try {
            $user = $this->userService->addUser($userData);
        } catch (UserAddException $e) {
            return $this->sendErrorResponse($e);
        } catch (ModelValidationException $e) {
            return $this->sendErrorResponse($e);
        }

        return $user;
    }

    public function actionGetBooks($userId)
    {
        $user = $this->userRepository->findOneById($userId);
        if ($user === null) {
            $this->sendErrorResponse(
                new NotFoundHttpException("User with userId = {$userId} not found")
            );
        }

        return $user->books;
    }

    public function actionTakeBook()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        if (!isset($params['userId']) || empty($params['userId'])) {
            $this->sendErrorResponse(new BadRequestHttpException('\'userId\' required'));
        }
        if (!isset($params['bookId']) || empty($params['bookId'])) {
            $this->sendErrorResponse(new BadRequestHttpException('\'bookId\' required'));
        }
        $user = $this->userRepository->findOneById($params['userId']);
        if ($user === null) {
            $this->sendErrorResponse(
                new NotFoundHttpException("User with userId = {$params['userId']} not found")
            );
        }
        $book = $this->bookRepository->findOneById($params['bookId']);
        if ($book === null) {
            $this->sendErrorResponse(
                new NotFoundHttpException("Book with bookId = {$params['bookId']} not found")
            );
        }

        try {
            return $this->userService->takeBook($user, $book);
        } catch (BookIssueException $e) {
            return $this->sendErrorResponse($e);
        } catch (ModelValidationException $e) {
            return $this->sendErrorResponse($e);
        }
    }

    public function actionReturnBook()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        if (!isset($params['userId']) || empty($params['userId'])) {
            $this->sendErrorResponse(new BadRequestHttpException('\'userId\' required'));
        }
        if (!isset($params['bookId']) || empty($params['bookId'])) {
            $this->sendErrorResponse(new BadRequestHttpException('\'bookId\' required'));
        }
        $user = $this->userRepository->findOneById($params['userId']);
        if ($user === null) {
            $this->sendErrorResponse(
                new NotFoundHttpException("User with userId = {$params['userId']} not found")
            );
        }
        $book = $this->bookRepository->findOneById($params['bookId']);
        if ($book === null) {
            $this->sendErrorResponse(
                new NotFoundHttpException("Book with bookId = {$params['bookId']} not found")
            );
        }

        try {
            return $this->userService->returnBook($user, $book);
        } catch (ModelValidationException $e) {
            return $this->sendErrorResponse($e);
        } catch (BookReturnException $e) {
            return $this->sendErrorResponse($e);
        }
    }

}