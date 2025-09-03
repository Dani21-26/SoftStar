<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>