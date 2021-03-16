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
                        <h3 class="box-title">Propostas Legislativas em tramitação na Comissão</h3>
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
                                            @if(config('app.likes_enabled'))
                                                <th>Curtidas</th>
                                            @endIf
                                            <th>Apoios</th>
                                            <th>Projeto de Lei</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach ($approvedsByCommittee as $approvedByCommittee)
                                            <tr>
                                                <td>{{ $approvedByCommittee->id }}</td>
                                                <td><a href="{{ route('admin.proposal.show',array('id'=>$approvedByCommittee->id)) }}">{{ $approvedByCommittee->name }}</a></td>
                                                {{--<td class="blue_link"><a href="{{ route('proposal.show',array('id'=>$approved->id)) }}">{{ $approved->name }}</a></td>--}}
                                                {{--<td><a href="{{ route('admin.proposal.response', $approved->id) }}" class="btn btn-danger">Responder Proposta</a></td>--}}
                                                @if(config('app.likes_enabled'))
                                                    <td>{{$approvedByCommittee->like_count - $approvedByCommittee->unlike_count}}</td>
                                                @endIf
                                                <td>{{$approvedByCommittee->approvals()->count()}}</td>
                                                @if ($approvedByCommittee->bill_project_id == null)
                                                <td><a href="{{ route('admin.proposal.billProject', ['id' => $approvedByCommittee->id]) }}" class="btn btn-warning botao" role="button">
                                                        <i class="fa fa-toggle-on"></i> Assignar Projeto de Lei </a>
                                                    {{--{{ $approvedByCommittee->bill_project_id }}--}}</td>
                                                @else
                                                    <td>Número: {{ $approvedByCommittee->bill_project->number }}<br>
                                                        Link: <a href={{$approvedByCommittee->bill_project->link}} target="_blank">{{ $approvedByCommittee->bill_project->link }}</a><br>
                                                        Responsável: {{$approvedByCommittee->bill_project->owner}}
                                                    </td>
                                                @endif
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
