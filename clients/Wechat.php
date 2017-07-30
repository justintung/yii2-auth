<?php

namespace yiichina\auth\clients;

use yii\authclient\OAuth2;
use yiichina\auth\AuthChoiceAsset;
use Yii;

class Wechat extends OAuth2
{
    use AuthTrait;

    public $authUrl = 'https://api.weibo.com/oauth2/authorize';

    public $tokenUrl = 'https://api.weibo.com/oauth2/access_token';

    public $apiBaseUrl = 'https://api.weibo.com/2';

    public function init()
    {
        $this->registerAsset();
        $this->registerTranslations();
    }

    protected function initUserAttributes()
    {
        $user = $this->api('users/show.json', 'GET', ['uid' => $this->user->uid]);

        return [
        	'client' => 'weibo',
        	'openid' => $user['id'],
        	'nickname' => $user['name'],
        	'gender' => $user['gender'],
        	'location' => $user['location'],
        ];
    }


    /**
     * @inheritdoc
     */
    protected function getUser()
    {
        $str = file_get_contents('https://api.weibo.com/2/account/get_uid.json?access_token=' . $this->accessToken->token);
        return json_decode($str);
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'wechat';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return Yii::t('yiichina/auth', 'Wechat');
    }
}
