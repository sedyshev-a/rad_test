<?php
/**
 * Created by PhpStorm.
 * Date: 06.05.18
 * Time: 15:34
 */

namespace app\exceptions;

use Throwable;
use yii\base\Model;

/**
 * Class ModelValidationException
 * @package app\exceptions
 * @author Andrey Sedyshev <a.sedyshev@s-cabinet.ru>
 */
class ModelValidationException extends \Exception
{
    protected $model;

    /**
     * ModelValidationException constructor.
     * @param Model $model
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(Model $model, string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->model = $model;
    }

    public function getModelErrors()
    {
        return $this->model->getErrors();
    }
}