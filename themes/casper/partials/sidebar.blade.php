<div class="nav">
    <h3 class="nav-title">Menu</h3>
    <a href="#" class="nav-close">
        <span class="hidden">Close</span>
    </a>
    <ul>
        @foreach (navigation_menu() as $menu)
        <li class="nav-home nav-current" role="presentation">
            <a href="{{ $menu->url }}">{{ $menu->label }}</a>
        </li>
        @endforeach
    </ul>
    <a class="subscribe-button" href="/rss">
        <i class="fa fa-rss"></i> Subscribe
    </a>
</div>
<span class="nav-cover"></span>