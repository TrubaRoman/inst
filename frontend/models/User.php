<?php
namespace frontend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $type
 * @property text $about
 * @property string $picture
 * @property integer $nickname
 * @property string $password write-only password
 *
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const DEFAULT_IMAGE = '/img/no_image.jpg';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUserEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @return int|mixed|string
     */

    public function getNickname(){
       return ($this->nickname)?$this->nickname:$this->getId();
    }

    /**
     * Підписатися на Usera
     * Subscribe current user to given user
     * @param User $user
     */

    public function followUser(User $user){
        /**
         * @var redis connection
         */
        $redis = Yii::$app->redis;
        $redis->sadd("user:{$this->getId()}:subscriptions",$user->getId());
        $redis->sadd("user:{$user->getId()}:followers",$this->getId());

    }

    /**
     * Відписатися від usera
     * Unsubscribe current user from given user
     * @param User $user*

     */
    public function unfollowUser(User $user){
        /**
         * @var redis connection
         */
        $redis = Yii::$app->redis;
        $redis->srem("user:{$this->getId()}:subscriptions",$user->getId());
        $redis->srem("user:{$user->getId()}:followers",$this->getId());
    }

    /**
     * Повертає список підсписок
     * @return mixed
     */
    public function getSubscriptions(){
        /**
         * @var redis connection
         */
        $redis = Yii::$app->redis;
        $key = "user:{$this->getId()}:subscriptions";
        $ids = $redis->smembers($key);
        return User::find()
            ->select('id,username,nickname')
            ->where(['id' => $ids])
            ->orderBy('username')
            ->asArray()
            ->all();
    }

    /**
     * Повертає список підписників
     * @return mixed
     *
     */

    public function getFollowers(){
        /**
         * @var redis connection
         */
        $redis = Yii::$app->redis;
        $key = "user:{$this->getId()}:followers";
        $ids = $redis->smembers($key);
        return User::find()
            ->select('id,username,nickname')
            ->where(['id' => $ids])
            ->orderBy('username')
            ->asArray()
            ->all();

    }

    /**
     * @return mixed
     * get count subscriptions
     */
    public function countSubscriptions(){
        /**
         * @var redis connection
         */
        $redis = Yii::$app->redis;
        return $redis->scard("user:{$this->getId()}:subscriptions");
    }

    /**
     * @return mixed
     * get count Followers
     */
    public function countFollowers(){
        /**
         * @var redis connection
         */
        $redis = Yii::$app->redis;
        return $redis->scard("user:{$this->getId()}:followers");
    }

    /**
     * get identical value
     * @param User $user
     * @return array|ActiveRecord[]
     */
    public function getMutualSubscriptionsTo(User $user){

        //current user subcriptions (список моїх підписок )
        $key1 = "user:{$this->getId()}:subscriptions";
        //given user follower (список підписників даного друга)
        $key2 = "user:{$user->getId()}:followers";
        /*@var redis Connection*/
        $redis = Yii::$app->redis;
        //get identical value (знаходимо ідентичні підписки )
        $ids = $redis->sinter($key1,$key2);
        return User::find()->select('id,username,nickname')
            ->where(['id' => $ids])
            ->orderBy('username')
            ->asArray()
            ->all();
    }

    public function getPicture(){
        if($this->picture) {
            return Yii::$app->storage->getFile($this->picture);
        }
        return self::DEFAULT_IMAGE;
    }

    public function deletePicture(){
        if($this->picture && Yii::$app->storage->deleteFile($this->picture)){
            $this->picture = null;
            return $this->save(false,['picture']);
        }
        return false;
    }


}
