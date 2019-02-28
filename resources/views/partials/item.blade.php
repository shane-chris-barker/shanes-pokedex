<div class="card mb-10">
    <div class="card-header">
        @if($cachedResult !== false)
            <p class="text-center">(This result is cached from a previous search)</p>
        @endif
        <h3 class="text-center">
            Pokedex information for
            <strong>{{ucfirst(str_replace('-', ' ', ($data->main->name)))}}</strong>
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-2 offset-5">
                <img src="{{$data->main->sprites->default}}" class="img-fluid" alt="{{$data->main->name}}"/>
            </div>
        </div>
        <div class="row">
            <p class="text-center col-12">Item ID: <strong>{{$data->main->id}}</strong></p>
            @foreach($data->main->flavor_text_entries as $itemDescription)
                @if($itemDescription->language->name === 'en')
                    <h3 class="text-center col-12">Item Description:</h3>
                    <p class="text-center col-12"><strong>{{$itemDescription->text}}</strong></p>
                    @break
                @endif
            @endforeach
        </div>
        @if(count($data->main->effect_entries) > 0)
            @foreach($data->main->effect_entries as $itemEffect)
                <div class="row">
                    <h3 class="text-center col-12">Additional Information:</h3>
                    @if($itemEffect->language->name === 'en')
                        <p class="col-12 text-center">
                            {{$itemEffect->effect}}
                        </p>
                    @endif
                </div>
            @endforeach
        @endif
        <hr>
        <div class="row">
            <h3 class="text-center col-12">Item Category:</h3>
            <p class="text-center col-12">{{ucfirst(str_replace('-', ' ',$data->main->category->name))}}</p>
        </div>
        <hr>
        <div class="row">
            <h3 class="text-center col-12">This item is:</h3>
            @foreach($data->main->attributes as $attribute)
                <p class="col-12 text-center">
                    <strong>{{str_replace('-', ' ', ucfirst($attribute->name))}}</strong>
                </p>
            @endforeach
        </div>
        <hr>
    </div>
</div>
