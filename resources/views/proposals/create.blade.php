@extends('layouts.alerj')

@section('title', 'Incluir Proposta Legislativa')

@section('content')
    <div class="propor-ideia">
        <div class="row">
            <div class="col-12">
                @include('partials.error')
                <h1 class=""><i class="fas fa-folder-plus mr-3"></i>Propor Ideia Legislativa</h1>
                <p class="lead">
                    Tão importante quanto escolher, pelo voto, um representante na Assembléia Legislativa é participar propondo ideias, posições e iniciativas ao processo parlamentar em favor da coletividade.
                </p>
                <h3 class="propor-envie mt-2">Envie-nos sua proposta!</h3>
            </div>
        </div>

        {{ Form::open(array('route' => 'proposal.store', 'class' => 'form')) }}


        <div class="row form-group mt-4 pb-2">

            <div class="col-12 col-lg-3">
                {{ Form::label('Título da ideia', null , ['class' => 'bold double']) }}
            </div>
            <div class="col-12 col-lg-9 ">
                {{ Form::text('name', null,
                    array('required',
                          'class'=>'campo form-control',
                          'dusk'=>'name_field',
                          'placeholder'=>'Digite aqui o título da Ideia')) }}
            </div>
        </div>

        {{-- <div class="row form-group pt-2 pb-2">
            <div class="col-12 col-lg-3">
                {{ Form::label('Assuntos', null , ['class' => 'bold double']) }}
            </div>
            <div class="col-12 col-lg-9 ">
                {{ Form::select('subjects', $subjects, null, ['name'=>'subjects[]','data-width'=>'100%','multiple','data-selected-text-format="count"','data-style'=>'form-control', 'data-live-search'=>'true', 'title'=>'Selecione os assuntos...']) }}
            </div>
        </div>

        <div class="row form-group pt-2 pb-2">
            <div class="col-12 col-lg-3">
                {{ Form::label('Problema a ser resolvido', null , ['class' => 'bold double']) }}
            </div>
            <div class="col-12 col-lg-9 ">
                {{ Form::textarea('problem', null,
                    array('required',
                          'class'=>'textarea form-control',
                          'placeholder'=>'Descreva o problema que deseja resolver com esta ideia')) }}
            </div>
        </div> --}}

        <div class="row form-group pt-2 pb-2">

            <div class="col-12 col-lg-3">
                {{ Form::label('Descrição da ideia', null , ['class' => 'bold double']) }}
            </div>
            <div class="col-12 col-lg-9 ">
                {{ Form::textarea('idea_exposition', null,
                    array('required',
                          'class'=>'textarea form-control',
                          'dusk'=>'exposionidea_field',
                          'placeholder'=>'Descreva de forma completa a sua Ideia. Dê todos os detalhes que achar necessário.')) }}
            </div>
        </div>



        <div class="row form-group pt-2 pb-2 text-right">
            <div class="col-12 ">
                {{ Form::submit('Enviar proposta de ideia',
                  array('class'=>'btn btn-primary botao',
                  'dusk'=>'submitbuttonproposal')) }}
            </div>
        </div>
        {{ Form::close() }}


    </div>


@stop
