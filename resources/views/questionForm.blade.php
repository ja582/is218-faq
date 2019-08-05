@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Create Question</div>
                    {!! Form::open(['action' => 'QuestionController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                    <div class="card-body">
                        @if($edit === FALSE)
                            {!! Form::model($question, ['action' => 'QuestionController@store']) !!}
                        @else()
                            {!! Form::model($question, ['route' => ['questions.update', $question->id], 'method' => 'patch']) !!}
                        @endif
                         <div class="form-group">
                             {!! Form::label('title', 'title') !!}
                             {!! Form::text('title', $question->title, ['class' => 'form-control','required' => 'required']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('body', 'Body') !!}
                            {!! Form::text('body', $question->body, ['class' => 'form-control','required' => 'required']) !!}
                        </div>
                        <div class ="form-group">
                            {!! Form::file('image'); !!}
                        </div>
                        <button class="btn btn-success float-right" value="submit" type="submit" id="submit">Save</button>
                        {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection