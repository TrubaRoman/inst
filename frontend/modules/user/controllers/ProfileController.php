<?php
/**
 * Created by PhpStorm.
 * User: r_truba
 * Date: 16.02.2019
 * Time: 14:44
 */

namespace frontend\modules\user\controllers;
use Faker\Factory;
use Faker\Provider\Address;
use frontend\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;


class ProfileController extends Controller
{
    /**
     * @param $nickname
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($nickname){
        return $this->render('view',['user' => $this->findUser($nickname)]);
    }

    /**
     * @param $nickname
     * @return array|\yii\db\ActiveRecord|null
     * @throws NotFoundHttpException
     */

    private function findUser($nickname){
        if($user = User::find()->where(['nickname' => $nickname])->orWhere(['id' => $nickname])->one()){
            return $user;
        }
        throw new NotFoundHttpException();
    }

//    public function actionGenerate(){
//        $faker = Factory::create();
//        for($i = 0;$i< 1000;$i++){
//            $user = new User([
//                'username' => $faker->name,
//                'email' => $faker->email,
//                'about' => $faker->text(200),
//                'nickname' =>$faker->regexify('[A-Za-z0-9_](5,15)'),
//                'auth_key' => Yii::$app->security->generateRandomString(),
//                'password_hash' => Yii::$app->security->generateRandomString(),
//                'created_at' => $time = time(),
//                'updated_at' => $time
//            ]);
//            $user->save(false);
//        }
//    }


}