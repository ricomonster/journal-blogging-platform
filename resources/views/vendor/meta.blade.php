@if (!empty($metaTree))
@foreach ($metaTree['link'] as $ml => $link)
@foreach ($link as $ld => $linkData)
    <link {{ $ml }}="{{ $linkData['attribute'] }}" href="{{ $linkData['href'] }}"/>
@endforeach
@endforeach

@foreach ($metaTree['meta'] as $mm => $meta)
@foreach ($meta as $md => $metaData)
    @if (!empty($metaData['content']))
    <meta {{ $mm }}="{{ $metaData['attribute'] }}" content="{{ $metaData['content'] }}"/>
    @endif
@endforeach
@endforeach
@endif
