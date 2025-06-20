@extends('builder/layout')

@section('content')
    <meta name="content-id" content="{{ $contentId }}">
    <div class="p-2">
        <div class="text-2xl m-1">My creatures</div>
        <div class="divide-y-1 divide-y divide-tertiary m-1">
            <div class="p-2">creature</div>
            <div class="p-2">creature</div>
            <div class="p-2">creature</div>
        </div>
    </div>
@endsection
