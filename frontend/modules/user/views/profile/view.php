<?php
/**
 *
 */
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;


?>

<h1><?=Html::encode($user->username)?></h1>
<p><?=HtmlPurifier::process($user->about);?></p>
<hr>
