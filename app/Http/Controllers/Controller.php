<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Constants;
use Cache;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getLocations(){
        // dd(Cache::get('test'));
        // Cache::put('test', str_replace('\n\s\s', '', Cache::get('trending_response')));
        // echo (Cache::get('test'));die;
        // dd(Cache::get('trending_response'));
        // preg_match_all("|<img data-ytimg=(.*)\" >\n<span|U",
        // preg_match_all("|<img data-ytimg(.*)height=|U",
        preg_match_all("|<li class=\"expanded-shelf-content-item-wrapper\">(.*)lượt xem|misU",
        // preg_match_all("|\sdir=\"ltr\">(.*)</a><span|U",
        Cache::get('trending_response'),
        $out1, PREG_PATTERN_ORDER);
        dd($out1);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.youtube.com/picker_ajax?action_country_json=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $rawLocation = curl_exec($ch);
        curl_close($ch);
        $rawLocation1 = json_decode($rawLocation)->data;
        $locations = [];
        if (!empty($rawLocation1[1])) {
            foreach ($rawLocation1[1] as $v) {
                if ($v[1] && $v[2]) {
                    array_push($locations, [$v[1], $v[2]]);
                }
            }
        }
        if(empty($locations)){
            Cache::put('raw_location', $rawLocation);
            echo 'load location fail';die;
        }
        return $locations;
    }

    public function index()
    {
        $data = [
            'locations' => $this->getLocations(),
        ];
        return view('welcome', $data);
    }

    public function search()
    {
        if (empty($_POST['search_type'])) {
            $_POST['search_type'] = 0;
        }
        if (empty($_POST['location'])) {
            $_POST['location'] = "VI";
        }
        if (empty($_POST['language'])) {
            $recentlyTrendingText = 'Thịnh hành gần đây';
        }
        $baseUrl = Constants::URL_MAP[$_POST['search_type']];
        $locationUrlPart = '?gl=' . $_POST['location'];
        $url = $baseUrl . $locationUrlPart;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        curl_close($ch);
        $res_gr = explode($recentlyTrendingText, $res);
        $trending = $res_gr[0];
        $recent_trending = $res_gr[1];
        preg_match_all(
            "|{\"videoRenderer\":{\"videoId\":(.*),{\"thumbnailOverlayNowPlayingRenderer\":|U",
            $trending,
            $out1,
            PREG_PATTERN_ORDER
        );
        preg_match_all(
            "|{\"videoRenderer\":{\"videoId\":(.*),{\"thumbnailOverlayNowPlayingRenderer\":|U",
            $recent_trending,
            $out2,
            PREG_PATTERN_ORDER
        );
        $group1 = [];
        $group2 = [];
        foreach ($out1[1] as $i) {
            preg_match_all(
                "|\"url\":\"https://i.ytimg.com(.*)\",\"width\":|U",
                $i,
                $image,
                PREG_PATTERN_ORDER
            );

            preg_match_all(
                "|\"title\":{\"runs\":\[{\"text\":\"(.*)\"}\],\"accessibility\"|U",
                $i,
                $title,
                PREG_PATTERN_ORDER
            );

            preg_match_all(
                "|\"descriptionSnippet\":{\"runs\":\[{\"text\":\"(.*)\"}\]},\"longBylineText\"|U",
                $i,
                $description,
                PREG_PATTERN_ORDER
            );

            preg_match_all(
                "|\"ownerText\":{\"runs\":\[{\"text\":\"(.*)\",\"navigationEndpoint\"|U",
                $i,
                $owner,
                PREG_PATTERN_ORDER
            );

            preg_match_all(
                "|\"publishedTimeText\":{\"simpleText\":\"(.*)\"},\"lengthText\"|U",
                $i,
                $published_time,
                PREG_PATTERN_ORDER
            );

            preg_match_all(
                "|\"lengthText\":{\"accessibility\":{\"accessibilityData\":{\"label\":\"[^\"]+\"}},\"simpleText\":\"(.*)\"},\"viewCountText\"|U",
                $i,
                $length,
                PREG_PATTERN_ORDER
            );

            preg_match_all(
                "|\"viewCountText\":{\"simpleText\":\"(.*)\"},\"navigationEndpoint\"|U",
                $i,
                $view_count,
                PREG_PATTERN_ORDER
            );

            preg_match_all(
                "~\"url\":\"/(channel|user)(.*)\",\"webPageType\"~U",
                $i,
                $channel_url,
                PREG_PATTERN_ORDER
            );

            preg_match_all(
                "|\"url\":\"/watch(.*)\",\"webPageType\"|U",
                $i,
                $video_url,
                PREG_PATTERN_ORDER
            );

            foreach ($image[1] as $index => $y) {
                $image[1][$index] = 'https://i.ytimg.com' . $image[1][$index];
            }
            array_push($group1, [
                'title' => !empty($title[1]) && !empty($title[1][0]) ? $title[1][0] : '',
                'description' => !empty($description[1]) && !empty($description[1][0]) ? $description[1][0] : '',
                'thumb_images' => $image[1],
                'owner' => !empty($owner[1]) && !empty($owner[1][0]) ? $owner[1][0] : '',
                'published_time' => !empty($published_time[1]) && !empty($published_time[1][0]) ? $published_time[1][0] : '',
                'length' => !empty($length[1]) && !empty($length[1][0]) ? $length[1][0] : '',
                'view_count' => !empty($view_count[1]) && !empty($view_count[1][0]) ? $view_count[1][0] : '',
                'channel_url' => !empty($channel_url[2]) && !empty($channel_url[2][0]) ? 'https://www.youtube.com/' . (!empty($channel_url[1][0]) ? $channel_url[1][0] : '') . $channel_url[2][0] : '',
                'video_url' => !empty($video_url[1]) && !empty($video_url[1][0]) ? 'https://www.youtube.com/watch' . $video_url[1][0] : ''
            ]);
        }
        foreach ($out2[1] as $i) {
            preg_match_all(
                "|\"url\":\"https://i.ytimg.com(.*)\",\"width\":|U",
                $i,
                $image,
                PREG_PATTERN_ORDER
            );

            preg_match_all(
                "|\"title\":{\"runs\":\[{\"text\":\"(.*)\"}\],\"accessibility\"|U",
                $i,
                $title,
                PREG_PATTERN_ORDER
            );

            preg_match_all(
                "|\"descriptionSnippet\":{\"runs\":\[{\"text\":\"(.*)\"}\]},\"longBylineText\"|U",
                $i,
                $description,
                PREG_PATTERN_ORDER
            );

            preg_match_all(
                "|\"ownerText\":{\"runs\":\[{\"text\":\"(.*)\",\"navigationEndpoint\"|U",
                $i,
                $owner,
                PREG_PATTERN_ORDER
            );

            preg_match_all(
                "|\"publishedTimeText\":{\"simpleText\":\"(.*)\"},\"lengthText\"|U",
                $i,
                $published_time,
                PREG_PATTERN_ORDER
            );

            preg_match_all(
                "|\"lengthText\":{\"accessibility\":{\"accessibilityData\":{\"label\":\"[^\"]+\"}},\"simpleText\":\"(.*)\"},\"viewCountText\"|U",
                $i,
                $length,
                PREG_PATTERN_ORDER
            );

            preg_match_all(
                "|\"viewCountText\":{\"simpleText\":\"(.*)\"},\"navigationEndpoint\"|U",
                $i,
                $view_count,
                PREG_PATTERN_ORDER
            );

            preg_match_all(
                "~\"url\":\"/(channel|user)(.*)\",\"webPageType\"~U",
                $i,
                $channel_url,
                PREG_PATTERN_ORDER
            );

            preg_match_all(
                "|\"url\":\"/watch(.*)\",\"webPageType\"|U",
                $i,
                $video_url,
                PREG_PATTERN_ORDER
            );

            foreach ($image[1] as $index => $y) {
                $image[1][$index] = 'https://i.ytimg.com' . $image[1][$index];
            }
            array_push($group2, [
                'title' => !empty($title[1]) && !empty($title[1][0]) ? $title[1][0] : '',
                'description' => !empty($description[1]) && !empty($description[1][0]) ? $description[1][0] : '',
                'thumb_images' => $image[1],
                'owner' => !empty($owner[1]) && !empty($owner[1][0]) ? $owner[1][0] : '',
                'published_time' => !empty($published_time[1]) && !empty($published_time[1][0]) ? $published_time[1][0] : '',
                'length' => !empty($length[1]) && !empty($length[1][0]) ? $length[1][0] : '',
                'view_count' => !empty($view_count[1]) && !empty($view_count[1][0]) ? $view_count[1][0] : '',
                'channel_url' => !empty($channel_url[2]) && !empty($channel_url[2][0]) ? 'https://www.youtube.com/' . (!empty($channel_url[1][0]) ? $channel_url[1][0] : '') . $channel_url[2][0] : '',
                'video_url' => !empty($video_url[1]) && !empty($video_url[1][0]) ? 'https://www.youtube.com/watch' . $video_url[1][0] : ''
            ]);
        }

        if(!count($group1)){
            Cache::put('trending_response', $res);
            echo 'trending response error';die;
        }

        $searchData = [
            'group1' => $group1,
            'group2' => $group2,
            'locations' => $this->getLocations(),
            'recentlyTrendingText' => $recentlyTrendingText
        ];

        return view('welcome', $searchData);
    }
}
