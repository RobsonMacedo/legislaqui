@extends('layouts.admin')

@section('title', 'Editar Ideia Legislativa -> Projeto de Lei')

@section('content')

    <div class="row">
        <div class="col-md-12">

            @include('partials.error')

            <div class="panel panel-default">
                <div class="panel-heading text-center"><strong>Editar Ideia Legislativa -> Projeto de Lei</strong></div>
                <div class="panel-body">

                    {{ Form::model($proposal, [
                    'method' => 'PATCH',
                    'route' => ['admin.proposal.updateBillProject', $proposal->id],
                    'class' => 'form'
                    ]) }}

                    <div class="form-group coluna_02">
                        {{ Form::label('Número do Projeto de Lei') }}
                        {{ Form::text('number', null,
                            array('required',
                                  'class'=>'campo',
                                  'dusk'=>'number',
                                  'placeholder'=>'Número do Projeto de Lei')) }}
                    </div>

                    <div class="form-group coluna_02">
                        {{ Form::label('Ano') }}
                        {{ Form::text('year', null,
                            array('required',
                                  'class'=>'campo',
                                  'dusk'=>'year',
                                  'placeholder'=>'Ano')) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('Responsável') }}
                        {{ Form::text('owner', null,
                            array('required',
                                  'class'=>'campo',
                                  'dusk'=>'owner',
                                  'placeholder'=>'Deputado Responsável')) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('Link') }}
                        {{ Form::text('link', null,
                            array('required',
                                  'class'=>'campo',
                                  'dusk'=>'link',
                                  'placeholder'=>'Insira o Link')) }}
                    </div>

                    <div class="form-group">
                        {{ Form::submit('Gravar',
                          array('class'=>'btn btn-primary botao', 'dusk'=>'submit_button-billproject')) }}
                    </div>

                    {{ Form::close() }}

                </div>
            </div>
        </div>
    </div>

@stop
