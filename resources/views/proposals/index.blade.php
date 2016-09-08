@extends('layouts.alerj')

@section('title', 'e-democracia')

<!-- Current Proposals -->
@section('content')

    <div class="index">

        @include('partials.error')

        <div class="row">
            <div class="col-xs-12 instrucao">

                Você pode sugerir e dar seu apoio a ideias legislativas que podem virar novas leis estaduais, alterar leis já existentes ou mudar a Constituição Estadual.

                As sugestões que receberem pelo menos 20 mil apoios serão encaminhadas à Comissão de Direitos Humanos e Legislação Participativa (CDH), onde serão discutidas e receberão parecer dos deputados, podendo virar um projeto de lei.

                Antes de propor uma ideia, procure, na lista de ideias já apresentadas, outras com o mesmo conteúdo, para não dividir os apoios e atrasar a tramitação.

            </div>
            @if( $query == "open" )
              <div class="col-xs-12 instrucao">
                Essas são as propostas que ainda não chegaram à Comissão. Uma proposta precisa de 20 mil apoios para ser
                encaminhada à Comissão. Antes de criar uma proposta, verifique se não há uma já criada para o mesmo fim.
                 Várias ideias semelhantes terminam diluindo o apoio dos demais cidadãos.
              </div>
            @elseif ( $query == "comittee" )
              <div class="col-xs-12 instrucao">
                 Essas são as propostas que receberam o apoio suficiente e, neste momento, estão sendo analisadas pela comissão.
               </div>
            @elseif ( $query == "expired" )
              <div class="col-xs-12 instrucao">
                  Essas são as propostas que não receberam o apoio suficiente e não foram encaminhadas para análise da comissão.
              </div>
            @elseif ( $query == "disapprove" )
               <div class="col-xs-12 instrucao">
                   Essas são as propostas analisadas e não acatadas pela comissão.
               </div>
             @endif
        </div>

        <div class="panel panel-default">
            <div class="panel-heading-nav">
                {{--{!! $proposals->links() !!}--}}
            </div>

              <div class="panel-body">
                  <br><br>
                    <table id="datatable" class="table table-striped table-hover compact" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th class="create">
                                <h3>
                                    @if (!Auth::check())
                                        <a href="{{ route('proposal.create') }}" onclick="if(!confirm('Para incluir nova ideia legislativa você deve estar logado')){return false;};">
                                            @else
                                                <a href="{{ route('proposal.create') }}">
                                            @endif
                                            <div class="icon-wrapper"><i class="fa fa-plus-circle custom-icon"><span class="fix-editor">&nbsp;</span></i></div>
                                            <div class="quadrado_legislaqui">Crie nova Ideia</div>
                                        </a></a>
                                </h3>
                                 {{--LINK PARA DÚVIDAS FREQUENTES--}}
                                 {{--<a href="{{ route('about') }}">Dúvidas frequentes</a> --}}
                            </th>
                            @if (isset($is_not_responded) && Auth::user()->is_admin)
                                <th><h3>Sem Resposta</h3></th>
                            @else
                                <th class="text-center"><i class="fa fa-thumbs-up" aria-hidden="true"></i><h3>Curtidas</h3></th>
                                {{--<th><h3>Unlikes</h3></th>--}}
                                {{--<th><h3>Rating</h3></th>--}}
                                <th class="text-center"><i class="fa fa-star" aria-hidden="true"></i><h3>Apoios</h3></th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($proposals as $proposal)
                            <tr>
                                {{--<!-- <td> --}}{{-- Html::linkAction('ProposalsController@show', $proposal->name, array($proposal->id)) --}}{{-- </td>-->--}}
                                <td class="blue_link"><a href="{{ route('proposal.show',array('id'=>$proposal->id)) }}">{{ $proposal->name }}</a></td>
                                @if (isset($is_not_responded) && Auth::user()->is_admin)
                                    <td><a href="{{ route('proposal.response', $proposal->id) }}" class="btn btn-danger">Responder Proposta</a></td>
                                @else
                                    {{--Likes --}}
                                    <td class="text-center">{{ ($proposal->like_count - $proposal->unlike_count) }}</td>
                                    {{--Unlikes--}}
                                    {{--<td>{{ $proposal->unlike_count }}</td>--}}
                                    {{--Rating--}}
                                    {{--<td>{{ $proposal->rating }}</td>--}}
                                    {{--Approvals--}}
                                    <td class="text-center">{{ $proposal->approvals()->count() }}</td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="panel-footer-nav">
                    {!! $proposals->links() !!}
                </div>
        </div>
    </div>
@stop
