<?php

namespace frontend\modules\post\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use frontend\models\Post;
use frontend\modules\post\models\forms\PostForm;
use yii\web\Response;
use Yii;

/**
 * Default controller for the `post` module
 */
class DefaultController extends Controller
{

    public function actionCreate()
    {
        $model = new PostForm(Yii::$app->user->identity);
        if($model->load(Yii::$app->request->post())){
            $model->picture = UploadedFile::getInstance($model,'picture');
            if($model->save()){
                Yii::$app->session->setFlash('success','Post created');
                return $this->goHome();
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }


    public function actionView($id){

        $currentUser = Yii::$app->user->identity;
        return $this->render('view',[
          'post' =>$this->findPost($id),
            'currentUser' => $currentUser
        ]);
    }
    
    public function actionLike(){
        if(Yii::$app->user->isGuest){
            return $this->redirect('/user/default/login');
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $post = $this->findPost($id);

        $currentUser = Yii::$app->user->identity;

        $post->like($currentUser);
        return [
            'success' => true,
            'likesCount' => $post->countLikes()
        ];
    }

    public function actionUnlike(){

        if(Yii::$app->user->isGuest){
            return $this->redirect('/user/default/login');
        }
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $post = $this->findPost($id);

        $currntUser = Yii::$app->user->identity;

        $post->unlike($currntUser);
        return [
            'success' => true,
            'likesCount' => $post->countLikes()
        ];
    }


    private function findPost($id){
        if($user = Post::findOne($id)){
            return $user;
        }
        throw new NotFoundHttpException();
    }


}
