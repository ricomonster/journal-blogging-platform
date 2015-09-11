@foreach($meta as $k => $m)
@if(isset($m['attribute']) && !empty($m['content']))
@if($m['attribute'] == 'name')
<meta name="{{$m['value']}}" content="{{$m['content']}}">
@endif
@if($m['attribute'] == 'property')
<meta property="{{$m['value']}}" content="{{$m['content']}}">
@endif
@endif
@if(isset($m['rel']) && !empty($m['href']))
<link rel="{{$m['rel']}}" href="{{$m['href']}}">
@endif
@endforeach

<!-- Google Analytic Code goes in here -->
{!! $google_analytics !!}
<!-- Google Analytic Code stops in here -->
