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
    <div class="w-50">
        <x-link.form action="{{ route('dashboard.link.add') }}" buttonText="Add"></x-link.form>
    </div>
    <div class="row border-bottom border-top bg-light">
        <div class="col-md-8 border-start">Link</div>
        <div class="col-md-2 border-start">Last check</div>
        <div class="col-md-1 border-start"></div>
        <div class="col-md-1 border-start border-end"></div>
    </div>
    @foreach($links as $link)
        <div class="row border-bottom align-items-center">
            <div class="p3 col-md-8 border-start p-1">{{ $link->link }}</div>
            <div class="col-md-2 border-start {{ $link->status === Links::STATUS_AVAILABLE ? 'text-success' : 'text-danger' }}">
                {{ Links::STATUS_LABEL[$link->status] }}
            </div>
            <div class="col-md-1 border-start">
                <x-modal
                    modalId="updateLink{{ $link->id }}"
                    buttonText="Update"
                    title="Configure notifications"
                    closeText="Close"
                >
                    <x-link.form
                        action="{{ route('dashboard.link.update') }}"
                        buttonText="Update"
                        :link="$link"
                    ></x-link.form>
                </x-modal>
            </div>
            <div class="col-md-1 border-start border-end ">
                <form method="POST" action="{{ route('dashboard.link.delete', $link->id) }}">
                    {{ csrf_field() }}
                    <input name="id" type="number" value="{{ $link->id }}" hidden>
                    <input class="btn btn-outline-danger" type="submit" value="Delete">
                </form>
            </div>
        </div>
    @endforeach
@endsection
