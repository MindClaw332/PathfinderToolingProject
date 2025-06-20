@include('partials_mich.header')
<div class="flex items-center">
    <div class="inline-block mx-auto border rounded-xl border-accent text-highlight p-5 bg-secondary">
        <h3>Didn't receive your email? You can try again here!</h3>
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="text-accent underline hover:opacity-60">
                Resend Verification Email
            </button>
        </form>
    </div>
</div>