@extends('builder.layout')

@section('content')
    <meta name="content-id" content="{{ $contentId }}">
    <div class="p-2">
        <div class="text-2xl m-1">Create creature</div>
        <form class="flex flex-col">
            <input class="m-3 p-1" type="text" placeholder="name">
            <select class="m-3 p-1">
                <option>trait</option>
            </select>
            <div class="flex justify-between">
                <input class="w-1/2 m-3 p-1" type="number" placeholder="level">
                <input class="w-1/2 m-3 p-1" type="number" placeholder="HP">
            </div>
            <div class="flex justify-between">
                <input class="w-1/2 m-3 p-1" type="number" placeholder="AC">
                <input class="w-1/2 m-3 p-1" type="number" placeholder="fortitude">
            </div>
            <div class="flex justify-between">
                <input class="w-1/2 m-3 p-1" type="number" placeholder="reflex">
                <input class="w-1/2 m-3 p-1" type="number" placeholder="will">
            </div>
        </form>
    </div>
@endsection