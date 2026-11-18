<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Laravel Online Sales</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
        }

        .navbar {
            background: #f44336;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .container {
            padding: 30px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px #ddd;
        }

        button {
            background: white;
            color: #f44336;
            border: none;
            padding: 10px 18px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h2>Laravel Online Sales</h2>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>

    <div class="container">
        <div class="card">
            <h1>Dashboard</h1>

            <h3>Selamat Datang, {{ Auth::user()->name }}!</h3>

            <p>Anda berhasil login ke sistem Laravel Online Sales.</p>
        </div>
    </div>

</body>
</html>
