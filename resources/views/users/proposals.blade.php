@extends('layouts.alerj')

@section('title', 'Propostas Legislativas')

<!-- Current Proposals -->
@section('content')

    @include('partials.alert')

    @if ( ! $proposals->isEmpty() )

        <div class="cards-lista-ideias">
            <h1><i class="fas fa-clipboard-list"></i> Minhas Ideias Legislativas</h1>


            <div class="row mt-5">

                @foreach ($proposals as $proposal)

                    <div class="col-sm-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <a href="{{ route('proposal.show',array('id'=>$proposal->id)) }}" class="stretched-link">
                                    <h5 class="card-title"><i class="fas fa-list-alt"></i> {{ $proposal->name }} </h5>
                                </a>

                                <p>
                                    @include('proposals.partials.badge')
                                </p>

                                <p class="card-text">
                                    {{$proposal->idea_exposition}}
                                </p>
                            </div>

                            <div class="card-footer">
                                @if(config('app.likes_enabled'))
                                    <span class="curtidas">
                                        <i class="fa fa-thumbs-up" aria-hidden="true"></i> {{ ($proposal->like_count - $proposal->unlike_count) }} Curtidas
                                    </span>

                                    <span class="apoios ml-3">
                                @endIf
                                    <i class="fa fa-star" aria-hidden="true"></i> {{ $proposal->approvals->count() }} Apoios
                                @if(config('app.likes_enabled'))
                                    </span>
                                @endIf
                            </div>

                        </div>
                    </div>
                @endforeach


            </div>



            @else

                <div class="panel-body">
                    <br><br>
                    <div class="alert alert-info">Clique abaixo para incluir sua primeira proposta legislativa.</div>

                    <table id="datatable" class="" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th class="create">
                                <h3>
                                    @if (!Auth::user())
                                        <a href="{{ route('proposal.create') }}" onclick="if(!confirm('Para incluir nova ideia legislativa você deve estar logado')){return false;}">
                                            @else
                                                <a href="{{ route('proposal.create') }}">
                                                    @endif
                                                    <div class="icon-wrapper"><i class="fa fa-plus-circle custom-icon"><span class="fix-editor">&nbsp;</span></i></div>
                                                    <
                                </h3>
                            </th>
                        </tr>
                        </thead>
                    </table>
                    <a href="{{ route('proposal.create') }}" class="btn btn-primary botao">
                        <span class="fas fa-plus" aria-hidden="true"></span> Incluir Nova Proposta
                    </a>
                    @endif

        </div>



@stop
