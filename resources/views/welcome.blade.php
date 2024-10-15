<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>EmploiDev</title>
        <meta name="description" content="EmploiDev est une plateforme dédiée à la mise en relation entre développeurs en recherche d'emploi ou d'alternance et entreprises du secteur technologique. Simplifiez le recrutement en trouvant des talents du numérique correspondant à vos critères, et accédez à de nouvelles opportunités de carrière dans le développement informatique.">

        <link rel="icon" type="image/png" href="{{ asset('favicon/favicon-48x48.png') }}" sizes="48x48" />
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon/favicon.svg') }}" />
        <link rel="shortcut icon" href="{{ asset('favicon/favicon.ico') }}" />
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}" />
        <meta name="apple-mobile-web-app-title" content="EmploiDev" />

        <!-- Styles -->
        @vite('resources/css/app.css')
    </head>
    <body>
        <div id="app" class="bg-gray-100"></div>
        @vite('resources/js/app.js')
    </body>
</html>
