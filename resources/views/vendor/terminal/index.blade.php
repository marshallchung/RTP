<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terminal</title>
    <link href="{{ asset('vendor/terminal/css/terminal.css') }}" rel="stylesheet" />
</head>

<body>
    <div id="terminal-shell"></div>
    <script src="{{ asset('vendor/terminal/js/terminal.js') }}"></script>
    <script>
        (function() {
        new Terminal("#terminal-shell", {!! $options !!});
    })();
    </script>
</body>

</html>