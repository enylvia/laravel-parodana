@extends('layouts.app')

@section('title', 'Import Data')

@section('content')

    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>

    <form action="/import" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="">
            file import:
            <input type="file" name="import-data">
        </label>
        <button type="submit">Upload</button>
    </form>

@endsection