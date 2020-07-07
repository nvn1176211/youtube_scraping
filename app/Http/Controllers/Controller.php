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
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.youtube.com/picker_ajax?action_country_json=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $rawLocation = curl_exec($ch);
        curl_close($ch);
        $rawLocation1 = json_decode($rawLocation);
        $rawLocation2 = !empty($rawLocation1->data) ? $rawLocation1->data : [];
        $locations = [];
        if (!empty($rawLocation2[1])) {
            foreach ($rawLocation2[1] as $v) {
                if (!empty($v[1]) && !empty($v[2])) {
                    array_push($locations, [$v[1], $v[2]]);
                }
            }
        }
        if(empty($locations)){
            Cache::put('raw_location', $rawLocation);
            echo 'load location fail! Please try again!';die;
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
            $_POST['location'] = "VN";
        }
        if (empty($_POST['language'])) {
            $_POST['language'] = 0;
        }
        $recentlyTrendingText = Constants::TEXT[$_POST['language']];
        $baseUrl = Constants::URL_MAP[$_POST['search_type']];
        $locationUrlPart = '?gl=' . $_POST['location'];
        $url = $baseUrl . $locationUrlPart;

        // do{
        //     $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_URL, $url);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //     $res = curl_exec($ch);
        //     curl_close($ch);
        //     $res_gr = explode($recentlyTrendingText, $res);
        //     $trending = $res_gr[0];
        //     $recent_trending = $res_gr[1];
        //     $group1 = [];
        //     $group2 = [];
        //     $style = 1;
        //     preg_match_all(
        //         "|{\"videoRenderer\":{\"videoId\":(.*),{\"thumbnailOverlayNowPlayingRenderer\":|U",
        //         $trending,
        //         $out1,
        //         PREG_PATTERN_ORDER
        //     );
        // }while(count($out1[0]));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        curl_close($ch);
        $res_gr = explode($recentlyTrendingText, $res);
        $trending = $res_gr[0];
        $recent_trending = $res_gr[1];
        $group1 = [];
        $group2 = [];
        $style = 1;
        preg_match_all(
            "|{\"videoRenderer\":{\"videoId\":(.*),{\"thumbnailOverlayNowPlayingRenderer\":|U",
            $trending,
            $out1,
            PREG_PATTERN_ORDER
        );
        
        if(!count($out1[0])){
            Cache::put('trending_response_2', $res);
            $style = 2;
            preg_match_all(
                "|<li class=\"expanded-shelf-content-item-wrapper\">([\s\S]*)</div></li>|U",
                $trending,
                $out1, 
                PREG_PATTERN_ORDER);
            preg_match_all(
                "|<li class=\"expanded-shelf-content-item-wrapper\">([\s\S]*)</div></li>|U",
                $recent_trending,
                $out2,
                PREG_PATTERN_ORDER
            );
            foreach ($out1[1] as $i) {
                preg_match_all(
                    "|https://i.ytimg.com(.*)\"|U",
                    $i,
                    $image,
                    PREG_PATTERN_ORDER
                );
    
                preg_match_all(
                    "|dir=\"ltr\">(.*)</a><span class=\"accessible-description\"|U",
                    $i,
                    $title,
                    PREG_PATTERN_ORDER
                );
    
                //div
                preg_match_all(
                    "|yt-ui-ellipsis-2\"\sdir=\"ltr\">(.*)</div>|U",
                    $i,
                    $description,
                    PREG_PATTERN_ORDER
                );
    
                preg_match_all(
                    "|data-sessionlink=\"[^\"]+\"\s>(.*)</a>|U",
                    $i,
                    $owner,
                    PREG_PATTERN_ORDER
                );
    
                //ngày trước
                preg_match_all(
                    "|<ul\sclass=\"yt-lockup-meta-info\"><li>(.*)</li><li>|U",
                    $i,
                    $published_time,
                    PREG_PATTERN_ORDER
                );
    
                preg_match_all(
                    "|<span\sclass=\"video-time\"\saria-hidden=\"true\">(.*)</span>|U",
                    $i,
                    $length,
                    PREG_PATTERN_ORDER
                );
    
                //lượt xem
                preg_match_all(
                    "|<ul\sclass=\"yt-lockup-meta-info\"><li>[^<]+</li><li>(.*)</li></ul>|U",
                    $i,
                    $view_count,
                    PREG_PATTERN_ORDER
                );
                // href=\"/(channel|user)/
                
                preg_match_all(
                    "|<div class=\"yt-lockup-byline \"><a href=\"(.*)\" class=\"yt-uix-sessionlink|U",
                    $i,
                    $channel_url,
                    PREG_PATTERN_ORDER
                );
    
                preg_match_all(
                    "|href=\"/watch(.*)\"\sclass=\"yt-uix-tile-link|U",
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
                    'channel_url' => !empty($channel_url[1]) && !empty($channel_url[1][0]) ? 'https://www.youtube.com' . $channel_url[1][0] : '',
                    'video_url' => !empty($video_url[1]) && !empty($video_url[1][0]) ? 'https://www.youtube.com/watch' . $video_url[1][0] : ''
                ]);
            }

            foreach ($out2[1] as $i) {
                preg_match_all(
                    "|https://i.ytimg.com(.*)\"|U",
                    $i,
                    $image,
                    PREG_PATTERN_ORDER
                );
    
                preg_match_all(
                    "|dir=\"ltr\">(.*)</a><span class=\"accessible-description\"|U",
                    $i,
                    $title,
                    PREG_PATTERN_ORDER
                );
    
                //div
                preg_match_all(
                    "|yt-ui-ellipsis-2\"\sdir=\"ltr\">(.*)</div>|U",
                    $i,
                    $description,
                    PREG_PATTERN_ORDER
                );
    
                preg_match_all(
                    "|data-sessionlink=\"[^\"]+\"\s>(.*)</a>|U",
                    $i,
                    $owner,
                    PREG_PATTERN_ORDER
                );
    
                //ngày trước
                preg_match_all(
                    "|<ul\sclass=\"yt-lockup-meta-info\"><li>(.*)</li><li>|U",
                    $i,
                    $published_time,
                    PREG_PATTERN_ORDER
                );
    
                preg_match_all(
                    "|<span\sclass=\"video-time\"\saria-hidden=\"true\">(.*)</span>|U",
                    $i,
                    $length,
                    PREG_PATTERN_ORDER
                );
    
                //lượt xem
                preg_match_all(
                    "|<ul\sclass=\"yt-lockup-meta-info\"><li>[^<]+</li><li>(.*)</li></ul>|U",
                    $i,
                    $view_count,
                    PREG_PATTERN_ORDER
                );
                // href=\"/(channel|user)/
                
                preg_match_all(
                    "|<div class=\"yt-lockup-byline \"><a href=\"(.*)\" class=\"yt-uix-sessionlink|U",
                    $i,
                    $channel_url,
                    PREG_PATTERN_ORDER
                );
    
                preg_match_all(
                    "|href=\"/watch(.*)\"\sclass=\"yt-uix-tile-link|U",
                    $i,
                    $video_url,
                    PREG_PATTERN_ORDER
                );
    
                foreach ($image[1] as $index => $y) {
                    $image[1][$index] = 'https://i.ytimg.com' . $image[1][$index];
                }
                
                array_push($group2, [
                    'title' => !empty($title[1]) && !empty($title[1][0]) ? $title[1][0] : '',
                    'description' => !empty($description[1]) && !empty($description[1][0]) ? '<div>'.$description[1][0].'</div>' : '',
                    'thumb_images' => $image[1],
                    'owner' => !empty($owner[1]) && !empty($owner[1][0]) ? $owner[1][0] : '',
                    'published_time' => !empty($published_time[1]) && !empty($published_time[1][0]) ? $published_time[1][0] : '',
                    'length' => !empty($length[1]) && !empty($length[1][0]) ? $length[1][0] : '',
                    'view_count' => !empty($view_count[1]) && !empty($view_count[1][0]) ? $view_count[1][0] : '',
                    'channel_url' => !empty($channel_url[1]) && !empty($channel_url[1][0]) ? 'https://www.youtube.com' . $channel_url[1][0] : '',
                    'video_url' => !empty($video_url[1]) && !empty($video_url[1][0]) ? 'https://www.youtube.com/watch' . $video_url[1][0] : ''
                ]);
            }

        }else{
            Cache::put('trending_response_1', $res);
            $style = 1;
            preg_match_all(
                "|{\"videoRenderer\":{\"videoId\":([\s\S]*),{\"thumbnailOverlayNowPlayingRenderer\":|U",
                $recent_trending,
                $out2,
                PREG_PATTERN_ORDER
            );
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
                    "|\"canonicalBaseUrl\":\"(.*)\"}}}\]},\"publishedTimeText\"|U",
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
                    'channel_url' => !empty($channel_url[1]) && !empty($channel_url[1][0]) ? 'https://www.youtube.com' . $channel_url[1][0] : '',
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
                    "|\"canonicalBaseUrl\":\"(.*)\"}}}\]},\"publishedTimeText\"|U",
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
                    'channel_url' => !empty($channel_url[1]) && !empty($channel_url[1][0]) ? 'https://www.youtube.com' . $channel_url[1][0] : '',
                    'video_url' => !empty($video_url[1]) && !empty($video_url[1][0]) ? 'https://www.youtube.com/watch' . $video_url[1][0] : ''
                ]);
            }
        }
        if(!count($group1)){
            Cache::put('trending_response', $res);
            echo 'Trending response error! Please try again!';die;
        }

        $searchData = [
            'group1' => $group1,
            'group2' => $group2,
            'style' => $style,
            'locations' => $this->getLocations(),
            'olds' => $this->setOldInput(),
            'recentlyTrendingText' => $recentlyTrendingText
        ];

        return view('welcome', $searchData);
    }

    public function setOldInput(){
        $old = [
            'location' => isset($_POST['location']) ? $_POST['location'] : '',
            'search_type' => isset($_POST['search_type']) ? $_POST['search_type'] : '',
            'language' => isset($_POST['language']) ? $_POST['language'] : '',
        ];
        return $old;
    }

   // dd(Cache::get('raw_location'));
        // echo Cache::get('raw_location');die;
        // preg_match_all("|<li class=\"expanded-shelf-content-item-wrapper\">([\s\S]*)</div></li>|U",
        // Cache::get('trending_response_2'),
        // $out1, PREG_PATTERN_ORDER);
        // $group1 = [];
        // foreach ($out1[1] as $i) {
        //     preg_match_all(
        //         "|https://i.ytimg.com(.*)\"|U",
        //         $i,
        //         $image,
        //         PREG_PATTERN_ORDER
        //     );

        //     preg_match_all(
        //         "|dir=\"ltr\">(.*)</a><span class=\"accessible-description\"|U",
        //         $i,
        //         $title,
        //         PREG_PATTERN_ORDER
        //     );

        //     //div
        //     preg_match_all(
        //         "|yt-ui-ellipsis-2\"\sdir=\"ltr\">(.*)</div>|U",
        //         $i,
        //         $description,
        //         PREG_PATTERN_ORDER
        //     );

        //     preg_match_all(
        //         "|data-sessionlink=\"[^\"]+\"\s>(.*)</a>|U",
        //         $i,
        //         $owner,
        //         PREG_PATTERN_ORDER
        //     );

        //     //ngày trước
        //     preg_match_all(
        //         "|<ul\sclass=\"yt-lockup-meta-info\"><li>(.*)</li><li>|U",
        //         $i,
        //         $published_time,
        //         PREG_PATTERN_ORDER
        //     );

        //     preg_match_all(
        //         "|<span\sclass=\"video-time\"\saria-hidden=\"true\">(.*)</span>|U",
        //         $i,
        //         $length,
        //         PREG_PATTERN_ORDER
        //     );

        //     //lượt xem
        //     preg_match_all(
        //         "|<ul\sclass=\"yt-lockup-meta-info\"><li>[^<]+</li><li>(.*)</li></ul>|U",
        //         $i,
        //         $view_count,
        //         PREG_PATTERN_ORDER
        //     );
        //     // href=\"/(channel|user)/
        //     preg_match_all(
        //         "|<div class=\"yt-lockup-byline \"><a href=\"(.*)\" class=\"yt-uix-sessionlink|U",
        //         $i,
        //         $channel_url,
        //         PREG_PATTERN_ORDER
        //     );
        //     preg_match_all(
        //         "|href=\"/watch(.*)\"\sclass=\"yt-uix-tile-link|U",
        //         $i,
        //         $video_url,
        //         PREG_PATTERN_ORDER
        //     );
        //     foreach ($image[1] as $index => $y) {
        //         $image[1][$index] = 'https://i.ytimg.com' . $image[1][$index];
        //     }
        //     array_push($group1, [
        //         'title' => !empty($title[1]) && !empty($title[1][0]) ? $title[1][0] : '',
        //         'description' => !empty($description[1]) && !empty($description[1][0]) ? $description[1][0] : '',
        //         'thumb_images' => $image[1],
        //         'owner' => !empty($owner[1]) && !empty($owner[1][0]) ? $owner[1][0] : '',
        //         'published_time' => !empty($published_time[1]) && !empty($published_time[1][0]) ? $published_time[1][0] : '',
        //         'length' => !empty($length[1]) && !empty($length[1][0]) ? $length[1][0] : '',
        //         'view_count' => !empty($view_count[1]) && !empty($view_count[1][0]) ? $view_count[1][0] : '',
        //         'channel_url' => !empty($channel_url[1]) && !empty($channel_url[1][0]) ? 'https://www.youtube.com' . $channel_url[1][0] : '',
        //         'video_url' => !empty($video_url[1]) && !empty($video_url[1][0]) ? 'https://www.youtube.com/watch' . $video_url[1][0] : ''
        //     ]);
        // }
        // dd($group1, $out1);    
}
