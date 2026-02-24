<!DOCTYPE html>
<html lang="en">

<head>
    <title>Print Table</title>
    <meta charset="UTF-8">
    <meta name=description content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <style>
        body {
            margin: 20px
        }
    </style>
</head>

<body>
    <table class="table table-bordered table-condensed table-striped">
        @foreach($data as $row)
        @if ($row == reset($data))
        <tr>
            @foreach($row as $key => $value)
            <th class="p-2 font-normal text-left border-r last:border-r-0">{!! $key !!}</th>
            @endforeach
        </tr>
        @endif
        <tr>
            @foreach($row as $key => $value)
            @if(is_string($value) || is_numeric($value))
            <td class="p-2 border-r last:border-r-0">{!! $value !!}</td>
            @else
            <td class="p-2 border-r last:border-r-0"></td>
            @endif
            @endforeach
        </tr>
        @endforeach
    </table>
</body>

</html>