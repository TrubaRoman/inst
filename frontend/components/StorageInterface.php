<?php
/**
 * Created by PhpStorm.
 * User: r_truba
 * Date: 21.02.2019
 * Time: 12:38
 */
namespace frontend\components;
use yii\web\UploadedFile;
interface StorageInterface
{

    public function saveUploadedFile(UploadedFile $file);

    public function getFile(string $filename);
}