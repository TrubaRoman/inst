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
<img src="<?php echo $user->getPicture();?>" alt="">
<?= FileUpload::widget([
    'model' => $modelPicture,
    'attribute' => 'picture',
    'url' => ['/user/profile/upload-picture'], // your url, this is just for demo purposes,
    'options' => ['accept' => 'image/*'],
    'clientOptions' => [
        'maxFileSize' => 2000000
    ],
    // Also, you can specify jQuery-File-Upload events
    // see: https://github.com/blueimp/jQuery-File-Upload/wiki/Options#processing-callback-options
    'clientEvents' => [
        'fileuploaddone' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
        'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
    ],
]); ?>

<?php if ($currntUser['id'] !== $user->getId()):?>
<a href="<?=Url::to(['/user/profile/subcribe','id' => $user->getId()])?>" class="btn btn-info btn-sm">Subscribe</a>
<a href="<?=Url::to(['/user/profile/unsubcribe','id' => $user->getId()])?>" class="btn btn-info btn-sm">Unsubscribe</a>
<hr>
<h4>Frends, who are also following: <?=Html::encode($user->username);?></h4>
<div class="row">
    <?php foreach ($currntUser->getMutualSubscriptionsTo($user) as $item ):?>
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