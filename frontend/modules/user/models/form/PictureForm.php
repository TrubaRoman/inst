<?php
/**
 * Created by PhpStorm.
 * User: r_truba
 * Date: 21.02.2019
 * Time: 10:44
 */

namespace frontend\modules\user\models\form;
use yii\base\Model;
use Intervention\Image\ImageManager;
use Yii;

class PictureForm extends Model
{
    public $picture;

    public function rules()
    {
        return [
            [['picture'],'file',
               'extensions'=> ['jpg'],
               'checkExtensionByMimeType' => true
            ],
        ];
    }

    public function __construct()
    {
        $this->on(self::EVENT_AFTER_VALIDATE,[$this,'resizePicture']);
    }

    public function resizePicture()
    {
        $width = Yii::$app->params['profilePicture']['maxWidth'];
        $height = Yii::$app->params['profilePicture']['maxHeight'];
        $manager = new ImageManager(array('driver'=>'imagick'));
        $image = $manager->make($this->picture->tempName);
        $image->resize($width,$height,function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save();

    }


}