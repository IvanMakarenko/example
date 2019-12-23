<?php

namespace api\models\forms;

use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends \common\models\LoginForm
{
    /**
     * @return Token|null
     */
    public function auth()
    {
        if ($this->validate()) {
            return $this->getUser()->auth_key;
        } else {
            return null;
        }
    }
}
