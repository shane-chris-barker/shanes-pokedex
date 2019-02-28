<div class="card mb-10">
    <div class="card-header">
        <h3 class="text-center">Pokedex information for <strong>{{ucfirst($data->main->name)}}</strong></h3>
        @if($cachedResult !== false)
            <p class="text-center">(This result is cached from a previous search)</p>
        @endif
    </div>
    <div class="row">
        <div class="col-md-6 col-12">
            <p class="text-center">Standard {{ucfirst($data->main->name)}}</p>
            <div class="row">
                <img src="{{$data->main->sprites->front_default}}"
                     alt="{{$data->main->name}} default front"
                     class="col-md-6 col-6 offset-3"
                />
                <img src="{{$data->main->sprites->back_default}}"
                     alt="{{$data->main->name}} default back"
                     class="col-md-6 col-6 offset-3"
                />
            </div>
        </div>
        <div class="col-md-6 col-12">
            <p class="text-center">Shiny {{ucfirst($data->main->name)}}</p>
            <div class="row">
                <img src="{{$data->main->sprites->front_shiny}}"
                     alt="{{$data->main->name}} shiny front"
                     class="col-md-6 img-fluid col-6 offset-3"
                />
                <img src="{{$data->main->sprites->back_shiny}}"
                     alt="{{$data->main->name}} shiny back"
                     class="col-md-6 img-fluid col-6 offset-3"
                />
            </div>
        </div>
    </div>
    <hr>
    <div class="card-body">
        <div class="row">
            <h3 class="text-center col-12">Description:</h3>
            <p class="text-center">
                <strong>
                    @foreach($data->extra->species_info->flavor_text_entries as $description)
                        @if($description->language->name === 'en')
                            {{$description->flavor_text}}
                            @break
                        @endif
                    @endforeach
                </strong>
            </p>
        </div>
        <hr>
        <div class="row">
            <h3 class="text-center col-12">Type(s):</h3>
            @foreach ($data->main->types as $pokemonType)
                <span class="badge {{$pokemonType->type->name}}-type col-6 offset-3 mt-1">
                    {{ucfirst($pokemonType->type->name)}}
                </span>
            @endforeach
        </div>
        <hr>
        <h3 class="text-center">Information:</h3>
        <div class="row">
            <p class="text-center col-12">Pokedex ID: <strong>{{$data->main->id}}</strong></p>
        </div>
        <div class="row">
            <p class="text-center col-12">Pokemon Weight: <strong>{{$data->main->weight}}</strong></p>
        </div>
        <div class="row">
            <p class="text-center col-12">Pokemon Height: <strong>{{$data->main->height}}</strong></p>
        </div>
        <div class="row">
            <p class="text-center col-12">Base Experience: <strong>{{$data->main->base_experience}}</strong></p>
        </div>
        <hr>
        <div class="row">
            <h2 class="text-center col-12">
                Evolution Chain
            </h2>
            @if($data->extra->evolution_info)
            <div class="row">
                @foreach ($data->extra->evolution_info as $evolution)
                    <div class="col-md-4 col-12">
                        <h4 class="col-12 text-center">Evolution Number: {{$loop->iteration}}</h4>
                        <img class="img-fluid col-md-10 offset-md-1 col-6 offset-3" alt='{{$evolution->name}}'
                             src="{{$evolution->sprites->front_default}}"
                        />
                        <p class="col-12 text-center">{{ucfirst($evolution->name)}}</p>
                    </div>
                @endforeach
            </div>
            @else
                <h5 class="text-center col-12">This Pokemon Does Not Evolve</h5>
            @endif
        </div>
        <hr>
        <div class="row">
            <h2 class="text-center col-12">
                Alternate Forms
            </h2>
        </div>
        @if($data->forms->varieties)
            <div class="row">
                @foreach ($data->forms->varieties as $form)
                    <div class="col-12">
                        <h5 class="text-center col-12">
                            <strong>{{ucwords($form->name)}}</strong>
                        </h5>
                        @if ($form->sprites->front_default)
                            <img src="{{$form->sprites->front_default}}"
                                 alt="{{$form->name}} default front"
                                 class="img-fluid col-6 offset-3"
                            />
                        @else
                            <div class="alert alert-danger">
                                <p class="text-center col-12">No Image available</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="row">
                <div class="alert alert-danger col-12">
                    <h5 class="text-center col-12">This Pokemon has no alternate forms</h5>
                </div>
            </div>
        @endif
        <hr>
        <div class="row">
            <h3 class="text-center col-12">
                Stats:
            </h3>
            @foreach($data->main->stats as $pokeStat)
                <p class="col-6 text-center">
                    <strong>{{ucfirst($pokeStat->stat->name)}}</strong> : {{$pokeStat->base_stat}}
                </p>
            @endforeach
        </div>
    </div>
</div>
