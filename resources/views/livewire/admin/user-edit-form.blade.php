<div class="max-w-2xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg transition-colors duration-300">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">Editar Usuario</h2>

    <form wire:submit.prevent="update" class="space-y-5">
        <!-- Nombre -->
        <div>
            <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Nombre</label>
            <input type="text" wire:model="name"
                class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
            @error('name')
                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Email</label>
            <input type="email" wire:model="email"
                class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
            @error('email')
                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Contraseña -->
        <div>
            <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Contraseña
                (opcional)</label>
            <input type="password" wire:model="password" placeholder="Nueva contraseña"
                class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
            <input type="password" wire:model="password_confirmation" placeholder="Confirmar contraseña"
                class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 mt-2 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
            @error('password')
                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>
        <!-- Estado -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Estado <span class="text-red-500">*</span>
            </label>
            <select wire:model="estado" class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600"
                required>
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>
            @error('estado')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
        <!-- Roles -->
        <div>
            <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Roles</label>
            <select wire:model="selectedRoleNames" multiple
                class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
            @error('selectedRoleNames')
                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Botón -->
        <div class="flex justify-end">
            <a href="{{ route('users.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded m-2" >
                Cancelar
            </a>

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                Actualizar
            </button>
        </div>

    </form>

    <!-- Mensaje de éxito -->
    @if (session()->has('message'))
        <div class="mt-4 p-3 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-lg shadow">
            {{ session('message') }}
        </div>
    @endif
</div>
