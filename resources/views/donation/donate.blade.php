@include('partials_mich.header')
<div class="flex justify-center">
    <div
        class="text-highlight border rounded-xl border-accent mx-auto bg-secondary flex flex-col items-center justify-center align-middle p-10">
        <h1 class="text-accent text-3xl">Help us keep the lavalamps on and the server running by donating!</h1>
        <form class="mt-10 flex flex-col " action="{{ route('donate.donate') }}" method="POST">
            @csrf
            <div>
                <input class="text-right border rounded-xl border-accent text-accent bg-highlight focus:border-black" type="number" name="amount">
                <label for="amount">amount of money in EUR</label>
            </div>
            <button type="submit" class="border rounded-xl border-accent bg-accent text-bold mt-3 hover:opacity-90 hover:cursor-pointer hover:underline">Donate</button>
        </form>
    </div>
</div>

@include('partials_mich.footer')
