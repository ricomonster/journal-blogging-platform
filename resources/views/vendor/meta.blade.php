@foreach($meta as $k => $m)
@if(isset($m['attribute']) && !empty($m['content']))
@if($m['attribute'] == 'name')
    <meta name="{{$m['value']}}" content="{{$m['content']}}"/>
@endif
@if($m['attribute'] == 'property')
    <meta property="{{$m['value']}}" content="{{$m['content']}}"/>
@endif
@endif
@if(isset($m['rel']) && !empty($m['href']))
    <link rel="{{$m['rel']}}" href="{{$m['href']}}"/>
@endif
@endforeach

@if($google_analytics && !empty($google_analytics))
    <!-- Google Analytic Code goes in here -->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', '{{$google_analytics}}', 'auto');
        ga('send', 'pageview');
    </script>
    <!-- Google Analytic Code stops in here -->
@endif
