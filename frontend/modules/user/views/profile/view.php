<?php
/**
 * @var $this \yii\web\View
 * @var $user \frontend\models\User
 * @var $currentUser \frontend\models\User
 * @var $modelPicture \frontend\modules\user\models\form\PictureForm
 *
 */
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;

?>


<h1><?=Html::encode($user->username)?></h1>
<p><?=HtmlPurifier::process($user->about);?></p>
<hr>
<div  class="row">
    <div class="col-lg-3">
    <img src="<?php echo $user->getPicture();?>" alt="" id="profile-picture">
    </div>
</div>

<div class="alert alert-success display-none" id="profile-image-success">Profile image updated</div>
<div class="alert alert-danger display-none" id="profile-image-fail"></div>
<?php if($currentUser->equals($user)):?>
<?=
FileUpload::widget([
    'model' => $modelPicture,
    'attribute' => 'picture',
    'url' => ['/user/profile/upload-picture'], // your url, this is just for demo purposes,
    'options' => ['accept' => 'image/*'],
    'clientEvents' => [
        'fileuploaddone' => 'function(e, data) {
                                            if (data.result.success) {
                                                $("#profile-image-success").show();
                                                $("#profile-image-fail").hide();
                                                $("#profile-picture").attr("src", data.result.pictureUri);
                                            } else {
                                                $("#profile-image-fail").html(data.result.errors.picture).show();
                                                $("#profile-image-success").hide();
                                            }
                                        }',
    ],
]);
?>
<?php else:?>

<a href="<?=Url::to(['/user/profile/subcribe','id' => $user->getId()])?>" class="btn btn-info btn-sm">Subscribe</a>
<a href="<?=Url::to(['/user/profile/unsubcribe','id' => $user->getId()])?>" class="btn btn-info btn-sm">Unsubscribe</a>
<hr>
<h4>Frends, who are also following: <?=Html::encode($user->username);?></h4>
<div class="row">
    <?php foreach ($currentUser->getMutualSubscriptionsTo($user) as $item ):?>
    <div class="col-md-12">
        <a href="<?=Url::to(['/user/profile/view','nickname' =>($item['nickname'])?$item['nickname']:$item['id']])?>">
        <h4> <?=Html::encode($item['username'])?></h4></a>
    </div>
    <?php endforeach;?>

</div><?php  endif;?>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">
   View subscriptions  <?=$user->countSubscriptions();?>
</button>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Subscriptions</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php foreach ($user->getSubscriptions() as $Subscription):?>
                    <div class="col-md-12">
                        <a href="<?php echo Url::to(['/user/profile/view','nickname' => ($Subscription['nickname'])?$Subscription['nickname']:$Subscription['id']])?>">
                            <span><?= Html::encode($Subscription['username'])?></span>
                        </a>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal2">
    View followers <?=$user->countFollowers();?>
</button>

<!-- Modal -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Followers</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php foreach ($user->getFollowers() as $Follower):?>
                        <div class="col-md-12">
                            <a href="<?php echo Url::to(['/user/profile/view','nickname' => ($Follower['nickname'])?$Follower['nickname']:$Follower['id']])?>">
                                <span><?= Html::encode($Follower['username'])?></span>
                            </a>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>