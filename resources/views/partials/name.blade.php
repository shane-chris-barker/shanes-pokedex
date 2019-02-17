<div class="card mb-10">
    <div class="card-header">
        <h3 class="text-center">Pokedex information for <strong>{{ucfirst($data->name)}}</strong></h3>
        @if($cachedResult !== false)
            <p class="text-center">(This result is cached from a previous search)</p>
        @endif
    </div>
    <div class="row">
        <div class="col-6">
            <p class="text-center">Standard {{ucfirst($data->name)}}</p>
            <div class="row">
                <img src="{{$data->sprites->front_default}}" class="col-6"/>
                <img src="{{$data->sprites->back_default}}" class="col-6"/>
            </div>
        </div>
        <div class="col-6">
            <p class="text-center">Shiny {{ucfirst($data->name)}}</p>
            <div class="row">
                <img src="{{$data->sprites->front_shiny}}" class="col-6"/>
                <img src="{{$data->sprites->back_shiny}}" class="col-6"/>
            </div>
        </div>
    </div>
    <hr>
    <div class="card-body">
        <h3 class="text-center">Information:</h3>
        <div class="row">
            <p class="text-center col-12">Pokedex ID: <strong>{{$data->id}}</strong></p>
        </div>
        <div class="row">
            <p class="text-center col-12">Pokemon Weight: <strong>{{$data->weight}}</strong></p>
        </div>
        <div class="row">
            <p class="text-center col-12">Pokemon Height: <strong>{{$data->height}}</strong></p>
        </div>
        <div class="row">
            <p class="text-center col-12">Base Experience: <strong>{{$data->base_experience}}</strong></p>
        </div>
        <hr>
        <div class="row">
            <h3 class="text-center col-12">Type(s):</h3>
            @foreach ($data->types as $pokemonType)
                <span class="badge {{$pokemonType->type->name}}-type col-6 offset-3 mt-1">{{ucfirst($pokemonType->type->name)}}</span>
            @endforeach
        </div>
        <hr>
        <div class="row">
            <h3 class="text-center col-12">
                Stats:
            </h3>

            @foreach($data->stats as $pokeStat)
                <p class="col-6 text-center">

                    <strong>{{ucfirst($pokeStat->stat->name)}}</strong> : {{$pokeStat->base_stat}}
                </p>
            @endforeach
        </div>
        <hr>
    </div>
</div>
