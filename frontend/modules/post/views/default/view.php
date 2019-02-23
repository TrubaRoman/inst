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
    </div>
</div>
