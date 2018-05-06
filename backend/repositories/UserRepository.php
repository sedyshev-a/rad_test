<?php
/**
 * Created by PhpStorm.
 * Date: 06.05.18
 * Time: 14:54
 */

namespace app\repositories;

use app\models\User;

/**
 * Class UserRepository
 * @author Andrey Sedyshev <a.sedyshev@s-cabinet.ru>
 */
class UserRepository
{
    public function findOneById($id): User
    {
        return User::findOne($id);
    }
}