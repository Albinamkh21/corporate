<!-- START SIDEBAR -->
@if($portfolios )
    <div class="widget-first widget recent-posts">
        <h3>{{Lang :: get('blog.latest_projects')}}</h3>
        <div class="recent-post group">
            @foreach($portfolios as $portfolio)
                <div class="hentry-post group">
                    <div class="thumb-img"><img src="{{ asset(env('THEME')) }}/images/articles/{{ $portfolio->img->mini }}" alt="001" title="001" /></div>
                    <div class="text">
                        <a href="article.html" title="Section shortcodes &amp; sticky posts!" class="title">{{ $portfolio->title }}</a>
                        <p>{{ str_limit($portfolio->text, 100) }}</p>
                        <a class="read-more" href="{{ route('portfolio.show', ['alias' => $portfolio->alias] ) }}"> {{ Lang::get('blog.read_more')}} </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
<div class="widget-last widget recent-comments">
    @if($comments)
        <h3>{{Lang :: get('blog.latest_comments')}}</h3>
        <div class="recent-post recent-comments group">

            @foreach($comments as $comment)
                <div class="the-post group">
                    <div class="avatar">
                        @set($hash, ($comment->email)? md5($comment->email) : md5($comment->user->email) )
                        <img alt="" src="https://gravatar.com/avatar/{{ $hash }} ?d=mm&s=55 " class="avatar" />
                    </div>
                    <span class="author"><strong><a href="mailto:no-email@i-am-anonymous.not">{{ isset($comment->user) ? $comment->user->name : $comment->name }}</a></strong> in</span>
                    <a class="title" href="{{ route('articles.show', ['alias' => $comment->article->alias]) }}">{{ $comment->article->title }}</a>
                    <p class="comment">
                        {{ $comment->text }}
                        <a class="goto" href="{{ route('articles.show', ['alias' => $comment->article->alias]) }}">&#187;</a>
                    </p>
                </div>
            @endforeach
        </div>
    @endif
</div>



