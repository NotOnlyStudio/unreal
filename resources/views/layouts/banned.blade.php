<!DOCTYPE html>
<html lang="en">
<?php
$user = Auth::user();
$user = $user !== null ? $user : 0;
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <title>Was banned</title>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <script type="text/javascript">
            function googleTranslateElementInit() {
                new google.translate.TranslateElement(
                    { pageLanguage: 'en', autoDisplay: false },
                    'app'
                );
            }
        </script>
        <script
            type="text/javascript"
            src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"
        ></script>
        <script
            type="text/javascript"
            src="https://cdn.jsdelivr.net/gh/lewis-kori/vue-google-translate@main/src/utils/translatorRegex.js"
        ></script>
</head>
<body>
    <div style="height: 100vh" class="container-fluid bg-dark d-flex flex-column align-items-center justify-content-center">
        <div class="alert alert-danger">
            <h1>Your account was banned.</h1>
        </div>
    </div>
</body>
</html>
