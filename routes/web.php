<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Controller@index');
Route::post('/search', 'Controller@search');




// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, "https://www.youtube.com/feed/trending");
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// $res = curl_exec($ch);
// curl_close($ch);
// $res_gr = explode('Thịnh hành gần đây', $res);
// $trending = $res_gr[0];
// $recent_trending = $res_gr[1];
// preg_match_all("|\sdir=\"ltr\">(.*)</a><span|U",
// $trending,
// $out1, PREG_PATTERN_ORDER);
// if(!count($out1[0])){
//     preg_match_all("|{\"videoRenderer\":{\"videoId\":(.*),{\"thumbnailOverlayNowPlayingRenderer\":|U",
//     $trending,
//     $out1, PREG_PATTERN_ORDER);
//     preg_match_all("|{\"videoRenderer\":{\"videoId\":(.*),{\"thumbnailOverlayNowPlayingRenderer\":|U",
//     $recent_trending,
//     $out2, PREG_PATTERN_ORDER);
//     $group1 = [];
//     $group2 = [];
//     foreach($out1[1] as $i){
//         preg_match_all("|\"url\":\"https://i.ytimg.com(.*)\",\"width\":|U",
//         $i,
//         $image, PREG_PATTERN_ORDER);

//         preg_match_all("|\"title\":{\"runs\":\[{\"text\":\"(.*)\"}\],\"accessibility\"|U",
//         $i,
//         $title, PREG_PATTERN_ORDER);

//         preg_match_all("|\"descriptionSnippet\":{\"runs\":\[{\"text\":\"(.*)\"}\]},\"longBylineText\"|U",
//         $i,
//         $description, PREG_PATTERN_ORDER);

//         preg_match_all("|\"ownerText\":{\"runs\":\[{\"text\":\"(.*)\",\"navigationEndpoint\"|U",
//         $i,
//         $owner, PREG_PATTERN_ORDER);

//         preg_match_all("|\"publishedTimeText\":{\"simpleText\":\"(.*)\"},\"lengthText\"|U",
//         $i,
//         $published_time, PREG_PATTERN_ORDER);

//         preg_match_all("|\"lengthText\":{\"accessibility\":{\"accessibilityData\":{\"label\":\"[^\"]+\"}},\"simpleText\":\"(.*)\"},\"viewCountText\"|U",
//         $i,
//         $length, PREG_PATTERN_ORDER);

//         preg_match_all("|\"viewCountText\":{\"simpleText\":\"(.*)\"},\"navigationEndpoint\"|U",
//         $i,
//         $length, PREG_PATTERN_ORDER);

//         preg_match_all("~\"url\":\"/(channel|user)(.*)\",\"webPageType\"~U",
//         $i,
//         $channel_url, PREG_PATTERN_ORDER);

//         preg_match_all("|\"url\":\"/watch(.*)\",\"webPageType\"|U",
//         $i,
//         $video_url, PREG_PATTERN_ORDER);

//         foreach($image[1] as $index => $y){
//             $image[1][$index] = 'https://i.ytimg.com'. $image[1][$index];
//         }
//         array_push($group1, [
//             'title' => !empty($title[1]) && !empty($title[1][0]) ? $title[1][0] : '', 
//             'description' => !empty($description[1]) && !empty($description[1][0]) ? $description[1][0] : '', 
//             'thumb_images' => $image[1],
//             'owner' => !empty($owner[1]) && !empty($owner[1][0]) ? $owner[1][0] : '',
//             'published_time' => !empty($published_time[1]) && !empty($published_time[1][0]) ? $published_time[1][0] : '',
//             'length' => !empty($length[1]) && !empty($length[1][0]) ? $length[1][0] : '',
//             'channel_url' => !empty($channel_url[2]) && !empty($channel_url[2][0]) ? 'https://www.youtube.com/'.(!empty($channel_url[1][0]) ? $channel_url[1][0] : '').$channel_url[2][0] : '',
//             'video_url' => !empty($video_url[1]) && !empty($video_url[1][0]) ? 'https://www.youtube.com/watch'.$video_url[1][0] : ''
//         ]);
//     }

//     foreach($out2[1] as $i){
//         preg_match_all("|\"url\":\"https://i.ytimg.com(.*)\",\"width\":|U",
//         $i,
//         $image, PREG_PATTERN_ORDER);

//         preg_match_all("|\"title\":{\"runs\":\[{\"text\":\"(.*)\"}\],\"accessibility\"|U",
//         $i,
//         $title, PREG_PATTERN_ORDER);

//         preg_match_all("|\"descriptionSnippet\":{\"runs\":\[{\"text\":\"(.*)\"}\]},\"longBylineText\"|U",
//         $i,
//         $description, PREG_PATTERN_ORDER);

//         preg_match_all("|\"ownerText\":{\"runs\":\[{\"text\":\"(.*)\",\"navigationEndpoint\"|U",
//         $i,
//         $owner, PREG_PATTERN_ORDER);

//         preg_match_all("|\"publishedTimeText\":{\"simpleText\":\"(.*)\"},\"lengthText\"|U",
//         $i,
//         $published_time, PREG_PATTERN_ORDER);

//         preg_match_all("|\"lengthText\":{\"accessibility\":{\"accessibilityData\":{\"label\":\"[^\"]+\"}},\"simpleText\":\"(.*)\"},\"viewCountText\"|U",
//         $i,
//         $length, PREG_PATTERN_ORDER);

//         preg_match_all("|\"viewCountText\":{\"simpleText\":\"(.*)\"},\"navigationEndpoint\"|U",
//         $i,
//         $length, PREG_PATTERN_ORDER);

//         preg_match_all("~\"url\":\"/(channel|user)(.*)\",\"webPageType\"~U",
//         $i,
//         $channel_url, PREG_PATTERN_ORDER);

//         preg_match_all("|\"url\":\"/watch(.*)\",\"webPageType\"|U",
//         $i,
//         $video_url, PREG_PATTERN_ORDER);

//         foreach($image[1] as $index => $y){
//             $image[1][$index] = 'https://i.ytimg.com'. $image[1][$index];
//         }
//         array_push($group2, [
//             'title' => !empty($title[1]) && !empty($title[1][0]) ? $title[1][0] : '', 
//             'description' => !empty($description[1]) && !empty($description[1][0]) ? $description[1][0] : '', 
//             'thumb_images' => $image[1],
//             'owner' => !empty($owner[1]) && !empty($owner[1][0]) ? $owner[1][0] : '',
//             'published_time' => !empty($published_time[1]) && !empty($published_time[1][0]) ? $published_time[1][0] : '',
//             'length' => !empty($length[1]) && !empty($length[1][0]) ? $length[1][0] : '',
//             'channel_url' => !empty($channel_url[2]) && !empty($channel_url[2][0]) ? 'https://www.youtube.com/'.(!empty($channel_url[1][0]) ? $channel_url[1][0] : '').$channel_url[2][0] : '',
//             'video_url' => !empty($video_url[1]) && !empty($video_url[1][0]) ? 'https://www.youtube.com/watch'.$video_url[1][0] : ''
//         ]);
//     }
// }else{
//     preg_match_all("|\sdir=\"ltr\">(.*)</a><span|U",
//     $recent_trending,
//     $out2, PREG_PATTERN_ORDER);
// }
// dd($group1, $group2);
