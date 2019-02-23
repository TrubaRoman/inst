<?php

/**
 * @var  $this yii\web\View
 * @var  $post frontend\models\Post;
 */
use yii\helpers\Html;
?>

<div class="post-default-index">

    <?php if($post->getUser()):?>
    <h1><?=$post->user->username;?></h1>
    <?php endif;?>
    <div class="row">
        <div class="col-lg-6">
           <?=Html::img($post->getImage());?>
        </div>
        <div class="col-lg-6">
            <?=Html::encode($post->description);?>
        </div>
        <div class="col-lg-12">
            <br>
             Like: <span class="likes-count"> <?=$post->countLikes();?></span>
            <a href="" class="btn btn-primary button-like <?=($currentUser && $post->isLikedBy($currentUser))?"display-none":"";?>" data-id = "<?=$post->id;?>">
                Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
            </a>
            <a href="" class="btn btn-primary button-unlike <?=($currentUser && !$post->isLikedBy($currentUser))?"display-none":"";?>" data-id = "<?=$post->id;?>">
                Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
            </a>
        </div>

    </div>
</div>
<?php $this->registerJsFile('@web/js/like.js', [
    'depends' => \yii\web\JqueryAsset::className()
]); ?>