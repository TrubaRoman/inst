<?php
/**
 * @var $this Yii\web\View
 * @var $model frontend\modules\post\models\forms\PostForm
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<div class="container">
    <h1>Created post</h1>
    <div class="row">
        <div class="col-lg-6">

    <?php $form = ActiveForm::begin(); ?>

    <?=$form->field($model,'picture')->fileInput();?>

    <?=$form->field($model,'description');?>

    <?=Html::submitButton('Create');?>

    <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

