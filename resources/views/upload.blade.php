<form action="{{ route('upload.resume') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="resume" required>
    <button type="submit">Upload Resume</button>
</form>
