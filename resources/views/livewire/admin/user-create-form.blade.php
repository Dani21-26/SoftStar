<div>
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-6">Registrar Nuevo Usuario</h2>
        
        @if(session('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif
    
        <form wire:submit.prevent="submit">
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="name">Nombre</label>
                <input wire:model="name" type="text" id="name" class="w-full px-3 py-2 border rounded">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
    
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="email">Email</label>
                <input wire:model="email" type="email" id="email" class="w-full px-3 py-2 border rounded">
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
    
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="password">Contraseña</label>
                <input wire:model="password" type="password" id="password" class="w-full px-3 py-2 border rounded">
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
    
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="password_confirmation">Confirmar Contraseña</label>
                <input wire:model="password_confirmation" type="password" id="password_confirmation" class="w-full px-3 py-2 border rounded">
            </div>
    
            
            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Roles</label>
                @foreach($roles as $role)
                    <div class="flex items-center mb-2">
                        <input wire:model="selectedRoleNames" 
                               type="checkbox" 
                               value="{{ $role->name }}" 
                               id="role-{{ $role->id }}" 
                               class="mr-2"
                               @if(auth()->user()->cannot('assignRole', $role)) disabled @endif>
                        <label for="role-{{ $role->id }}">
                            {{ $role->name }}
                            @if($role->name === 'Super-Admin')
                                <span class="text-xs text-red-500">(Acceso completo)</span>
                            @endif
                        </label>
                    </div>
                @endforeach
                @error('selectedRoleNames') 
                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                @enderror
            </div>
    
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Registrar Usuario
            </button>
        </form>
    </div>
</div>