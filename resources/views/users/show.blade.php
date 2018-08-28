@extends('layouts.default') 

@section('title', $user->name) 

@section('content')
<div class="row">
    <div class="col-md-offset-2 col-md-8">

        <!-- 用户信息 -->
        <div class="col-md-12">
            <div class="col-md-offset-2 col-md-8">
                <section class="user_info">
                    @include('components.userinfo', ['user' => $user])
                </section>
                <section class="stats">
                    @include('components.count', ['user' => $user])
                </section>
            </div>
        </div>

        <!-- 微博 -->
        <div class="col-md-12">
            @if (Auth::check())
                @include('users.followform')
            @endif

            @if (count($statuses) > 0)
                <ol class="statuses">
                    @foreach ($statuses as $status) 
                        @include('statuses.status') 
                    @endforeach
                </ol>
                {!! $statuses->render() !!} 
            @endif
        </div>
    </div>
</div>
@endsection