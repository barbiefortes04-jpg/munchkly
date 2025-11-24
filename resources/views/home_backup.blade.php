@extends('layouts.app')

@section('content')
    <h1>Test Page</h1>
    @if($isAuth && $authUser)
        <p>User is logged in: {{ $authUser['name'] ?? 'Unknown' }}</p>
    @else
        <p>User is not logged in</p>
    @endif
@endsection