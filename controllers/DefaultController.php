<?php

namespace mkiselev\broadcasting\controllers;

use mkiselev\broadcasting\Module;
use Yii;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $channelName = Yii::$app->request->post('channel_name');

        return Module::getInstance()->getBroadcasterInstance()->auth(Yii::$app->user, $channelName);
    }

}
