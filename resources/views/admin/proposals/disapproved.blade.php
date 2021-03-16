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
                        <h3 class="box-title">Propostas Legislativas Não Aprovadas</h3>
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
                                            <th>Apoios</th>
                                            <th>Moderação</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach ($disapproveds as $disapproved)
                                            <tr>
                                                <td>{{ $disapproved->id }}</td>
                                                <td><a href="{{ route('admin.proposal.show',array('id'=>$disapproved->id)) }}">{{ $disapproved->name }}</a></td>

                                                <td>{{$disapproved->approvals()->count()}}</td>
                                                @if($disapproved->isModeratable())
                                                    <td><a href="{{ route('admin.proposal.response', ['id' => $disapproved->id]) }}" class="btn btn-info botao" role="button"><i class="fa fa-cog fa-spin fa fa-fw"></i> Moderar esta ideia!</a>  </td>
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
