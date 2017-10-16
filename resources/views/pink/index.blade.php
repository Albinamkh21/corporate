@extends(env('THEME').'.layouts.site')

@section('navigation')
    {!! $navigation !!}
@endsection


@section('slider')
    {!! $slider !!}
@endsection


@section('content')
    {!! $content !!}
@endsection


@section('sidebar')
    {!! $sidebar_right !!}
@endsection

@section('footer')
    {!! $footer !!}
@endsection