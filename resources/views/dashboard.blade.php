@extends('layouts.admin')

@section('container-class')
    container
@endsection

@section('body-class')
    col-md-12
@endsection

@section('admin-title')
    <span><a class="btn btn-link p-0" href="{{ route('dashboard') }}">Dashboard</a>/Links</span>
@endsection

@section('admin-body')
    <strong>---Links---</strong>
    <form method="POST" action="{{ route('dashboard.link.add') }}" class="row border p-3 w-50 mb-2">
        {{ csrf_field() }}
        <label for="link">Link:</label>
        <input id="link" name="link" type="text" placeholder="https://google.com" class="col-md-12" required>
        <label class="mt-2" for="success_content">Content to check:</label>
        <textarea id="success_content" name="success_content" type="text" placeholder="hello world" class="col-md-12" required></textarea>
        <input type="submit" value="Add" class="col-md-2 mt-3">
    </form>
    <div class="row border-bottom border-top bg-light">
        <div class="col-md-4 border-start">Link</div>
        <div class="col-md-4 border-start">Content to check</div>
        <div class="col-md-2 border-start">Last check</div>
        <div class="col-md-1 border-start"></div>
        <div class="col-md-1 border-start border-end"></div>
    </div>
    @foreach($links as $link)
        <div class="row border-bottom align-items-center">
            <div class="p3 col-md-4 border-start p-1">{{ $link['link'] }}</div>
            <div class="p3 col-md-4 border-start p-1">{{ $link['success_content'] }}</div>
            <div class="col-md-2 border-start {{ $link['status'] ? 'text-success' : 'text-danger' }}">
                {{ $link['status'] === null ? '-----------' : '' }}
                {{ $link['status'] === true ? 'AVAILABLE' : '' }}
                {{ $link['status'] === false ? 'DISABLED' : '' }}
            </div>
            <div class="col-md-1 border-start">
                <x-modal
                    modalId="updateLink{{ $link['id'] }}"
                    buttonText="Update"
                    title="Configure notifications"
                    closeText="Close"
                >
                    <form method="POST" action="{{ route('dashboard.link.update') }}" class="row">
                        {{ csrf_field() }}
                        <input name="id" type="number" value="{{ $link['id'] }}" hidden>
                        <label for="link">Link:</label>
                        <input
                            id="link"
                            name="link"
                            type="text"
                            value="{{ $link['link'] }}"
                            placeholder="https://google.com"
                            class="col-md-12"
                            required
                        >
                        <label class="mt-2" for="success_content">Content to check:</label>
                        <textarea
                            id="success_content"
                            name="success_content"
                            type="text"
                            placeholder="hello world"
                            class="col-md-12"
                            required
                        >{{ $link['success_content'] }}</textarea>
                        <input type="submit" value="Update" class="col-md-2 mt-3">
                    </form>
                </x-modal>
            </div>
            <div class="col-md-1 border-start border-end ">
                <form method="POST" action="{{ route('dashboard.link.delete', $link['id']) }}">
                    {{ csrf_field() }}
                    <input name="id" type="number" value="{{ $link['id'] }}" hidden>
                    <input class="btn btn-outline-danger" type="submit" value="Delete">
                </form>
            </div>
        </div>
    @endforeach
@endsection
