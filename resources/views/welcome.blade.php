<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="index.css?{{time()}}">
</head>

<body>
    <div class="p-10px">
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
                <div class="main-search-item">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>
    </div>
    <div>{{$style ?? ''}}</div>
    @if(!empty($group1))
    @foreach($group1 as $v)
        <div class="card" style="display: flex;">
            <div style="width: 20%;margin-right: 10px">
                <img src="{{$v['thumb_images'] && $v['thumb_images'][0] ? $v['thumb_images'][0] : ''}}" width="100%">
            </div>
            <div style="width: 80%">
                <div class="card-item">
                    <span class="card-title" style="flex-shrink: 0">Title: </span>
                    <div style="flex-grow: 2;">
                        <div style="width:100%;">
                            <input class="card-input" type="text" value="{{$v['title'] ?? ''}}">
                            <button class="card-button" type="button">copy</button>
                        </div>
                    </div>
                </div>
                <div class="card-item">
                    <span class="card-title" style="flex-shrink: 0">Owner: </span>
                    <div style="flex-grow: 2">
                        <div style="width:100%;">
                            <input class="card-input" type="text" value="{{$v['owner'] ?? ''}}">
                            <button class="card-button" type="button">copy</button>
                        </div>
                    </div>
                </div>
                <div class="card-item">
                    <span class="card-title" style="flex-shrink: 0">Length: </span>
                    <div style="flex-grow: 2">
                        <div style="width:100%;">
                            <input class="card-input" type="text" value="{{$v['length'] ?? ''}}">
                            <button class="card-button" type="button">copy</button>
                        </div>
                    </div>
                </div>
                <div class="card-item">
                    <span class="card-title" style="flex-shrink: 0">View count: </span>
                    <div style="flex-grow: 2">
                        <div style="width:100%;">
                            <input class="card-input" type="text" value="{{$v['view_count'] ?? ''}}">
                            <button class="card-button" type="button">copy</button>
                        </div>
                    </div>
                </div>
                <div class="card-item">
                    <span class="card-title" style="flex-shrink: 0">Description: </span>
                    <div style="flex-grow: 2">
                        <div style="width:100%;">
                            <input class="card-input" type="text" value="{{$v['description'] ?? ''}}">
                            <button class="card-button" type="button">copy</button>
                        </div>
                    </div>
                </div>
                <div class="card-item">
                    <span class="card-title" style="flex-shrink: 0">Published time: </span>
                    <div style="flex-grow: 2;">
                        <div style="width:100%">
                            <input class="card-input" type="text" value="{{$v['published_time'] ?? ''}}">
                            <button class="card-button" type="button">copy</button>
                        </div>
                    </div>
                </div>
                <div class="card-item">
                    <span class="card-title" style="flex-shrink: 0">Channel url: </span>
                    <div style="flex-grow: 2;">
                        <div style="width:100%">
                            <input class="card-input" type="text" value="{{$v['channel_url'] ?? ''}}">
                            <button class="card-button" type="button">copy</button>
                        </div>
                    </div>
                </div>
                <div class="card-item">
                    <span class="card-title" style="flex-shrink: 0">Video url: </span>
                    <div style="flex-grow: 2;">
                        <div style="width:100%">
                            <input class="card-input" type="text" value="{{$v['video_url'] ?? ''}}">
                            <button class="card-button" type="button">copy</button>
                        </div>
                    </div>
                </div>
                <div class="card-item">
                    <span class="card-title" style="flex-shrink: 0">Thumbnail urls:&nbsp;</span>
                    <div style="flex-grow: 2;">
                        <div>
                            <input class="card-input" type="text" value="{{$v['thumb_images'] && $v['thumb_images'][0] ? $v['thumb_images'][0] : ''}}">
                            <button class="card-button" type="button">copy</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    @endif

    @if(!empty($group2))
    <h3 class="mt-30px mb-10px">{{$recentlyTrendingText}}</h3>
    @foreach($group2 as $v)
    <div class="card" style="display: flex;">
            <div style="width: 20%;margin-right: 10px">
                <img src="{{$v['thumb_images'] && $v['thumb_images'][0] ? $v['thumb_images'][0] : ''}}" width="100%">
            </div>
            <div style="width: 80%">
                <div class="card-item">
                    <span class="card-title" style="flex-shrink: 0">Title: </span>
                    <div style="flex-grow: 2;">
                        <div style="width:100%;">
                            <input class="card-input" type="text" value="{{$v['title'] ?? ''}}">
                            <button class="card-button" type="button">copy</button>
                        </div>
                    </div>
                </div>
                <div class="card-item">
                    <span class="card-title" style="flex-shrink: 0">Owner: </span>
                    <div style="flex-grow: 2">
                        <div style="width:100%;">
                            <input class="card-input" type="text" value="{{$v['owner'] ?? ''}}">
                            <button class="card-button" type="button">copy</button>
                        </div>
                    </div>
                </div>
                <div class="card-item">
                    <span class="card-title" style="flex-shrink: 0">Length: </span>
                    <div style="flex-grow: 2">
                        <div style="width:100%;">
                            <input class="card-input" type="text" value="{{$v['length'] ?? ''}}">
                            <button class="card-button" type="button">copy</button>
                        </div>
                    </div>
                </div>
                <div class="card-item">
                    <span class="card-title" style="flex-shrink: 0">View count: </span>
                    <div style="flex-grow: 2">
                        <div style="width:100%;">
                            <input class="card-input" type="text" value="{{$v['view_count'] ?? ''}}">
                            <button class="card-button" type="button">copy</button>
                        </div>
                    </div>
                </div>
                <div class="card-item">
                    <span class="card-title" style="flex-shrink: 0">Description: </span>
                    <div style="flex-grow: 2">
                        <div style="width:100%;">
                            <input class="card-input" type="text" value="{{$v['description'] ?? ''}}">
                            <button class="card-button" type="button">copy</button>
                        </div>
                    </div>
                </div>
                <div class="card-item">
                    <span class="card-title" style="flex-shrink: 0">Published time: </span>
                    <div style="flex-grow: 2;">
                        <div style="width:100%">
                            <input class="card-input" type="text" value="{{$v['published_time'] ?? ''}}">
                            <button class="card-button" type="button">copy</button>
                        </div>
                    </div>
                </div>
                <div class="card-item">
                    <span class="card-title" style="flex-shrink: 0">Channel url: </span>
                    <div style="flex-grow: 2;">
                        <div style="width:100%">
                            <input class="card-input" type="text" value="{{$v['channel_url'] ?? ''}}">
                            <button class="card-button" type="button">copy</button>
                        </div>
                    </div>
                </div>
                <div class="card-item">
                    <span class="card-title" style="flex-shrink: 0">Video url: </span>
                    <div style="flex-grow: 2;">
                        <div style="width:100%">
                            <input class="card-input" type="text" value="{{$v['video_url'] ?? ''}}">
                            <button class="card-button" type="button">copy</button>
                        </div>
                    </div>
                </div>
                <div class="card-item">
                    <span class="card-title" style="flex-shrink: 0">Thumbnail urls:&nbsp;</span>
                    <div style="flex-grow: 2;">
                        <div>
                            <input class="card-input" type="text" value="{{$v['thumb_images'] && $v['thumb_images'][0] ? $v['thumb_images'][0] : ''}}">
                            <button class="card-button" type="button">copy</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    @endif

</body>

</html>