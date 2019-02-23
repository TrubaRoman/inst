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
use frontend\modules\user\models\form\PictureForm;
use Yii;
use yii\web\Response;
use yii\web\UploadedFile;


class ProfileController extends Controller
{
    /**
     * @param $nickname
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($nickname){

        $currentUser = Yii::$app->user->identity;
        $modelPicture = new PictureForm();

        return $this->render('view',[
            'user' => $this->findUser($nickname),
            'currentUser'=> $currentUser,
            'modelPicture' => $modelPicture]);
    }

    /**
     * handle profile image upload via ajax request
     */

    public function actionUploadPicture(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new PictureForm();

        $model->picture = UploadedFile::getInstance($model,'picture');

        if($model->validate()){
            $user = Yii::$app->user->identity;
            $user->picture = Yii::$app->storage->saveUploadedFile($model->picture);
            if($user->save(false,['picture'])){
                return [
                    'success' => true,
                    'pictureUri' => Yii::$app->storage->getFile($user->picture)
                ];

            }

        }
        return ['success' =>false, 'errors' => $model->getErrors()];
    }

    public function actionDeletePicture(){
        if (Yii::$app->user->isGuest){
            return $this->redirect(['/user/default/login']);
        }
        
        $currentUser = Yii::$app->user->identity;

        if($currentUser->deletePicture()){
            Yii::$app->session->setFlash('success','picture deleted');
        }
        else {
            Yii::$app->session->setFlash('danger','picture no deleted');
        }
        return $this->redirect(['/user/profile/view', 'nickname' => $currentUser->getNickname()]);

    }

    /**
     * підписка текущого user на вибраного user
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSubcribe($id){
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/user/default/login']);
        }

        $currentUser = Yii::$app->user->identity;
        $user = $this->findUserById($id);
        $currentUser->followUser($user);
        return $this->redirect(['/user/profile/view','nickname' => $user->getNickname()]);
    }

    /**
     * Відписка текущого user від вибраного user
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     *
     */

    public function actionUnsubcribe($id){
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/user/default/login']);
        }

        $currentUser = Yii::$app->user->identity;
        $user = $this->findUserById($id);
        $currentUser->unfollowUser($user);
        return $this->redirect(['/user/profile/view','nickname' => $user->getNickname()]);
    }

    /**
     * @param $id
     * @return User|null
     * @throws NotFoundHttpException
     */
    private function findUserById($id){
        if($user = User::findOne($id)){
            return $user;
        }
           throw new NotFoundHttpException();
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
//        for($i = 0;$i< 100;$i++){
//            $user = new User([
//                'username' => $faker->name,
//                'email' => $faker->email,
//                'about' => $faker->text(200),
//                'nickname' =>$faker->regexify('[A-Za-z0-9_]{5,15}'),
//                'auth_key' => Yii::$app->security->generateRandomString(),
//                'password_hash' => Yii::$app->security->generateRandomString(),
//                'created_at' => $time = time(),
//                'updated_at' => $time
//            ]);
//            $user->save(false);
//        }
//    }


}