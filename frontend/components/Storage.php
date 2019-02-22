<?php
/**
 * Created by PhpStorm.
 * User: r_truba
 * Date: 21.02.2019
 * Time: 12:49
 */

namespace frontend\components;
use Yii;
use yii\base\Component;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;


class Storage extends Component implements StorageInterface
{
    private $filename;

    /**
     * save given Uploaded file instance to disk
     *
     * @param UploadedFile $file
     * @return string|null
     */
    public function saveUploadedFile(UploadedFile $file)
    {
        $path = $this->preparePath($file);

        if($path && $file->saveAs($path)){
            return $this->filename;
        }
    }

    /**
     * prepare path to save uploaded file
     * @param UploadedFile $file
     * @return string|null
     * @throws \yii\base\Exception
     */
    protected function preparePath(UploadedFile $file)
    {
            $this->filename = $this->getFilename($file);
            //c1/6a/d3a1aca45b1bece04577acd2aebce315fd31.jpg
            $path = $this->getStoragePath().$this->filename;
            //var/www/project/frontend/web/uploads/c1/6a/d3a1aca45b1bece04577acd2aebce315fd31.jpg
            $path = FileHelper::normalizePath($path);
            if (FileHelper::createDirectory(dirname($path)));
            return $path;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    protected function getFilename(UploadedFile $file)
    {
        //$file->tmpname  -  /tmp/qio93k1
        $hash = sha1_file($file->tempName);//c16ad3a1aca45b1bece04577acd2aebce315fd31
        $name = substr_replace($hash,'/',2,0);
        $name = substr_replace($name,'/',5,0);
        //c1/6a/d3a1aca45b1bece04577acd2aebce315fd31
        return $name .'.'. $file->extension;//c1/6a/d3a1aca45b1bece04577acd2aebce315fd31.jpg
    }

    /**
     * @return bool|string
     */
    protected function getStoragePath()
    {
        return Yii::getAlias(Yii::$app->params['storagePath']);
    }

    /**
     * @param string $filename
     * @return bool
     */

   public function deleteFile(string $filename){
        $file = $this->getStoragePath().$filename;
        if(file_exists($file)){
           return unlink($file);
        }
         return true;
   }



    /**
     * @param string $filename
     * @return string
     */

    public function getFile(string $filename)
    {
        return Yii::getAlias(Yii::$app->params['storageUri']).$filename;
    }

}