<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>


    </div>

    <div class="body-content">

     <?php if(is_array($users)):?>

     <?php foreach ($users as $user):?>

         <p><a href="<?= Url::to(['/user/profile/view','nickname' => $user->getNickname()]);?>"><?=$user->username; ?></p>
        <?php endforeach;?>
        <?php endif;?>

    </div>
</div>
