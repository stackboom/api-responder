# Response Code Reference
@empty($lang)
@foreach ($responders as $name=>$responder)

## Name: `{{ $name }}`
@foreach($responder as $each_lang)
* {{$each_lang['lang']}}

| Name:     | `{{ $each_lang['camel_name'] }}`     |
| ------ | ------ |
| Code:     | `{{ $each_lang['code'] }}`     |
| Message:     | `{{ $each_lang['message'] }}`     |
| CreatedAt:     | `{{ $each_lang['created_at'] }}`     |
{{$each_lang['comment']}}
[{{$each_lang['help']}}]({{$each_lang['help']}})

@endforeach
@endforeach
@else
@foreach ($responders as $name=>$responder)
## `{{ $responder['name'] }}`
| Name:     | `{{ $responder['camel_name'] }}`     |
| ------ | ------ |
| Code:     | `{{ $responder['code'] }}`     |
| Message:     | `{{ $responder['message'] }}`     |
| CreatedAt:     | `{{ $responder['created_at'] }}`     |
{{$responder['comment']}}
[{{$responder['help']}}]({{$responder['help']}})
@endforeach
@endempty
{{--
    ### Description:

    ### Columns:

    | Column | Data Type | Attributes | Default | Description |
    | --- | --- | --- | --- | --- |
    @foreach ($table['columns'] as $column)
        | `{{ $column->name }}` | {{ $column->type }} | {{ $column->attributes->implode(', ') }} | {{ $column->default }} | {{ $column->description }} |
    @endforeach
    @if (count($table['indices']))

        ### Indices:

        | Name | Columns | Type | Description |
        | --- | --- | --- | --- |
        @foreach($table['indices'] as $indices)
            | `{{ $indices->name }}` | {{ $indices->columns->map(function ($column) { return "`{$column}`"; })->implode(', ') }} | {{ $indices->type }} | {{ $indices->description }} |
        @endforeach
    @endif--}}
