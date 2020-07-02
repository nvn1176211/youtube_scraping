<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="index.css?{{time()}}">
</head>

<body>
    <h2>Youtube Data Scraping</h2>
    <form action="search" method="post">
        @csrf
        <div class="main-search-form">
            <div class="main-search-item">
                <select name="location" style="width: 300px;" class="input-control">
                    <option value="" selected disabled>Choose Location</option>
                    @foreach($locations as $v)
                        <option value="{{$v[0] ?? ''}}">{{$v[1] ?? ''}}</option>
                    @endforeach
                </select>
            </div>
            <div class="main-search-item">
                <select name="search_type" class="input-control" style="width: 200px;">
                    <option value="" selected disabled>Choose Search Type</option>
                    @foreach(Constants::SEARCH_TYPE as $i => $v)
                        <option value="{{$i}}">{{$v}}</option>
                    @endforeach
                </select>
            </div>
            <div class="main-search-item">
                <select name="" class="input-control" style="width: 200px;">
                    <option value="" selected disabled>Choose Language</option>
                    @foreach(Constants::LANGUAGES as $i => $v)
                        <option value="{{$i}}">{{$v}}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-10px">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

    @if($group1)
    @foreach($group1 as $v)
        <div class="mt-20px" style="display: flex;">
            <div style="width: 20%;background-color:red;margin-right: 10px">
                <img src="{{$v['thumb_images'] && $v['thumb_images'][0] ? $v['thumb_images'][0] : ''}}" width="100%">
            </div>
            <div style="width: 80%">
                <div>
                    <span class="blue-text">Title: </span><span>{{$v['title'] ?? ''}}</span>
                </div>
                <div>
                    <span class="blue-text">Owner: </span><span>{{$v['owner'] ?? ''}}</span>
                </div>
                <div>
                    <span class="blue-text">Length: </span><span>{{$v['length'] ?? ''}}</span>
                </div>
                <div>
                    <span class="blue-text">View count: </span><span>{{$v['view_count'] ?? ''}}</span>
                </div>
                <div>
                    <span class="blue-text">Description: </span><span>{{$v['description'] ?? ''}}</span>
                </div>
                <div>
                    <span class="blue-text">Published time: </span><span>{{$v['published_time'] ?? ''}}</span>
                </div>
                <div>
                    <span class="blue-text">Channel url: </span><a href="{{$v['channel_url'] ?? ''}}" target="_blank">{{$v['channel_url'] ?? ''}}</a>
                </div>
                <div>
                    <span class="blue-text">Video url: </span><a href="{{$v['video_url'] ?? ''}}" target="_blank">{{$v['video_url'] ?? ''}}</a>
                </div>
                <div style="display: flex;">
                    <span class="blue-text" style="flex-shrink: 0">Thumbnail urls:&nbsp;</span>
                    <div>
                        @foreach($v['thumb_images'] as $v)
                            <a href="{{$v}}" target="_blank">{{$v}}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    @endif

    @if($group2)
    <h3 class="mt-30px mb-10px">{{$recentlyTrendingText}}</h3>
    @foreach($group2 as $v)
        <div class="mt-20px" style="display: flex;">
            <div style="width: 20%;display: block;background-color:red;margin-right: 10px">
            </div>
            <div style="width: 80%">
                <div>
                    <span class="blue-text">Title: </span><span>{{$v['title'] ?? ''}}</span>
                </div>
                <div>
                    <span class="blue-text">Owner: </span><span>{{$v['owner'] ?? ''}}</span>
                </div>
                <div>
                    <span class="blue-text">Length: </span><span>{{$v['length'] ?? ''}}</span>
                </div>
                <div>
                    <span class="blue-text">View count: </span><span>{{$v['view_count'] ?? ''}}</span>
                </div>
                <div>
                    <span class="blue-text">Description: </span><span>{{$v['description'] ?? ''}}</span>
                </div>
                <div>
                    <span class="blue-text">Published time: </span><span>{{$v['published_time'] ?? ''}}</span>
                </div>
                <div>
                    <span class="blue-text">Channel url: </span><a href="{{$v['channel_url'] ?? ''}}">{{$v['channel_url'] ?? ''}}</a>
                </div>
                <div>
                    <span class="blue-text">Video url: </span><a href="{{$v['video_url'] ?? ''}}">{{$v['video_url'] ?? ''}}</a>
                </div>
            </div>
        </div>
    @endforeach
    @endif

</body>

</html>