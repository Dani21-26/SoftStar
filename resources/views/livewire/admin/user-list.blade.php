<div class="max-w-6xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg transition-colors duration-300">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Usuarios</h2>
        <a href="{{ route('users.create') }}"
            class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow transition-colors duration-200">
            + Nuevo Usuario
        </a>
    </div>

    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre o email..."
            class="w-full sm:w-1/2 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
    </div>

    <div class="overflow-x-auto rounded-lg shadow-sm">
        <table class=" text-bla min-w-full divide-y divide-gray-200 text-black dark:text-white tracking-tight">
            <thead class="bg-blue-400 dark:bg-blue-700">
                <tr> 
                    <th class="px-4 py-3 text-left text-xs font-semibold  text-black dark:text-white uppercase">
                        Nombre</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold  text-black dark:text-white uppercase">
                        Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold  text-black dark:text-white uppercase">
                        Roles</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-black dark:text-white uppercase">
                        Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-4 py-3 text-gray-800 dark:text-gray-200">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                            {{ $user->roles->pluck('name')->join(', ') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('users.edit', $user->id) }}"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-xs shadow transition-colors">Editar</a>
                            
                            
                                @if ($user->estado == 'activo')
                                <button wire:click="toggleStatus({{ $user->id }})"
                                    class="px-3 py-1 rounded-lg text-xs shadow ml-2 transition-colors
            {{ $user->estado === 'activo' ? 'bg-yellow-600 hover:bg-yellow-700 text-white' : 'bg-green-600 hover:bg-green-700 text-white' }}">
                                    {{ $user->estado === 'activo' ? 'Desactivar' : 'Activar' }}
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                            No hay usuarios registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links('pagination::tailwind') }}
    </div>
</div>
