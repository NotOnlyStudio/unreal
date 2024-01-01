<form enctype="multipart/form-data" action="/api/photo-test" method="POST">
<input type="hidden" name="_token" value="{{ csrf_token() }}" />

    <input type="file" name="image">
    <input type="submit">
</form>