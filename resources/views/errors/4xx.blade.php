@extends('layouts.public')

@section('content')
    @include('errors.partials.hero', ['code' => $exception->getStatusCode()])
@endsection
