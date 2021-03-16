@extends('layouts.alerj')

@section('title', 'Editar Proposta Legislativa')

@section('content')

    <div class="row">
        <div class="col-12 ">
            @include('partials.error')

            <h1 class=""><i class="fas fa-edit mr-3"></i> Editar Ideia Legislativa</h1>

        </div>
    </div>

    <div class="row propor-ideia">
        <div class="col-12">

            {{ Form::model($proposal, [
            'method' => 'PATCH',
            'route' => ['proposal.update', $proposal->id],
            'class' => 'form'
            ]) }}

            {{-- Form::open(array('route' => 'proposal.update', 'class' => 'form')) --}}

            <div class="row form-group mt-4 pb-2">

                <div class="col-12 col-lg-3">
                    {{ Form::label('Nome da Proposta') }}
                </div>
                <div class="col-12 col-lg-9">
                    {{ Form::text('name', null,
                        array('required',
                              'class'=>'campo form-control',
                              'dusk'=>"name-edit_field",
                              'placeholder'=>'Nome')) }}
                </div>
            </div>

            {{-- <div class="row form-group pt-2 pb-2">
                <div class="col-12 col-lg-3">
                    {{ Form::label('Assuntos', null , ['class' => 'bold double']) }}
                </div>
                <div class="col-12 col-lg-9 ">
                    {{ Form::select('subjects', $subjects, $proposal->subject_ids_array, ['name'=>'subjects[]','data-width'=>'100%','multiple','data-selected-text-format="count"','data-style'=>'form-control', 'data-live-search'=>'true', 'title'=>'Selecione os assuntos...']) }}
                </div>
            </div>

            <div class="row form-group">

                <div class="col-12 col-lg-3">
                    {{ Form::label('Problema') }}
                </div>
                <div class="col-12 col-lg-9">
                    {{ Form::textarea('problem', null,
                        array('required',
                              'class'=>'textarea form-control',
                              'dusk'=>"problem-edit_field",
                              'placeholder'=>'Descreva o Problema')) }}
                </div>
            </div>
 --}}
            <div class="row form-group">

                <div class="col-12 col-lg-3">
                    {{ Form::label('Exposição da Ideia') }}
                </div>
                <div class="col-12 col-lg-9">
                    {{ Form::textarea('idea_exposition', null,
                        array('required',
                              'class'=>'textarea form-control',
                              'dusk'=>"exposionidea-edit_field",
                              'placeholder'=>'Descreva sua Ideia')) }}
                </div>
            </div>

            <div class="row form-group text-right">

                <div class="col-12 col-lg-3">

                </div>
                <div class="col-12 col-lg-9">
                    {{ Form::submit('Gravar',
                      array('class'=>'btn btn-primary botao',
                      'dusk'=>'savebutton')) }}
                </div>
            </div>

            {{ Form::close() }}

        </div>
    </div>


@stop
