@props([
    'title' => config('app.name'),
    'description' => null,
    'skipText' => __('common.skip_to_content'),
    'robots' => null,
    'fontSet' => 'nunito',
    'bodyClass' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        (function () {
            try {
                var tema = localStorage.getItem('tema');

                if (tema === 'gelap' || (tema === null && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.dataset.theme = 'dark';
                }
            } catch (error) {
                // localStorage tidak tersedia: gunakan tema terang bawaan.
            }
        })();
    </script>
    <title>{{ $title }}</title>

    @if ($description)
        <meta name="description" content="{{ $description }}">
    @endif

    @if ($robots)
        <meta name="robots" content="{{ $robots }}">
    @endif

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @if ($fontSet === 'public')
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=Public+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ $head ?? '' }}
</head>
<body id="top" @class([$bodyClass])>
    <a class="skip" href="#isi">{{ $skipText }}</a>

    <x-icon-sprite />

    {{ $slot }}

    @stack('scripts')
</body>
</html>
