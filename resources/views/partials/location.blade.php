<div class="card mb-10">
    <div class="card-header">
        <h3 class="text-center">Pokedex information for <strong>{{ucfirst(str_replace('-', ' ', ($data->name)))}}</strong></h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-2 offset-5">
                <img src="{{$data->sprites->default}}" class="img-fluid"/>
            </div>
        </div>

        <div class="row">
            <p class="text-center col-12">Item ID: <strong>{{$data->id}}</strong></p>
            @foreach($data->flavor_text_entries as $itemDescription)
                @if($itemDescription->language->name === 'en')
                    <h3 class="text-center col-12">Item Description:</h3>
                    <p class="text-center col-12"><strong>{{$itemDescription->text}}</strong></p>
                    @break
                @endif
            @endforeach
        </div>
        @if(count($data->effect_entries) > 0)
            @foreach($data->effect_entries as $itemEffect)
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
            <h3 class="text-center col-12">Item Catgeory:</h3>
            <p class="text-center col-12">{{ucfirst(str_replace('-', ' ',$data->category->name))}}</p>
        </div>
        <div class="row">
            <h3 class="text-center col-12">This item is:</h3>
            @foreach($data->attributes as $attribute)
                <p class="col-12 text-center"><strong>{{str_replace('-', ' ', ucfirst($attribute->name))}}</strong></p>
                @endforeach
            </ul>
        </div>
        <hr>
    </div>
</div>
