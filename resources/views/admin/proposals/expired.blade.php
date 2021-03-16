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
                        <h3 class="box-title">Propostas Legislativas que expiraram, sem receberam o número mínimo de Apoios</h3>
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
                                            {{--<th>Sem Resposta</th>--}}
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach ($expired as $expired)
                                            <tr>
                                                <td>{{ $expired->id }}</td>
                                                <td><a href="{{ route('admin.proposal.show',array('id'=>$expired->id)) }}">{{ $expired->name }}</a></td>
                                                @if(config('app.likes_enabled'))
                                                    <td>{{$expired->like_count - $expired->unlike_count}}</td>
                                                @endIf
                                                <td>{{$expired->approvals()->count()}}</td>
                                                {{--<td class="blue_link"><a href="{{ route('proposal.show',array('id'=>$approved->id)) }}">{{ $approved->name }}</a></td>--}}
                                                {{--<td><a href="{{ route('admin.proposal.response', $approved->id) }}" class="btn btn-danger">Responder Proposta</a></td>--}}
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
