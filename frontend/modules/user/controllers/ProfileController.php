<?php
/**
 * Created by PhpStorm.
 * User: r_truba
 * Date: 16.02.2019
 * Time: 14:44
 */

namespace frontend\modules\user\controllers;
use frontend\models\User;
use yii\web\Controller;


class ProfileController extends Controller
{
    public function actionView($id){

        $user = User::find($id)->one();
        return $this->render('view',['user' => $user]);
    }


}