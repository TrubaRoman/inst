<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $user_id
 * @property string $filename
 * @property string $description
 * @property int $created_ad
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }



    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'filename' => 'Filename',
            'description' => 'Description',
            'created_ad' => 'Created Ad',
        ];
    }

    public function getImage(){
        return Yii::$app->storage->getFile($this->filename);
    }

    public function getUser(){
        return $this->hasOne(User::className(),['id'=> 'user_id']);
    }

    public function like(User $user){

        $redis = Yii::$app->redis;

        $redis->sadd("post:{$this->getId()}:likes",$user->getId());
        $redis->sadd("user:{$user->getId()}:likes",$this->getId());
    }

    public function unlike(User $user){
        $redis = Yii::$app->redis;
        $redis->srem("post:{$this->getId()}:likes",$user->getId());
        $redis->srem("post:{$user->getId()}:likes",$this->getId());
    }


    public function countLikes(){
        $redis = Yii::$app->redis;
       return  $redis->scard("post:{$this->getId()}:likes");
    }

    public function isLikedBy(User $user)
    {
        $redis = Yii::$app->redis;
        return $redis->sismember("post:{$this->getId()}:likes",$user->getId());
    }


    public function getId(){
        return $this->id;
    }

}
