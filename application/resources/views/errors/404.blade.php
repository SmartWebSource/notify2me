<html>
<head>
    <title>{{ env('APP_NAME') }} | 404 The Requested URL not found.</title>

    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            color: #B0BEC5;
            display: table;
            font-weight: 100;
            font-family: 'Lato';
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 72px;
            margin-bottom: 40px;
        }

        .back-link {
            font-size: 20px;
            color: #0000ff;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">404 The Requested URL not found.</div>
        <a class="back-link" href="{{ $back_url }}">Back to home</a>
    </div>
</div>
</body>
</html>