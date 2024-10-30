<form action="{{ route('encrypt') }}" method="POST">
    @csrf
    <label for="encryption_token">Encryption token</label>
    <input type="text" name="encryption_token" id="encryption_token" value="{{ request('token') }}" />
    @error('encryption_token')
        {{ $message }}
    @enderror
    <br>
    <label for="data">Encrypt data</label>
    <input type="text" name="data" id="data">
    <button type="submit">Encrypt</button>
</form>
