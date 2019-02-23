<?php
/**
 * Created by PhpStorm.
 * User: r_truba
 * Date: 23.02.2019
 * Time: 8:00
 */
namespace frontend\modules\post\models\forms;

use Yii;
use yii\base\Model;
use frontend\models\Post;
use frontend\models\User;
class PostForm extends Model
{
    const MAX_DESCRIPTION_LENGTH = 1000;

    public $picture;
    public $description;
    private $user;

    public function rules()
    {
        return [
            [['picture'],'file',
                'skipOnEmpty' => false,
                'extensions' => ['jpg','png'],
                'checkExtensionByMimeType'=>true,
                'maxSize' => $this->getMaxFileSize()
                ],
            [['description'],'string','max'=> self::MAX_DESCRIPTION_LENGTH]
        ];
    }

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function save()
    {
        if($this->validate()){

            $post = new Post();
            $post->description = $this->description;
            $post->created_ad = time();
            $post->filename = Yii::$app->storage->saveUploadedFile($this->picture);
            $post->user_id = $this->user->getId();
            return  $post->save(false);
        }
    }


    private function getMaxFileSize(){
        return Yii::$app->params['maxFileSize'];
    }

}