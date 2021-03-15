@extends('layouts.admin')

@section('title', 'Propostas Legislativas')

@section('content')

    <!-- Main row -->
    <div class="row">
        <div class="col-md-12">

            @include('partials.error')

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{ $proposal->name }}</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div id="dataTableUser_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="panel-body">
                                        @include('admin.proposals.partials.table')
                                    </div>

                                    <div class="panel-footer">
                                        <div class="row">
                                            {{--Proposal Moderation--}}
                                            <div class="pull-left botao">
                                                @if ($proposal->state == App\Enums\ProposalState::NotModerated || $proposal->state == App\Enums\ProposalState::Approved ||$proposal->state == App\Enums\ProposalState::Disapproved || $proposal->state == App\Enums\ProposalState::Supported)
                                                    <a href="{{ route('admin.proposal.response', ['id' => $proposal->id]) }}" class="btn btn-info botao" role="button" dusk="proposal_moderate">
                                                        <i class="fa fa-cog fa-spin fa fa-fw" ></i> Moderar essa Ideia! </a>
                                                @else
                                                    <i class="fa fa-check-square text-success" aria-hidden="true"></i> Esta Ideia Legislativa já foi Moderada.
                                                @endif
                                            </div>

                                            {{--<div class="pull-left botao">--}}
                                                {{--<a href="{{ route('admin.proposal.approved', ['id' => $proposal->id]) }}" class="btn btn-info botao" role="button">--}}
                                                    {{--<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> Aprovar! </a>--}}
                                                {{--<a href="{{ route('admin.proposal.disapproved', ['id' => $proposal->id]) }}" class="btn btn-info botao" role="button">--}}
                                                    {{--<span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> Desaprovar</a>--}}
                                                {{--<a href="{{ route('proposal.approval', $proposal->id) }}" class="btn btn-info botao" role="button">--}}
                                                    {{--<span class="glyphicon glyphicon-star" aria-hidden="true"></span> Apoiar essa Ideia!</a>--}}
                                            {{--</div>--}}

                                            {{--<div class="pull-left botao">--}}

                                                {{--<a href="{{ URL::current() }}/like" class="btn btn-info" role="button">Apoiar essa Ideia!</a>--}}
                                                {{--<a href="{{ route('proposal.like', ['id' => $proposal->id]) }}" class="btn btn-info botao" role="button">--}}
                                                    {{--<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> Gostei dessa Ideia! </a>--}}
                                                {{--<a href="{{ route('proposal.unlike', ['id' => $proposal->id]) }}" class="btn btn-info botao" role="button">--}}
                                                    {{--<span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> Não gostei dessa Ideia!</a>--}}
                                                {{--<a href="{{ route('proposal.approval', $proposal->id) }}" class="btn btn-info botao" role="button">--}}
                                                    {{--<span class="glyphicon glyphicon-star" aria-hidden="true"></span> Apoiar essa Ideia!</a>--}}
                                            {{--</div>--}}


                                            <div class="pull-right botao">

                                                @can('edit', $proposal)
                                                    <a href="{{ route('admin.proposal.edit', $proposal->id) }}" class="btn btn-primary botao">
                                                        <span class="fas fa-edit" aria-hidden="true"></span> Editar Proposta</a>
                                                @endcan

{{--                                                 @can('bypass', $proposal)--}}
{{--                                                    @if($proposal->in_committee)--}}

{{--                                                    @else--}}
{{--                                                        <a href="{{ route('admin.proposal.bypass', $proposal->id) }}" class="btn btn-danger botao">--}}
{{--                                                            <span class="glyphicon glyphicon-forward" aria-hidden="true"></span> Enviar diretamente à Comissão</a>--}}
{{--                                                    @endif--}}
{{--                                                 @endcan--}}

                                                {{--@can('destroy', $proposal)--}}
                                                {{--<a href="{{ route('admin.proposal.destroy', $proposal->id) }}" class="btn btn-danger botao" onclick="if(!confirm('Você tem certeza que deseja apagar essa Ideia Legislativa?')){return false;};">--}}
                                                    {{--<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Eliminar Proposta</a>--}}
                                                {{--@endcan--}}
                                            </div>
                                       </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>

@stop
