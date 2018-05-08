<?php
/**
 * Created by PhpStorm.
 * Date: 08.05.18
 * Time: 16:14
 */

namespace app\repositories;

use app\models\BookIssueLog;
use app\models\User;

/**
 * Class BookIssueLogRepository
 * @package app\repositories
 * @author Andrey Sedyshev <a.sedyshev@s-cabinet.ru>
 */
class BookIssueLogRepository
{
    const RECENT_COUNT = 10;

    public function findRecentByUserId($userId)
    {
        return BookIssueLog::find()
            ->where(['user_id' => $userId])
            ->limit(self::RECENT_COUNT)
            ->orderBy(['date' => SORT_DESC])
            ->all();
    }
}