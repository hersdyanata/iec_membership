@extends('layouts.app')
@section('header')
    {{ $title }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">{{ $title }}</h5>
                </div>

                <div class="card-body">
                    <h5>Hi, selamat datang kembali Tuan {{ Auth::user()->name }} 🙇🏻‍♂️</h5>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('page_js')

@endsection