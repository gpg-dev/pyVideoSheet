@extends('frontend.layouts.main')

@section('content')
 <div class="container">
            <div class="page-404">
                <img src="{{asset('public/frontend/images/404-not-found.gif')}}">
                <div class="sorry-404"><span>Sorry :(</span> We couldn’t find this page</div>
                <a href="{{URL('/')}}" class="btn btn-danger">Back to homepage</a>
            </div>
        </div>
@endsection
