<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>EmploiDev</title>
        <meta name="description" content="Rassemblements moto en France, balade, exposition, hivernale, roadtrip, chaque événement se trouve ici."> 

        <!-- Styles -->
        @vite('resources/css/app.css')
    </head>
    <body>
        <div id="app" class="bg-gray-100"></div>
        @vite('resources/js/app.js')
        <script src="https://kit.fontawesome.com/5344b5e2b1.js" crossorigin="anonymous"></script>
    </body>
</html>
