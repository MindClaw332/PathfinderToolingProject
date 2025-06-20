@include('partials_mich.header')


<div class="text-white flex items-center justify-center flex-col">
    <div class="bg-secondary border border-accent rounded-lg inline-block m-8 p-8">
        @if (session('message'))
            <div class="flex justify-center"><span class="text-white">{{ session('message') }}</span></div>
        @endif
        <div class="flex justify-center">
            <h1 class="text-xl underline text-accent">Login</h1>
        </div>
        <form method="POST" action="{{ route('login.login') }}">
            <div class="text-white flex items-center justify-center flex-col mx-12 my-8">
                @csrf
                <label class="pb-2" for="email">Email Address</label>
                <input name="email"
                    class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent mb-4"
                    id="email" type="email" value="{{ old('email') }}">

                <label class="pb-2" for="password">Password</label>
                <input
                    class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent"
                    type="password" id="password" name="password">
                @error('email')
                    <span class="text-accent mt-4">{{ $message }}</span>
                @enderror
                <span class="p-4">
                    <label class="p-4" for="remember">Remember Me</label>
                    <input class="border border-accent accent-accent" type="checkbox" name="remember" id="remember">

                </span>
                <button class="border rounded-lg bg-accent py-2 px-5 hover:brightness-90 hover:underline mb-4"
                    type="submit">Login</button>

                <span class="text-white">Dont have an account? <a class="text-accent hover:underline"
                        href="{{ route('register.register') }}">Click
                        Here</a></span>
            </div>
        </form>

    </div>
</div>
