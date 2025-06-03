<div>
    <!-- Encabezado y otros elementos -->

    <!-- Sección de Roles -->
    <div class="mb-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Roles</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach ($availableRoles as $role)
                <div class="flex items-center">
                    <input wire:model="selectedRoleNames" id="role_{{ $role->id }}" type="checkbox"
                        value="{{ $role->name }}"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                        @if ($role->name === 'Super-Admin' && !auth()->user()->hasRole('Super-Admin')) disabled @endif>
                    <label for="role_{{ $role->id }}" class="ml-2 block text-sm text-gray-900">
                        {{ $role->name }}
                        @if ($role->name === 'Super-Admin')
                            <span class="text-xs text-red-500">(Acceso total)</span>
                        @endif
                    </label>
                </div>
            @endforeach
        </div>
        @error('selectedRoleNames')
            <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
        @enderror
        <!-- Sección de Permisos Actuales -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Permisos Actuales</h3>
            <div class="space-y-4">
                @foreach ($permissionGroups as $group => $permissions)
                    <div>
                        <h4 class="font-medium text-gray-700 mb-2">{{ $group }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                            @foreach ($permissions as $permission)
                                @if ($user->hasPermissionTo($permission))
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $permission }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Botón de Guardar -->
        <div class="flex justify-end mt-6">
            <button wire:click="updateRoles"
                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Guardar Cambios
            </button>
        </div>

        <!-- Mensaje de éxito -->
        @if (session()->has('success'))
            <div class="mt-4 p-4 bg-green-50 border-l-4 border-green-400">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
</div>
</div>
</div>
