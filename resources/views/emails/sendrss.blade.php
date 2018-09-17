キーワードにマッチングした記事を送信いたします。

@foreach($contents as $content)
RSS:「{{ $content['rss_comments'] }}」
@foreach($content['rss_contents'] as $rss_content)

タイトル：{{$rss_content['title']}}
({{$rss_content['time']}})
マッチしたキーワード：{{$rss_content['match_keywords']}}
{{$rss_content['description']}}
link:{{$rss_content['link']}}
--------------------------------------------------------------------
@endforeach
@endforeach
