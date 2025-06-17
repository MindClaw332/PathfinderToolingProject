<h3>verify your email</h3>
<form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button type="submit" class="text-blue-600 underline">
        Resend Verification Email
    </button>
</form>