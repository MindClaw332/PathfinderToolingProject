<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-primary">
    <header>
        <div class="bg-secondary m-4 border border-accent rounded-lg block">
            <ul class="flex justify-between m-6 text-white flex-wrap gap-4 items-center">
                <li>
                    <a class="hover:underline hover:text-accent" href="{{ route('home') }}">Home</a>
                </li>
                <li>
                    <a class="hover:underline hover:text-accent" href="">Contact</a>
                </li>
                <li>
                    <a class="hover:underline hover:text-accent" href="{{ route('builder.encounter') }}">Encounter
                        Builder</a>
                </li>
                <li>
                    <a class="hover:underline hover:text-accent" href="">Encounter manager</a>
                </li>
                <li>
                    <a class="hover:underline hover:text-accent" href="">About us</a>
                </li>
                @if (!Auth::check())
                    <li>
                        <a class="hover:underline hover:text-accent" href="{{ route('login.login') }}">Log In</a>
                    </li>
                @endif
                @if (Auth::check())
                    <li>
                        <form action="{{ route('login.logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="border rounded-lg bg-accent py-2 px-5 hover:brightness-90 hover:underline">Logout
                            </button>
                        </form>
                    </li>
                @endif
            </ul>
        </div>
    </header>
