<?php
namespace App\Traits;


use Image;

Trait ImageUpload{
    public function uploadImage($fileNname,$prefix,$path){

        if($fileNname){
            $image_tmp = $fileNname;
            if($image_tmp->isValid()){
                // Get extension
                $extension = $image_tmp->getClientOriginalExtension();
                //Generer une image
                $imageName = uniqid($prefix,true).''.'.'.$extension;
                $Path =$path.$imageName;

                //Upload new  image
                Image::make($image_tmp->getRealPath())->save($Path);

                // supprimer ancien photo

               /* if(Auth::user()->image != null && Auth::user()->image != 'default-user.png'  ){
                    $ancienPath = $path.Auth::user()->image;

                    if(file_exists($ancienPath) && Auth::user()->image != 'default-user.png' ){
                        unlink($ancienPath);
                        User::where('id',Auth::user()->id)->update(['image' => '']);
                    }
                }*/

            }
        }
        return  $imageName;

    }


}
