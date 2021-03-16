@extends('layouts.alerj')

@section('title', config('app.name'))

<!-- Current Proposals -->
@section('content')

    @include('partials.alert')

    <div class="panel panel-default">
        <div class="panel-heading text-center">Propostas Legislativas sem Resposta</div>
            <div class="panel-body">
                <table id="datatable" class="table table-striped table-hover compact" cellspacing="0" width="100%">

                    <thead>
                    <tr><td>{!! $proposals->links() !!}</td></tr>
                    <tr>
                        <th><h3>Proposta Legislativa</h3></th>
                        <th class="text-center"><h3>Sem Resposta</h3></th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($proposals as $proposal)
                        <tr>
                            <!-- <td> {{-- Html::linkAction('ProposalsController@show', $proposal->name, array($proposal->id)) --}} </td>-->
                            <td class="blue_link"><a href="{{ route('proposal.show',array('id'=>$proposal->id)) }}">{{ $proposal->name }}</a></td>
                            @if (isset($is_not_responded) && Auth::user()->is_admin)
                                <td class="text-center"><a href="{{ route('proposal.response', $proposal->id) }}" class="btn btn-danger">Responder Proposta</a></td>
                            @else
                                <td>{{ $proposal->likes->count() }}</td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr><td>{!! $proposals->links() !!}</td></tr>
                    </tfoot>
                </table>
            </div>
    </div>
@stop
