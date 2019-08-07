<?php

use App\Models\Publication;
use App\Models\Store\UserStore;
use App\Models\Feed;
use App\Models\User;
use App\Models\Business\UserBusinessProduct;
use App\Models\Store\UserVentureProduct;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CorrentIpUrlOnUploads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        $old_url = 'https://server.startev.africa';
//        $old_url = 'https://server.startev.africa';
//        $old_url = 'http://startev.server';
        $old_url = 'http://37.139.29.190:8086';

        $feeds = Feed::whereNotNull('image')->get(['id','image']);

        foreach ($feeds as $feed) {
            if (strpos($feed->image, $old_url) !== false) {
                $update = [
                    'image' => str_replace($old_url, "", $feed->image)
                ];

                Feed::find($feed->id)->update($update);
            }
        }

        $feedImages = Feed::whereNotNull('images')->get(['id','images']);

        foreach ($feedImages as $feedImage) {
            $id = $feedImage->id;
            $images = [];

            foreach ($feedImage->images as $image) {
                if (strpos($image, $old_url) !== false) {
                    $images[] = str_replace($old_url, "", $image);
                }
            }

            if (count($images) > 0) {
                Feed::find($id)->update(['images' => null]);
                Feed::find($id)->update(['images' => $images]);
            }
        }


        $users = User::whereNotNull('avatar')->get(['id','avatar','bg_image']);

        foreach ($users as $user) {
            if (strpos($user->avatar, $old_url) !== false) {
                $update = [
                    'avatar' => str_replace($old_url, "", $user->avatar),
                ];

                User::find($user->id)->update($update);
            }

            if (strpos($user->bg_image, $old_url) !== false) {
                $update = [
                    'bg_image' => str_replace($old_url, "", $user->bg_image)
                ];

                User::find($user->id)->update($update);
            }
        }


        $businessProducts = UserBusinessProduct::whereNotNull('images')->get(['id','images']);

        foreach ($businessProducts as $product) {

            $id = $product->id;
            $newImages = [];

            foreach ($product->images as $image) {
                if (strpos($image, $old_url) !== false) {
                    $newImages[] = str_replace($old_url, "", $image);
                }
            }

            //update images
            if (count($newImages) > 0) {
                UserBusinessProduct::find($id)->update(['images' => null]);
                UserBusinessProduct::find($id)->update(['images' => $newImages]);
            }
        }


        $userProducts = UserVentureProduct::whereNotNull('images')->get(['id','images']);

        foreach ($userProducts as $product) {

            $id = $product->id;
            $newImages = [];

            foreach ($product->images as $image) {
                if (strpos($image, $old_url) !== false) {
                    $newImages[] = str_replace($old_url, "", $image);
                }
            }

            //update images
            if (count($newImages) > 0) {
                UserVentureProduct::find($id)->update(['images' => null]);
                UserVentureProduct::find($id)->update(['images' => $newImages]);
            }
        }


        $userStores = UserStore::whereNotNull('store_logo')->get(['id','store_logo']);

        foreach ($userStores as $store) {
            if (strpos($store->store_logo, $old_url) !== false) {
                $update = [
                    'store_logo' => str_replace($old_url, "", $store->store_logo)
                ];

                UserStore::find($store->id)->update($update);
            }
        }





        $publicationFiles = Publication::whereNotNull('files')->get(['id','files']);

        foreach ($publicationFiles as $publicationFile) {
            $id = $publicationFile->id;
            $files = [];

            foreach ($publicationFile->files as $file) {
                if (strpos($file, $old_url) !== false) {
                    $files[] = str_replace($old_url, "", $file);
                }
            }


            if (count($files) > 0) {
                Publication::find($id)->update(['files' => null]);
                Publication::find($id)->update(['files' => $files]);
            }
        }


        $publicationImages = Publication::whereNotNull('images')->get(['id','images']);

        foreach ($publicationImages as $publicationImage) {
            $id = $publicationImage->id;
            $images = [];

            foreach ($publicationImage->images as $image) {
                if (strpos($image, $old_url) !== false) {
                    $images[] = str_replace($old_url, "", $image);
                }
            }


            if (count($images) > 0) {
                Publication::find($id)->update(['images' => null]);
                Publication::find($id)->update(['images' => $images]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
