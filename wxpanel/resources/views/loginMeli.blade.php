@extends('layouts.base')


@section('main_container')
    @if(isset($url))
    <a href="{{ $url }}">Login Meli</a>
    @endif
    @if(isset($data))
    {{ dd($data) }}
    @endif
@stop	