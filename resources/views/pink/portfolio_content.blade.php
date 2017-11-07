@if($portfolios)
    <div id="content-page" class="content group">
        <div class="hentry group">

            <div id="portfolio" class="portfolio-big-image">

               @foreach($portfolios as $portfolio)
                    <div class="hentry work group">
                        <div class="work-thumbnail">
                            <div class="nozoom">
                                <img src="{{ asset(env('THEME')) }}/images/projects/{{ $portfolio->img->max }}" alt="0061" title="0061" />
                                <div class="overlay">
                                    <a class="overlay_img" href="{{ asset(env('THEME')) }}/images/projects/{{ $portfolio->img->path }}" rel="lightbox" title="Love"></a>
                                    <a class="overlay_project" href="{{ route('portfolio.show', $portfolio->alias ) }}"></a>
                                    <span class="overlay_title">{{ $portfolio->title }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="work-description">
                            <h3>{{ $portfolio->title }}</h3>
                            <p>{{ $portfolio->text }}</p>

                            <div class="clear"></div>
                            <div class="work-skillsdate">
                                <p class="skills"><span class="label">Skills:</span> {{ $portfolio->filter->title }}</p>
                                <p class="skills"><span class="label">Customer:</span> {{ $portfolio->customer }}</p>
                            </div>
                            <a class="read-more" href="{{ route('portfolio.show', $portfolio->alias ) }}">View Project</a>
                        </div>
                        <div class="clear"></div>
                    </div>
                @endforeach

                   <div class="general-pagination group">
                       @if ($portfolios->lastPage() > 1)
                           <ul class="pagination">

                               @if ($portfolios->currentPage() !== 1)
                                   <a  href="{{ $portfolios->url(($portfolios->currentPage()-1)) }}">{{ Lang::get('pagination.previous')}}</a>
                               @endif
                               @for ($i = 1; $i <= $portfolios->lastPage(); $i++)

                                   @if ($portfolios->currentPage() == $i)
                                       <a class="selected disabled">{{ $i }}</a>
                                   @else
                                       <a href="{{ $portfolios->url($i) }}">{{ $i }}</a>
                                   @endif


                               @endfor

                               @if ($portfolios->currentPage() !== $portfolios->lastPage())

                                   <a href="{{ $portfolios->url($portfolios->currentPage()+1) }}" >{{ Lang::get('pagination.next')}}</a>
                               @endif
                           </ul>
                       @endif


                   </div>
            </div>
            <div class="clear"></div>

        </div>

    </div>
@endif