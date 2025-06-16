@include('partials_mich.header')


<div class="text-white flex items-center justify-center flex-col">
    <div class="bg-secondary border border-accent rounded-lg inline-block m-8 p-8">
        <div class="flex justify-center">
            <h1 class="text-xl underline text-accent">Register</h1>
        </div>
        <form method="POST" action="{{ route('register.register') }}">
            <div class="text-white flex items-center justify-center flex-col mx-12 my-8">
                @csrf

                <label class="pb-2" for="name">Full Name</label>
                <input name="name"
                    class="my-1 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent mb-4"
                    id="name" type="text" value="{{ old('name') }}">
                @error('name')
                    <span class="text-accent mx-1">{{ $message }}</span>
                @enderror
                <label class="pb-2" for="email">Email Address</label>
                <input name="email"
                    class="my-1 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent mb-4"
                    id="email" type="email" value="{{ old('email') }}">
                @error('email')
                    <span class="text-accent mx-1">{{ $message }}</span>
                @enderror
                <label class="pb2" for="password">Password</label>
                <input
                    class="my-1 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent mb-4"
                    type="password" id="password" name="password">
                <label class="pb-2" for="password">Confirm Password</label>
                <input
                    class="my-1 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent"
                    type="password" id="password" name="password_confirmation">
                @error('password')
                    <span class="text-accent mx-4">{{ $message }}</span>
                @enderror
                <button class="border rounded-lg bg-accent py-2 px-5 hover:brightness-90 hover:underline mt-4"
                    type="submit">Register</button>
            </div>
        </form>

    </div>
</div>
