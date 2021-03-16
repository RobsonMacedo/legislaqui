@extends('layouts.admin')

@section('title', config('app.name'))

<!-- Current Proposals -->
@section('content')

    @include('partials.alert')

    @include('includes.status')

        <!-- Main row -->
        <div class="row">

            <div class="col-md-12">

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Propostas Legislativas não Moderadas</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div id="dataTableAdmin2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="dataTableAdmin2" class="table table-bordered table-striped table-hover compact dataTable" role="grid" aria-describedby="dataTableAdmin2_info">
                                        <thead>
                                        <tr role="row">
                                            <th>Id</th>
                                            <th>Título</th>
                                            <th>Moderação</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach ($notrespondeds as $notresponded)
                                            <tr>
                                                <td>{{ $notresponded->id }}</td>
                                                <td><a href="{{ route('admin.proposal.show',array('id'=>$notresponded->id)) }}">{{ $notresponded->name }}</a></td>
                                                {{--<td class="blue_link"><a href="{{ route('proposal.show',array('id'=>$notresponded->id)) }}">{{ $notresponded->name }}</a></td>--}}
                                                {{--<td><a href="{{ route('admin.proposal.response', $notresponded->id) }}" class="btn btn-danger">Responder Proposta</a></td>--}}
                                                {{--Proposal Moderation--}}
                                                <td class="text-center">
                                                    {{--{{$proposal->moderation($proposal->id)}}--}}
                                                    {{--@if () === null)--}}
                                                    {{--<a href="{{ route('admin.proposal.response', $proposal->id) }}" class="btn btn-danger">Responder Proposta</a>--}}
                                                    {{--@endif--}}
                                                    {{--@if ($notresponded->approved_at == null && $notresponded->approved_by == null && $notresponded->disapproved_at == null && $notresponded->disapproved_by == null )--}}
                                                        <a href="{{ route('admin.proposal.response', ['id' => $notresponded->id]) }}" class="btn btn-info botao" role="button">
                                                            <i class="fa fa-cog fa-spin fa fa-fw"></i> Moderar essa Ideia! </a>
                                                    {{--@else--}}
                                                        {{--<i class="fa fa-check-square text-success" aria-hidden="true"></i> Já Moderada--}}
                                                    {{--@endif--}}
                                                </td>
                                            </tr>
                                        @endforeach


                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@stop
