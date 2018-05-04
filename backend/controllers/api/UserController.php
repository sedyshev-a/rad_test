<?php
namespace app\controllers\api;
/**
 * Created by PhpStorm.
 * Date: 04.05.18
 * Time: 12:04
 */

use app\models\User;
use Yii;
use yii\web\ServerErrorHttpException;

/**
 * Class UserController
 * @author Andrey Sedyshev <a.sedyshev@s-cabinet.ru>
 */
class UserController extends BaseApiController
{
    public function actionList()
    {
        return \app\models\User::find()->all();
    }

    public function add()
    {
        $user = new User();
        $user->load(Yii::$app->getRequest()->getBodyParams(), '');
        if (!$user->validate()) {
            return $this->sendErrorResponse($user);
        }
        if ($user->save(false)) {
            return $user;
        } else  {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
    }
}