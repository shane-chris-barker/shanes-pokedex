{{Form::open(['route' => 'pokedex.search'])}}
<div class="col-md-10 offset-md-1 col-12">
    <div class="form-check form-check-inline">
        {{Form::radio('search','name', 'checked', ['class' => 'form-check-input'])}}
        {{Form::label('name', 'Search for a Pokemon by name or ID',['class' => 'form-check-label'] )}}
    </div>
    <div class="form-check form-check-inline">
        {{Form::radio('search', 'move', null, ['class' => 'form-check-input'])}}
        {{Form::label('move', 'Search for a move by name or ID',['class' => 'form-check-label'] )}}
    </div>
    <div class="form-check form-check-inline">
        {{Form::radio('search', 'item', null, ['class' => 'form-check-input'])}}
        {{Form::label('item', 'Search for an item by name or ID', ['class' => 'form-check-label'])}}
    </div>
    <div class="form-check form-check-inline">
        {{Form::radio('search', 'location', null, ['class' => 'form-check-input'])}}
        {{Form::label('item', 'Search for a location by name or ID', ['class' => 'form-check-label'])}}
    </div>
</div>

<div class="row form-group">
    {{Form::label("search_param", "Enter your search term:",
        [
            'class' => 'col-md-10 offset-md-1 col-12 text-center form-label'
        ]
    )}}
    <div class="col-md-10 offset-md-1 col-12">
        {{Form::text('search_param', null,
            [
                'class' => 'form-control',
                'required' => 'required',
                'placeholder' => 'E.G Pikachu or Fire Blast'
            ]
        )}}
    </div>
</div>
<div class="row">
    {{Form::submit('Search', ['class' => 'btn btn-primary col-4 offset-4'])}}
</div>
{{Form::close()}}
