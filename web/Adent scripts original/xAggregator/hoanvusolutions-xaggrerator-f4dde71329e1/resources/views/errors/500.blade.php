@extends('frontend.layouts.main')
@section('header')
@inject('Language', 'App\Services\LanguageService')
<?php $member=Session::get('sess_member'); ?>
@if($member)
<header class="header-page">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{URL('/'.$lang.'/'.trans('routes.HOME'))}}"><span class="text-gray">EVENT</span>  <span class="text-orange">QUICKLY</span> </a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">

                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        {{$Language->getList(Request::segment(1))}}
                    </li>
                    <li >
                        <a @if(Request::segment(2)==trans('routes.HOW_IT_WORKS')) class="active" @endif href="{{URL('/'.$lang.'/'.trans('routes.HOW_IT_WORKS'))}}">{{trans('home.HOW_IT_WORKS')}}</a>
                    </li>
                    <li>
                        <a  @if(Request::segment(2)==trans('routes.EVENTS')) class="active" @endif href="{{URL('/'.$lang.'/'.trans('routes.EVENTS'))}}">{{trans('home.BROWSE_EVENT')}}</a>
                    </li>
                    <li>
                        <a  @if(Request::segment(2)==trans('routes.SPONSORS')) class="active" @endif href="{{URL('/'.$lang.'/'.trans('routes.SPONSORS'))}}">{{trans('home.SPONSORS')}}</a>
                    </li>
                    <li>
                        <a  @if(Request::segment(2)==trans('routes.CONTACTS_US')) class="active" @endif href="{{URL('/'.$lang.'/'.trans('routes.CONTACTS_US'))}}">{{trans('home.CONTACT_US')}}</a>
                    </li>
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="dLabel" class="profile-top text-profile"> <img src="{{URL('public/upload/avatar/'.$member->image)}}"> {{$member->first_name}} {{$member->last_name}} <i class="icon icon-angle-down"></i> </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li>
                                <a href="{{URL('/'.$lang.'/'.trans('routes.EVENTS').'/'.trans('routes.MY_EVENTS'))}}">My Events</a>
                            </li>
                            <li>
                                <a href="{{URL('/'.$lang.'/'.trans('routes.ACCOUNT_SETTINGS'))}}">Account Settings</a>
                            </li>
                            <li>
                                <a href="{{URL('/'.$lang.'/'.trans('routes.EVENTS').'/'.trans('routes.MY_TICKET'))}}">My Tickets</a>
                            </li>
                            <li>
                                <a href="{{URL('/'.$lang.'/'.trans('routes.EVENTS').'/'.trans('routes.MY_FAVORITE'))}}">Favourite</a>
                            </li>
                            <li>
                                <a href="{{URL('/'.$lang.'/'.trans('routes.CONTACTS'))}}">{{trans('home.CONTACT')}}</a>
                            </li>
                            <li>
                                <a href="{{URL('/'.$lang.'/'.trans('routes.LOGOUT'))}}">{{trans('home.LOGOUT')}}</a>
                            </li>
                        </ul>
                    </li>
                    <li><a href="{{URL('/'.$lang.'/'.trans('routes.EVENTS').'/'.trans('routes.CREATE_STEP1'))}}" class="btn btn-danger">{{trans('home.CREATE_EVENT')}}</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>
</header>
@else
<header class="header-page">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{URL('/'.$lang.'/'.trans('routes.HOME'))}}"><span class="text-gray">EVENT</span>  <span class="text-orange">QUICKLY</span> </a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">

                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        {{$Language->getList(Request::segment(1))}}
                    </li>
                    <li>
                        <a href="{{URL('/'.$lang.'/'.trans('routes.HOW_IT_WORKS'))}}">{{trans('home.HOW_IT_WORKS')}}</a>
                    </li>

                    <li>
                        <a href="{{URL('/'.$lang.'/'.trans('routes.EVENTS'))}}">{{trans('home.BROWSE_EVENT')}}</a>
                    </li>
                    <li>
                        <a href="{{URL('/'.$lang.'/'.trans('routes.SPONSORS'))}}">{{trans('home.SPONSORS')}}</a>
                    </li>
                    <li>
                        <a href="{{URL('/'.$lang.'/'.trans('routes.CONTACTS_US'))}}">{{trans('home.CONTACT_US')}}</a>
                    </li>
                    <li>
                        <a href="{{URL('/'.$lang.'/'.trans('routes.REGISTER'))}}">{{trans('home.SIGN_UP')}}</a>
                    </li>
                    <li>
                        <a href="{{URL('/'.$lang.'/'.trans('routes.LOGIN'))}}">{{trans('home.LOGIN')}}</a>
                    </li>

                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>
</header>
@endif
@endsection
@section('content')
 <style>



            .title {
                font-size: 70px;
                margin-bottom: 20px;

                font-weight:bold;
            }
            .color-red{
                 color: #E6200C;
            }
            h1{margin-top: 0}
        </style>
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-9">
                <div class="panel-group panel-faq">
                    <div class="panel panel-default">
                        <h3 class="text-center title color-red">500</h3>
                        <div class="panel-body">
                          <div class="content text-center" style="margin-bottom:20px">
                            <div class="color-red" style="font-size:40px">  Something Went Wrong </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                @inject('featured', 'App\Services\EventsService')
                {{ $featured->getFeatured(Request::segment(1))}}
            </div>
        </div>
    </div>
</div>
@endsection
