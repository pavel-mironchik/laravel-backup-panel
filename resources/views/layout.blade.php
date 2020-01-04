<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Backups{{ $globalVariables['appName'] ? ' - ' . $globalVariables['appName'] : '' }}</title>

    <!-- Scripts -->
    <script src="{{ asset(mix('app.js', 'vendor/backup_panel')) }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset(mix('app.css', 'vendor/backup_panel')) }}" rel="stylesheet">
</head>
<body>
    <div id="backup_panel" v-cloak>
        <router-view></router-view>
    </div>

    <!-- Global variables -->
    <script>
        window.BackupPanel = @json($globalVariables);
    </script>
</body>
</html>
