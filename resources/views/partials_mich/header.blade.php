<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/builder/builder.js'])
</head>

<body class="bg-primary">
    <header>
        <div class="bg-secondary m-4 border border-accent rounded-lg block">
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


                @if (Auth::check() && Auth::user()->is_admin)
                    <li>
                        <a class="hover:underline hover:text-accent" href="/admin">Admin Panel</a>
                    </li>
                @endif
                @if (!Auth::check())
                    <li>
                        <button class="border rounded-lg bg-accent py-2 px-5 hover:brightness-90 hover:underline"
                            onclick="toggleTheme()">Toggle Theme</button>
                        <a class="border rounded-lg bg-accent py-2 px-5 hover:brightness-90 hover:underline"
                            href="{{ route('login.login') }}" role="button">Log In</a>
                    </li>
                @endif
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
