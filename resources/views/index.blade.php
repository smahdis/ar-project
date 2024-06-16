<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
{{--    <link href="{{ asset('/css/app.css') }}?v=<?php echo rand(10,100); ?>" rel="stylesheet">--}}
    <script src="{{ asset('/assets/js/mindar-image-three.prod.js') }}"></script>
    <script>
        window.mindFile = "{{$job->attachment()->where('attachments.id', $job->mind_file)->first()->relativeUrl}}";
{{--        window.mediaFile = "{{$job->attachment()->where('attachments.id', $job->video)->first()->relativeUrl}}";--}}
        window.mediaFiles = [];
        @foreach($job->videos as $key => $video)
            window.mediaFiles.push({
                media_file: "{{$video->attachment()->where('attachments.id', $video->video_file)->first()->relativeUrl}}",
                width_aspect: {{$video->width_aspect}},
                height_aspect: {{$video->height_aspect}}
            });

            window['playVideo{{$key}}'] = function() {
                console.log('index is', {{$key}});
                window.ar['video' + {{$key}}].play();
            }

        window['pauseVideo{{$key}}'] = function() {
            {{--console.log('index is', {{$key}});--}}
            window.ar['video' + {{$key}}].pause();
        }
        @endforeach

        window.width_aspect = "{{$job->width_aspect}}";
        window.height_aspect = "{{$job->height_aspect}}";

        {{--mindFile = {{$job->mind_file}};--}}
        {{--mediaFile = {{$job->video}};--}}
    </script>
    <script src="{{ asset('/assets/js/main.js') }}?v=<?php echo rand(10,10000); ?>" type="module"></script>
    <style>
        html, body {position: relative; margin: 0; width: 100%; height: 100%; overflow: hidden}
    </style>
</head>
<body>
</body>
<script data-consolejs-channel="f37f948e-fe24-3792-c22c-a1a529d3205d" src="https://remotejs.com/agent/agent.js"></script>
</html>


