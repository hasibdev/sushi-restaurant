<?php

namespace Jara\App\Models;

use Jara\App\Configs\Config;
use Jara\Core\Database\Connect;
use Jara\Core\Helpers\Images;
use Jara\Core\Helpers\Response;
use Jara\Core\Helpers\Setting;
use Jara\Core\Helpers\Videos;

class HomeModel
{
    public $db;

    function __construct()
    {
        $this->db = new Connect();
        $this->db = $this->db->connect();
        $this->api = Config::$app_url;
    }

    public function set_home()
    {

        if (isset($_FILES['json'])) {
            // new payload
            $body = json_decode(file_get_contents($_FILES['json']['tmp_name']));
            // old settings
            $old_body = json_decode(Setting::get('page_home')->value, '');


            // upload video
            if (isset($_FILES['video']) && $_FILES['video']['tmp_name'] != '') {
                $new_vid = file_get_contents($_FILES['video']['tmp_name']);

                if ($new_vid != null) {


                    if (isset($old_body->sections) && $old_body->sections[0]->props->media->source->url != '') {
                        Videos::remove($old_body->sections[0]->props->media->source->url);
                    }

                    $video = Videos::add($_FILES['video']);

                    $body->sections[0]->props->media->source->url = $video;
                } else {
                    $body->sections[0]->props->media->source->url = $old_body->sections[0]->props->media->source->url;
                }
            } else {
                $body->sections[0]->props->media->source->url = $old_body->sections[0]->props->media->source->url;
            }

            // review main image
            $review_main = $body->sections[1]->props->image->data;
            $old_review_main = $old_body->sections[1]->props->image->url;

            $old_review_main = str_replace($this->api . '/uploads/images/', '', $old_review_main);

            if (strlen($review_main) > 5) {
                if ($old_review_main) {
                    Images::remove($old_review_main);
                }
                $body->sections[1]->props->image->url = Images::add($review_main, 900, 800, 'webp');
                unset($body->sections[1]->props->image->data);
            } else {
                $body->sections[1]->props->image->url = $old_review_main;
            }


            $reviews = $body->sections[1]->props->content->reviews;

            foreach ($reviews as $review) {
                $review->author->thumbnail->url = str_replace($this->api . '/uploads/images/', '', $review->author->thumbnail->url);
                if (strlen($review->author->thumbnail->data) > 5) {
                    if ($review->author->thumbnail->url != '') {
                        Images::remove($review->author->thumbnail->url);
                    }
                    $review->author->thumbnail->url = Images::add($review->author->thumbnail->data, 100, 100, 'webp');
                    unset($review->author->thumbnail->data);
                }
            }


            if (isset($body->sections[6]->props->image->data) && strlen($body->sections[6]->props->image->data) > 5) {
                if (isset($old_body->sections) && $old_body->sections[6]->props->image->url != '') {
                    Images::remove($old_body->sections[6]->props->image->url);
                }
                $body->sections[6]->props->image->url = Images::add($body->sections[6]->props->image->data, 255, 1280, 'webp');
                unset($body->sections[6]->props->image->data);
            } else {
                $body->sections[6]->props->image->url =  str_replace($this->api . '/uploads/images/', '', $old_body->sections[6]->props->image->url);
            }



            echo json_encode($body);

            if ($old_body != '') {
                $result = Setting::update('page_home', json_encode($body));
            } else {
                $result = Setting::set('page_home', json_encode($body));
            }

            return Response::new(200, $result);
        }
    }

    public function get_home()
    {
        $result = Setting::get('page_home', '');

        $result = json_decode($result->value);

        // $items = json_decode('
        // {
        //     "name": "Menu",
        //     "props": {
        //         "title": "Our Menu",
        //         "categories": [64, 65]
        //     }
        // }
        // ');


        if (isset($result->sections)) {

            $result->sections[0]->props->media->source->url = $this->api . '/uploads/videos/' . $result->sections[0]->props->media->source->url;
            $result->sections[6]->props->image->url = $this->api . '/uploads/images/' . $result->sections[6]->props->image->url;

            $result->sections[1]->props->image->url = $this->api . '/uploads/images/' . $result->sections[1]->props->image->url;

            foreach ($result->sections[1]->props->content->reviews as $review) {
                $review->author->thumbnail->url = $this->api . '/uploads/images/' . $review->author->thumbnail->url;
            }
        }
        return json_encode($result);
    }
}
