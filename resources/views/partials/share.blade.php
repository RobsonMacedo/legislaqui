<div class="controleideias row mt-2 mb-1 d-flex align-items-center ml-2 mr-2">
    <div class="action_links col-12 col-sm-9 ">

        @if(config('app.likes_enabled'))
            <a dusk="like" href="{{ route('proposal.like', ['id' => $proposal->id]) }}">
                <i class="far fa-thumbs-up" aria-hidden="true"></i><span class="hidden-xs">Curtir</span></a>

            <a dusk="dislike" href="{{ route('proposal.unlike', ['id' => $proposal->id]) }}">
                <i class="far fa-thumbs-down" aria-hidden="true"></i><span class="hidden-xs">Descurtir</span></a>
        @endIf

        @if($proposal->isSupportable())
            @if (!Auth::check())
                <a dusk="support" href="{{ route('proposal.approval', $proposal->id) }}" onclick="if(!confirm('Para apoiar oficialmente uma ideia legislativa você precisa criar uma conta na página da ALERJ.')){return false;};">
                    <i class="far fa-star" aria-hidden="true"></i><span class="hidden-xs">Apoiar!</span></a>
            @elseif (Auth::check())
                <a dusk="support" href="{{ route('proposal.approval', $proposal->id) }}">
                    <i class="far fa-star" aria-hidden="true"></i><span class="hidden-xs">Apoiar!</span></a>
            @endif
        @endIf

        @if($proposal->isFollowable())
            @if (!Auth::check())
                <a dusk="follow" href="{{ route('proposal.follow', $proposal->id) }}" onclick="if(!confirm('Para acompanhar uma ideia legislativa você precisa criar uma conta na página da ALERJ.')){return false;};">
                    <i class="fas fa-asterisk" aria-hidden="true"></i><span class="hidden-xs">Acompanhar</span></a>
            @elseif (Auth::check() && $proposal->isFollowedByCurrentUser())
                <a dusk="unfollow"href="{{ route('proposal.unfollow', $proposal->id) }}">
                    <i class="fas fa-asterisk" aria-hidden="true"></i><span class="hidden-xs">Deixar de acompanhar</span></a>
            @else
                <a dusk="follow" href="{{ route('proposal.follow', $proposal->id) }}">
                    <i class="fas fa-asterisk" aria-hidden="true"></i><span class="hidden-xs">Acompanhar</span></a>
            @endif
        @endIf
    </div>


    <div class="share_links col-12 col-sm-3 text-right">
        <div class="pull-right">
            <a href="{{'https://api.whatsapp.com/send?phone=&text='.rawurlencode($url)}}" target="_blank">
                <i class="fab fa-whatsapp-square"></i>
            </a>

            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($url) }}"
               target="_blank"><i class="fab fa-facebook-square"></i>
            </a>

            <a href="https://twitter.com/intent/tweet?text=Proponha%20sua%20Ideia%20Legislativa%20Aqui!&url={{ urlencode($url) }}&ALERJ&hashtags={!! config('app.name') !!}"
               target="_blank"><i class="fab fa-twitter-square"></i>
            </a>

            <a href="mailto:?subject={{ config('app.name') }}&amp;body={{ urlencode($url) }} "><i class="fa fa-envelope-square"></i>
            </a>
        </div>
    </div>
</div>

