@extends('layouts.main')
@section('content')
    <div class="container-fluid" id="intro">
        <div class="row">
            <div class="col-md-2 offset-md-5 col-8 offset-2">
                <img src="{{asset('img/logo.png')}}" class="img-fluid"/>
            </div>
        </div>
    </div>
    <div class="container-fluid">
            <h2 class="text-center mt-5">
            Welcome to Shane's Pokedex!
        </h2>
        <p class="text-center">
            A fun little PHP and Laravel based Pokedex.
            <br>
            Consumes the API kindly created and provided by <a href="https://pokeapi.co/" target="_blank">PokeApi</a>
        </p>
        <div class="col-md-4 offset-md-4 col-12">
            @if($errors->any())
                <div class="alert alert-danger">
                    {{$errors->first()}}
                </div>
            @endif
            @include('partials.pokedex-form')
        </div>
        @if ($hasResults === true)
            <div class="p-10">
                <div class="col-md-4 offset-md-4 col-12 mt-5 mt-10" id="results">
                    {!! $results !!}
                </div>
            </div>
        @endif
    </div>
@endsection
