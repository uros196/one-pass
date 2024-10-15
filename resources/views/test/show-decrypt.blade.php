<form action="{{ route('decrypt') }}" method="POST">
    @csrf
    <label for="encryption_token">Encryption token</label>
    <input type="text" name="encryption_token" id="encryption_token" value="{{ request('token') }}" />
    @error('encryption_token')
        {{ $message }}
    @enderror
    <label for="data">Decrypt data</label>
    <input type="text" name="data" id="data" value="{{ request('encrypted') }}" />
    <button type="submit">Decrypt</button>
</form>
