<?php
/**
 * Created by PhpStorm.
 * User: r_truba
 * Date: 25.02.2019
 * Time: 14:45
 */
namespace frontend\models\events;
use yii\base\Event;
use frontend\models\User;
use frontend\models\Post;

class PostCreatedEvent extends  Event
{
    /**
     * @var User;
     */
    public $user;

    /**
     * @var Post
     */

    public $post;


    public function getUser():User
    {
        return $this->user;
    }

    public function getPost():Post
    {
        return $this->post;
    }
}