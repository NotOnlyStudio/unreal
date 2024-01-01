<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Receiver</title>

    </head>
    <body>
        <div id="app"></div>

        <script
            src="https://code.jquery.com/jquery-2.2.4.js"
            integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="
            crossorigin="anonymous"></script>
        <script type="application/javascript" src="{{ asset('/js/cabinet.js') }}"></script>

        <script type="text/javascript">
        console.log("Start listening")
        window.Echo.private('room.2')
            .listen('.PrivateMessage', function (data) {
                console.log(data)
                document.write(data.message)
            //    $('#app').append('<p>' + e.message + '</p>');
            })
        </script>
    </body>
</html>