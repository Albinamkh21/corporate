@extends(env('THEME').'.layouts.site')

@section('navigation')
    {!! $navigation !!}
@endsection

@section('content')
    {!! $content !!}
@endsection


@section('sidebar')
    {!! $sidebar_right  or '' !!}
@endsection

@section('footer')
    {!! $footer !!}
@endsection