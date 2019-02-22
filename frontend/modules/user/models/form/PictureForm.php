<?php
/**
 * Created by PhpStorm.
 * User: r_truba
 * Date: 21.02.2019
 * Time: 10:44
 */

namespace frontend\modules\user\models\form;
use yii\base\Model;
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


}