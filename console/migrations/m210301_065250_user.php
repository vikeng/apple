<?php

use yii\db\Migration;
use common\models\User;

/**
 * Class m210301_065250_user
 */
class m210301_065250_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $user = new User();
        $user->username = 'user';
        $user->email = 'user@mail.ru';
        $user->setPassword('12345678');
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->status=User::STATUS_ACTIVE;
        $user->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        User::deleteAll();
    }
}
