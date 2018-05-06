<?php
namespace app\controllers\api;
/**
 * Created by PhpStorm.
 * Date: 04.05.18
 * Time: 11:55
 */

use app\exceptions\ModelValidationException;
use yii\filters\ContentNegotiator;
use yii\web\Response;

/**
 * Class BaseApiController
 * @author Andrey Sedyshev <a.sedyshev@s-cabinet.ru>
 */
abstract class BaseApiController extends \yii\rest\Controller
{
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    protected function sendErrorResponse(\Exception $e) {
        if ($e instanceof ModelValidationException) {
            return [
                'errors' => $e->getModelErrors()
            ];
        }
        return [
            'error' => $e->getMessage(),
        ];
    }
}