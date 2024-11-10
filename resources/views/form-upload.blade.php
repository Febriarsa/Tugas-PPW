@extends('auth.layouts')

@section('content')
<form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="photo">
    <button type="submit">Upload</button>
</form>
@endsection
