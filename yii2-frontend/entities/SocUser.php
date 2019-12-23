<?php

namespace app\core\entities;


use app\core\services\MailService;
use Yii;
use yii\authclient\BaseOAuth;
use yii\helpers\ArrayHelper;

class SocUser extends User
{
    public function updateIfEmpty(BaseOAuth $client)
    {
        $identity = $client->getId() . '_id';
        $attr = $client->getUserAttributes();

        if ($attr['id']) {
            $this->{$identity} = $attr['id'];
        }
        if (empty($this->profile->first_name)) {
            $this->profile->first_name = ArrayHelper::getValue($attr, 'first_name');
        }
        if (empty($this->profile->last_name)) {
            $this->profile->last_name = ArrayHelper::getValue($attr, 'last_name');
        }
        if (empty($this->photo)) {
            $this->downloadPhoto($client);
        }

        return $this->profile->save(false) && $this->save(false);
    }

    public function downloadPhoto(BaseOAuth $client)
    {
        $attr = $client->getUserAttributes();
        $photoUrl = ArrayHelper::getValue($attr, 'photo');   // vk
        if (!empty($attr['picture']['data']['url'])) {            // facebook
            $photoUrl = $attr['picture']['data']['url'];
        } elseif(!empty($attr['profile_image_url'])) {            // twitter
            $photoUrl = $attr['profile_image_url'];
        }

        if ($photoUrl) {
            $this->photo = '/images/users/' . $this->id . '-photo';
            $path = Yii::getAlias('@webroot' . $this->photo);
            // save file
            file_put_contents($path, fopen($photoUrl, 'r'));
            // get type and extension
            list($type, $extension) = explode('/', mime_content_type($path));
            // add extension into path and name
            $this->photo .= '.' . $extension;
            rename($path , $path . '.' . $extension);
            return $this->save(false);
        }

        return false;
    }

    public static function findByAuth(BaseOAuth $client)
    {
        $identity = $client->getId() . '_id';
        $attr = $client->getUserAttributes();
        return self::findOne([$identity => ArrayHelper::getValue($attr, 'id')]);
    }

    public static function create(BaseOAuth $client)
    {
        $identity = $client->getId() . '_id';
        $attr = $client->getUserAttributes();
        $password = Yii::$app->security->generateRandomString(8);

        if (!empty($attr['email']) && self::find()->where(['email' => $attr['email']])->exists()) {
            throw new \DomainException(Yii::t('app', 'A user with such an email already exists. To get started, please log in using e-mail.'));
        }

        $user = new self([
            'email' => ArrayHelper::getValue($attr, 'email'),
            'role' => User::ROLE_INVESTOR,
            'password_hash' => Yii::$app->getSecurity()->generatePasswordHash($password),
        ]);
        $user->{$identity} = ArrayHelper::getValue($attr, 'id');

        if (!$user->save()) {
            Yii::error($user->getErrors(), 'soc-registration');

            if ($user->getErrors()) {
                throw new \DomainException($user->getErrorSummary(true));
            }
            throw new \DomainException('User not created');
        }

        $profile = new UserProfile(['user_id' => $user->id]);
        if (!$profile->save()) {
            Yii::error($profile->getErrors(), 'soc-registration');
            $user->delete();

            if ($profile->getErrors()) {
                throw new \DomainException($profile->getErrorSummary(true));
            }
            throw new \DomainException('User not created');
        }

        $user->refresh();
        $user->updateIfEmpty($client);
        Yii::createObject(MailService::class)->sendRegistrationMessage($user, $password);
        return $user;
    }
}
