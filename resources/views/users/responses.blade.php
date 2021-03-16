@extends('layouts.alerj')

@section('title', 'Propostas Legislativas')

        <!-- Current Proposals -->
@section('content')

    @include('partials.alert')

    <div class="">
        <div class="">
            <div class="panel panel-default">
                <div class="panel-heading text-center"><strong>IDEIAS LEGISLATIVAS RESPONDIDAS</strong></div>
                <div class="panel-body">
                    <table id="datatable" class="table table-striped table-hover compact" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th><h3>Proposta Legislativa</h3></th>
                            <th class="text-center"><i class="fa fa-thumbs-up" aria-hidden="true"></i><h3>Curtidas</h3></th>
                            <th class="text-center"><i class="fa fa-star" aria-hidden="true"></i><h3>Apoios</h3></th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($proposals as $proposal)
                            <tr>
                                <!-- <td> {{-- Html::linkAction('ProposalsController@show', $proposal->name, array($proposal->id)) --}} </td>-->
                                <td class="blue_link"><a href="{{ route('proposal.show',array('id'=>$proposal->id)) }}">{{ $proposal->name }}</a> </td>
                                {{--Likes --}}
                                <td class="text-center">{{ ($proposal->like_count - $proposal->unlike_count) }}</td>
                                <td class="text-center">{{ $proposal->approvals->count() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop
