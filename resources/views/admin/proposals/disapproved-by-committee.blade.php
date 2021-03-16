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
                        <h3 class="box-title">Propostas Legislativas não aprovadas pela Comissão</h3>
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
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach ($disapprovedsByCommittee as $disapprovedByCommittee)
                                            <tr>
                                                <td>{{ $disapprovedByCommittee->id }}</td>
                                                <td><a href="{{ route('admin.proposal.show',array('id'=>$disapprovedByCommittee->id)) }}">{{ $disapprovedByCommittee->name }}</a></td>
                                                @if(config('app.likes_enabled'))
                                                    <td>{{$disapprovedByCommittee->like_count - $disapprovedByCommittee->unlike_count}}</td>
                                                @endIf
                                                <td>{{$disapprovedByCommittee->approvals()->count()}}</td>

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
