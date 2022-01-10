@extends('layouts.app')

@section('title', $surah)

@section('content')
<h1 class="text-center mt-4">{{ $surah }}</h1>
<div class="row">
    <ul class="list-group my-3 mx-auto">
        @foreach ($responseBody->data->verses as $item)
        <li class="list-group-item"><p class="text-end" style="line-height: 2em"><h3 class="text-end" style="line-height: 1.5em">{{ $item->text->arab }}</h3> <br> "{{ $item->translation->id }}"</p></li>
        @endforeach
    </ul>
</div>
@endsection