<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forbidden</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
            background-color: #f2f2f2;
        }
        h1 {
            color: #d9534f;
        }
    </style>
</head>
<body>
    <h1>403 Forbidden</h1>
    <p>You are not authorized to perform this action. You are not the owner.</p>
    <p><a href="{{ url()->previous() }}">Go back</a></p>
</body>
</html>
