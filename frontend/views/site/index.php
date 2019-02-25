<?php

/* @var $this yii\web\View */

/**
 * @var $currentUser  \frontend\models\User
 * @var $feedItems  \frontend\models\Feed
 */
use yii\web\JqueryAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;
use Yii;

$this->title = 'My Yii Application';
?>
<div class="site-index">
<?php if($feedItems):?>
    <div class="row container">

        <?php foreach( $feedItems as $feedItem ):?>
            <div class="col-md-12">
            <?=Html::img($feedItem->author_picture,['width' => '30','height' => '30']);?>
                <span class="h4"><a href="<?=Url::to(['user/profile/view','nickname' => ($feedItem->author_nickname)?$feedItem->author_nickname:$feedItem->id])?>">
                    <?=Html::encode($feedItem->author_name);?>
                </a></span>
        </div>
            <br>
        <div class="col-md-12">
            <?= Html::img(Yii::$app->storage->getFile($feedItem->post_filename));?>
        </div>
            <br>
        <div class="col-md-12">
            <?= HtmlPurifier::process($feedItem->post_description);?>
        </div>

        <div class="col-md-12">
            <?= Yii::$app->formatter->asDatetime(Html::encode($feedItem->post_created_at));?>
        </div>
        <div class="col-md-12">Likes:<span class="likes-count"><?=$feedItem->countLikes();?></span>
            <a href="#" class=" btn btn-primary button-unlike <?=($currentUser->likesPost($feedItem->post_id))?'':'display-none';?>" data-id="<?=$feedItem->post_id;?>">
                Unlike: &nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
            </a>
            <a href="#" class=" btn btn-primary button-like <?=($currentUser->likesPost($feedItem->post_id))?'display-none':'';?>" data-id="<?=$feedItem->post_id;?>">
                Like: &nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
            </a>
        </div>
            <div class="col-md-12">
                <hr>
            </div>
        <?php endforeach;?>

    </div>
<?php else:?>
    <div class="col-md-12">
        No body posted yet!
    </div>
<?php endif;?>
</div>
<?php $this->registerJsFile('@web/js/like.js', [
    'depends' => \yii\web\JqueryAsset::className()
]); ?>