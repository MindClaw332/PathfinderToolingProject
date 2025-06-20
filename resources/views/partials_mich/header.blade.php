<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/builder/builder.js'])
</head>

{{-- start the body which ends in footer --}}

<body class="bg-primary">
    <header class="bg-primary">
        <div class="bg-secondary m-4 border border-accent rounded-lg block">
            {{-- list of items in a flex --}}
            <ul class="flex justify-between m-6 text-highlight flex-wrap gap-4 items-center">
                <li>
                    <a class="hover:underline hover:text-accent" href="{{ route('home') }}"><img
                            src="{{ asset('storage/images/logo_masterstoolkit.png') }}" alt="website-logo"
                            class="h-15 w-auto"></a>
                </li>
                <li>
                    <a class="hover:underline hover:text-accent" href="">Contact</a>
                </li>
                <li>
                    <a class="hover:underline hover:text-accent" href="{{ route('builder.encounter') }}">Encounter
                        Builder</a>
                </li>
                <li>
                    <a class="hover:underline hover:text-accent" href="{{ route('combat') }}">Combat manager</a>
                </li>
                <li>
                    <a class="hover:underline hover:text-accent" href="">About us</a>
                </li>

                {{-- if the user is logged in and an admin show the admin panel link  --}}
                @if (Auth::check() && Auth::user()->is_admin)
                    <li>
                        <a class="hover:underline hover:text-accent" href="/admin">Admin Panel</a>
                    </li>
                @endif
                {{-- if the user is not logged in show theme button and login button  --}}
                @if (!Auth::check())
                    <li>
                        <button class="border rounded-lg bg-accent py-2 px-5 hover:brightness-90 hover:underline"
                            onclick="toggleTheme()">Toggle Theme</button>
                        <a class="border rounded-lg bg-accent py-2 px-5 hover:brightness-90 hover:underline"
                            href="{{ route('login.login') }}" role="button">Log In</a>
                    </li>
                @endif
                {{-- if they are logged in show a logout button  --}}
                @if (Auth::check())
                    <li class="flex gap-2">
                        <form action="{{ route('login.logout') }}" method="POST">
                            @csrf
                            <button class="border rounded-lg bg-accent py-2 px-5 hover:brightness-90 hover:underline"
                                onclick="toggleTheme()">Toggle Theme</button>
                            <button type="submit"
                                class="border rounded-lg bg-accent py-2 px-5 hover:brightness-90 hover:underline">Logout
                            </button>
                        </form>
                    </li>
                @endif

            </ul>
        </div>
    </header>
