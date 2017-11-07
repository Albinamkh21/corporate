@extends(env('THEME').'.layouts.site')

@section('navigation')
    {!! $navigation !!}
@endsection

@section('content')
    {!! $content !!}
@endsection


@section('sidebar')
    {!! $sidebar_left  or '' !!}
@endsection

@section('footer')
    {!! $footer !!}
@endsection