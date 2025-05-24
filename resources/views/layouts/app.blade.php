<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sistema')</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        header {
            background-color: #004aad;
            color: white;
            padding: 1rem;
            text-align: center;
        }
        main {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
        }
        .modules-container {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            justify-content: center;
        }
        .module-card {
            background-color: lightblue;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 150px;
            height: 150px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            font-weight: bold;
            color: #004aad;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .module-card:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <header>
        <h1>Sistema Para Pagamentos Simplificados</h1>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>
