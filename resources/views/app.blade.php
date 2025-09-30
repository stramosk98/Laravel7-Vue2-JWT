<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel+Vue') }}</title>
    
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="/">
                    {{ config('app.name', 'Laravel+Vue') }}
                </a>
            </div>
        </nav>
        
        <router-view></router-view>
    </div>
    
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
