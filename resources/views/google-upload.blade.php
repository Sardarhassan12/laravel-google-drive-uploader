@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

<form action="/google/upload" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit">Upload</button>
</form>
