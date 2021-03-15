@extends('layouts.admin')

@section('title', 'Responder Proposta Legislativa')

@section('content')

    <div class="row">
        <div class="col-md-12">

            @include('partials.error')

            <div class="panel panel-default">
                <div class="panel-heading text-center"><strong>Responder Proposta Legislativa</strong></div>
                <div class="panel-body">

                    @include('admin.proposals.partials.table')

                    {{ Form::model($proposal, [
                    'method' => 'PATCH',
                    'route' => ['admin.proposal.updateResponse', $proposal->id],
                    'class' => 'form'
                    ]) }}

                    <div class="form-group">
                        {{ Form::label('Resposta') }}
                        {{ Form::textarea('response', null,
                            array('class'=>'textarea',
                                  'name'=>'response',
                                  'placeholder'=>'Responder Proposta')) }}
                    </div>

                    <div class="pull-left botao">

                            <div class="form-group">

                                @if($proposal->state == App\Enums\ProposalState::NotModerated)
                                    {{ Form::submit('Aprovar!',
                                    array('name' => 'approvalBtn', 'class'=>'btn btn-primary botao','dusk'=>'approve')) }}
                                    {{ Form::submit('Desaprovar',
                                    array('name' => 'disapprovalBtn', 'class'=>'btn btn-danger botao')) }}
                                @elseif($proposal->state == App\Enums\ProposalState::Approved || $proposal->state == App\Enums\ProposalState::Supported)
                                    {{ Form::submit('Remover aprovação',
                                    array('name' => 'disapprovalBtn', 'class'=>'btn btn-danger botao')) }}
                                @elseif($proposal->state == App\Enums\ProposalState::Disapproved)
                                    {{ Form::submit('Aprovar!',
                                    array('name' => 'approvalBtn', 'class'=>'btn btn-primary botao','dusk'=>'approve')) }}
                                    {{ Form::submit('Alterar reposta',
                                    array('name' => 'disapprovalBtn', 'class'=>'btn btn-danger botao')) }}

                                @endif
                                </div>

                    </div>

                    {{ Form::close() }}

                </div>
            </div>
        </div>
    </div>

@stop
