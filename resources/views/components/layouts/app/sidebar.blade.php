<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')



</head>

<body
    class="min-h-screen border-zinc-400 bg-gradient-to-b from-blue-100 to-white text-black dark:border-zinc-500 dark:from-zinc-900 dark:to-zinc-800 ">

    <flux:sidebar sticky stashable
        class="border-r border-zinc-400 bg-gradient-to-b from-white to-blue-100 dark:border-zinc-500 dark:from-zinc-900 dark:to-zinc-800 ">

        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 p-4">
            <img src="{{ asset('imagenes/logo.png') }}" class="h-10 w-auto rounded-lg" alt="Logo">
            <span class="text-lg font-bold text-black dark:text-white">SoftStar</span>
        </a>


        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Menu')" class="grid">
                <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>{{ __('Dashboard') }}
                </flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <flux:navlist.item icon="eye" href="{{ route('servicios.agenda') }}" wire:navigate
            class="{{ request()->routeIs('servicios.agenda') ? 'bg-white font-semibold rounded-lg dark:bg-zinc-800' : '' }}">
            {{ __('Agenda') }}
        </flux:navlist.item>


        <flux:navlist.item icon="check-circle" href="{{ route('servicios.historial') }}" wire:navigate
            class="{{ request()->routeIs('servicios.historial') ? 'bg-white font-semibold rounded-lg dark:bg-zinc-800' : '' }}">
            {{ __('Sevicios Completados') }}
        </flux:navlist.item>

        <flux:navlist.item icon="users" href="{{ route('users.index') }}" wire:navigate
            class="{{ request()->routeIs('users.index') ? 'bg-white font-semibold rounded-lg dark:bg-zinc-800' : '' }}">
            {{ __('Usuarios') }}
        </flux:navlist.item>

        <flux:spacer />

        <flux:spacer />

        <flux:navlist variant="outline">
            <flux:navlist.item icon="rectangle-group" href="{{ route('productos.index') }}" wire:navigate
                class="{{ request()->routeIs('productos.index') ? 'bg-white font-semibold rounded-lg dark:bg-zinc-800' : '' }}">
                {{ __('Herramientas') }}
            </flux:navlist.item>

            <flux:navlist.item icon="user" href="{{ route('proveedores.index') }}" wire:navigate
                class="{{ request()->routeIs('proveedores.index') ? 'bg-white font-semibold rounded-lg dark:bg-zinc-800' : '' }}">
                {{ __('Proveedores') }}
            </flux:navlist.item>

            @can('ver empleado')
                <flux:navlist.item icon="users" href="{{ route('empleado.index') }}" wire:navigate
                    class="{{ request()->routeIs('empleado.index') ? 'bg-white font-semibold rounded-lg dark:bg-zinc-700' : '' }}">
                    {{ __('Personal') }}
                </flux:navlist.item>
            @endcan

        </flux:navlist>

        <!-- Desktop User Menu -->
        <flux:dropdown position="bottom" align="start">
            <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                icon-trailing="chevrons-up-down" />

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}
    @fluxScripts

</body>

</html>
