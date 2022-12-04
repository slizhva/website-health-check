@extends('layouts.admin')

@section('container-class')
    container
@endsection

@section('body-class')
    col-md-10
@endsection

@section('admin-title')
    <span><a class="btn btn-link p-0" href="{{ route('dashboard') }}">Dashboard</a>/Links</span>
@endsection

@section('admin-body')
    <strong>---Links---</strong>
    <form method="POST" action="{{ route('dashboard.link.add') }}" class="row">
        {{ csrf_field() }}
        <input name="link" type="text" value="" placeholder="https://google.com" class="col-md-10" required>
        <input type="submit" value="Add" class="col-md-2">
    </form>
    <div class="row border-bottom border-top bg-light">
        <div class="col-md-9 border-start">Link</div>
        <div class="col-md-2 border-start border-end">Last check</div>
        <div class="col-md-1 border-start border-end">Action</div>
    </div>
    @foreach($links as $link)
        <div class="row border-bottom align-items-center">
            <div class="p3 col-md-9 border-start p-1">{{ $link['link'] }}</div>
            <div class="col-md-2 border-start {{ $link['status'] ? 'text-success' : 'text-danger' }}">
                {{ $link['status'] === null ? '-----------' : '' }}
                {{ $link['status'] === true ? 'AVAILABLE' : '' }}
                {{ $link['status'] === false ? 'DISABLED' : '' }}
            </div>
            <div class="col-md-1 border-start border-end ">
                <form method="POST" action="{{ route('dashboard.link.delete', $link['id']) }}">
                    {{ csrf_field() }}
                    <input name="id" type="number" value="{{ $link['id'] }}" hidden>
                    <input class="pt-0 pb-0" type="submit" value="Delete">
                </form>
            </div>
        </div>
    @endforeach
@endsection
