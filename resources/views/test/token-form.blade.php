<form action="{{ route('create-token') }}" method="POST">
    @csrf
    <input type="password" name="password" />
    @error('password')
        {{ $message }}
    @enderror
    <button type="submit">Create</button>
</form>
