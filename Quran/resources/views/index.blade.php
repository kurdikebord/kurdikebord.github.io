@extends('layouts.app')

@section('title', 'Al-Quran Digital')

@section('content')
<div class="row">
    @foreach ($responseBody->data as $item)
    <div class="card mx-auto my-3" style="width: 20rem;">
        <div class="card-body">
            <h5 class="card-title">{{ $item->name->transliteration->id }}</h5>
            <h6 class="card-subtitle mb-2 text-muted">{{ $item->revelation->arab }} ({{ $item->revelation->id }})</h6>
            <p class="card-text">{{ Str::substr($item->tafsir->id, 0, 100) . '...' }}</p>
            <a href="{{ '/surah/' . $loop->iteration }}" class="card-link" style="text-decoration: none">Baca</a>
        </div>
    </div>
    @endforeach
</div>
@endsection