<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Access Denied</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            text-align: center;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        h1 {
            font-size: 3rem;
            color: #dc3545;
        }
        p {
            color: #6c757d;
            font-size: 1.2rem;
        }
        .btn {
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="card bg-white">
        <h1>🚫 Access Restricted</h1>
        <p>You don’t have permission to view this page.</p>
        <a href="{{ url('/') }}" class="btn btn-primary">Go Home</a>
    </div>
</body>
</html>
