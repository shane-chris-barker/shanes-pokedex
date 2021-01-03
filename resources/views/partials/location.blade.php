<div class="card mb-10">
    <div class="card-header">
        <h3 class="text-center">Pokedex information for
            <strong>{{ucfirst(str_replace('-', ' ', ($data->main->name)))}}
            </strong>
        </h3>
        @if($cachedResult !== false)
            <p class="text-center">(This result is cached from a previous search)</p>
        @endif
    </div>
    <div class="card-body">
        <div class="row">
            <p class="text-center col-12">Location ID: <strong>{{$data->main->id}}</strong></p>
            @if (isset($data->extra->region))
                <p class="text-center col-12">Region Found In:
                    <strong>{{ucfirst(str_replace('-', ' ',$data->extra->region->name))}}</strong>
                </p>
            @endif
        </div>
        <hr>
        @if (isset($data->extra->area))
            <p class="text-center col-12">Area Found In:
                <strong>{{ucfirst(str_replace('-', ' ',$data->extra->area->name))}}</strong>
            </p>
            <div class="row">
                <p class="text-center col-12"><strong>Pokemon Found In This Area:</strong></p>
            </div>
            @foreach ($data->extra->pokemonEncounters as $pokemon)
                <div class="row">
                    <p class="text-center col-12">
                        Name: <strong>{{ ucfirst($pokemon->name) }}</strong>
                    </p>
                    <p class="text-center col-12">
                        <strong>{{ ucfirst($pokemon->name) }}</strong> can only be found in this location when playing
                        the following following versions:
                    </p>
                    @foreach ($pokemon->versions as $version)
                        <p class="text-center col-12">
                            <strong>{{ ucfirst($version->versionName) }}</strong>
                        </p>
                    @endforeach
                </div>
                <hr>
            @endforeach
        @endif
        <hr>
    </div>
</div>
