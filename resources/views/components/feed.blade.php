@if (count($feedItems))
<ol class="statuses">
    @foreach ($feedItems as $status) 
        @include('statuses.status', ['user' => $status->user]) 
    @endforeach 

    {!! $feedItems->render() !!}
</ol>
@endif