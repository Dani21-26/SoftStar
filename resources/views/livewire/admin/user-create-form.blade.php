@php
    $showPassword = false;
@endphp
<div>
    <div class="max-w-4xl mx-auto px-6 py-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">Registrar Nuevo Usuario</h2>

        @if (session('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="submit" class="space-y-5">
            <!-- Nombre -->
            <div>
                <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300"
                    for="name">Nombre</label>
                <input wire:model="name" type="text" id="name"
                    class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                @error('name')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300"
                    for="email">Email</label>
                <input wire:model="email" type="email" id="email"
                    class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                @error('email')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Contraseña -->
            <div x-data="{ show: false }">
                <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300"
                    for="password">Contraseña</label>
                <div class="relative">
                    <input wire:model="password" :type="show ? 'text' : 'password'" id="password"
                        class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 pr-10 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                    <button type="button" @click="show = !show"
                        class="absolute right-2 top-2 text-gray-500 dark:text-gray-300 focus:outline-none">
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.223-3.592M6.634 6.634A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.478 5.568M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirmar Contraseña -->
            <div x-data="{ show: false }">
                <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300"
                    for="password_confirmation">Confirmar Contraseña</label>
                <div class="relative">
                    <input wire:model="password_confirmation" :type="show ? 'text' : 'password'"
                        id="password_confirmation"
                        class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 pr-10 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                    <button type="button" @click="show = !show"
                        class="absolute right-2 top-2 text-gray-500 dark:text-gray-300 focus:outline-none">
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.223-3.592M6.634 6.634A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.478 5.568M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Roles -->
            <div>
                <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Roles</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach ($roles as $role)
                        <div class="flex items-center">
                            <input wire:model="selectedRoleNames" type="checkbox" value="{{ $role->name }}"
                                id="role-{{ $role->id }}" class="mr-2"
                                @if (auth()->user()->cannot('assignRole', $role)) disabled @endif>
                            <label for="role-{{ $role->id }}" class="text-gray-700 dark:text-gray-300">
                                {{ $role->name }}
                                @if ($role->name === 'Super-Admin')
                                    <span class="text-xs text-red-500">(Acceso completo)</span>
                                @endif
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('selectedRoleNames')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end">

                <a href="{{ route('users.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded mr-6">
                    Cancelar
                </a>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                    Registrar Usuario
                </button>
            </div>
        </form>
    </div>
</div>
