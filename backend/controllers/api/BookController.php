<?php
namespace app\controllers\api;
/**
 * Created by PhpStorm.
 * Date: 06.05.18
 * Time: 14:35
 */

use app\exceptions\BookAddException;
use app\exceptions\ModelValidationException;
use app\services\BookService;
use Yii;

/**
 * Class BookController
 * @author Andrey Sedyshev <a.sedyshev@s-cabinet.ru>
 */
class BookController extends BaseApiController
{
    /** @var BookService */
    private $bookService;

    public function init()
    {
        $this->bookService = Yii::$container->get(BookService::class);
    }

    public function actionList()
    {
        return $this->bookService->getAllBooks();
    }

    public function actionAdd()
    {
        $bookData = Yii::$app->getRequest()->getBodyParams();
        try {
            $book = $this->bookService->addBook($bookData);
        } catch (BookAddException $e) {
            return $this->sendErrorResponse($e);
        } catch (ModelValidationException $e) {
            return $this->sendErrorResponse($e);
        }

        return $book;
    }

}