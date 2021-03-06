<div class="card mb-10">
    <div class="card-header">
        <h3 class="text-center">Pokedex information for
            <strong>{{ucfirst(str_replace('-', ' ', ($data->main->name)))}}
            </strong>
        </h3>
        @if($cachedResult !== false)
            <p class="text-center">(This result is cached from a previous seach)</p>
        @endif
    </div>
    <div class="card-body">
        <div class="row">
            <p class="text-center col-12">Move ID: <strong>{{$data->main->id}}</strong></p>
            @foreach($data->main->flavor_text_entries as $moveDescription)
                @if($moveDescription->language->name === 'en')
                    <h3 class="text-center col-12">Move Description:</h3>
                    <p class="text-center col-12"><strong>{{$moveDescription->flavor_text}}</strong></p>
                    @break
                @endif
            @endforeach
        </div>
        @if(count($data->main->effect_entries) > 0)
            @foreach($data->main->effect_entries as $moveEffect)
                <div class="row">
                    <h3 class="text-center col-12">Additional Information:</h3>
                    @if($moveEffect->language->name === 'en')
                        <p class="col-12 text-center">
                            {{str_replace('$effect_chance', $data->main->effect_chance, $moveEffect->effect)}}
                        </p>
                    @endif
                </div>
            @endforeach
            <hr>
        @endif
        @if(isset($data->main->meta->ailment))
            <div class="row">
                <h3 class="text-center col-12">Ailment Inflicted:</h3>
                <p class="text-center col-12">
                    {{ucfirst($data->main->meta->ailment->name)}}
                </p>
            </div>
        @endif
        <hr>
        <div class="row">
            <h3 class="text-center col-12">Move Type:</h3>
                <span class="badge {{$data->main->type->name}}-type col-6 offset-3 mt-1">
                    {{ucfirst($data->main->type->name)}}
                </span>
            </div>
        <hr>
        <div class="row">
            <h3 class="text-center col-12">
                Stats:
            </h3>
            <p class="col-6 text-center">
                <strong>Accuracy:</strong> {{$data->main->accuracy}}
            </p>
            <p class="col-6 text-center">
                <strong>Power:</strong> {{$data->main->power}}
            </p>
        </div>
        <hr>
    </div>
</div>
