<div>
    <flux:modal name="crear-servicio" wire:model="showModal" class="md:w-1/2">
        <form wire:submit.prevent="guardar" class="p-6 space-y-6  rounded-lg">
            <!-- Encabezado -->
            <div class="flex justify-between items-center pb-4 border-b border-blue-500 dark:border-blue-700">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                    Nuevo Servicio
                </h2>
                <button 
                    type="button" 
                    @click="$dispatch('close-modal', 'crear-servicio')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none"
                >
                    
                </button>
            </div>

            <!-- Cliente -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cliente</label>
                <input 
                    type="text" 
                    wire:model="cliente" 
                    required
                    class="w-full px-3 py-2 border border-blue-400 dark:border-blue-400 rounded-lg 
                           bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                           focus:outline-none focus:ring-2 focus:ring-blue-200"
                />
                @error('cliente')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Router -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Router</label>
                <input 
                    type="text" 
                    wire:model="router" 
                    required
                    class="w-full px-3 py-2 border border-blue-400 dark:border-blue-400 rounded-lg 
                           bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                           focus:outline-none focus:ring-2 focus:ring-blue-200"
                />
                @error('router')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Litebeam -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Litebeam</label>
                <input 
                    type="text" 
                    wire:model="litebean" 
                    required
                    class="w-full px-3 py-2 border border-blue-400 dark:border-blue-400 rounded-lg 
                           bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                           focus:outline-none focus:ring-2 focus:ring-blue-200"
                />
                @error('litebean')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Dirección -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dirección</label>
                <input 
                    type="text" 
                    wire:model="direccion" 
                    required
                    class="w-full px-3 py-2 border border-blue-400 dark:border-blue-400 rounded-lg 
                           bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                           focus:outline-none focus:ring-2 focus:ring-blue-200"
                />
                @error('direccion')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Falla Reportada -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Falla Reportada</label>
                <input 
                    type="text" 
                    wire:model="falla_reportada" 
                    required
                    class="w-full px-3 py-2 border border-blue-400 dark:border-blue-400 rounded-lg 
                           bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                           focus:outline-none focus:ring-2 focus:ring-blue-200"
                />
                @error('falla_reportada')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Prioridad -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prioridad</label>
                <select 
                    wire:model="prioridad" 
                    required
                    class="w-full px-3 py-2 border border-blue-400 dark:border-blue-400 rounded-lg 
                           bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                           focus:outline-none focus:ring-2 focus:ring-blue-200"
                >
                    <option value="baja">Baja</option>
                    <option value="media">Media</option>
                    <option value="alta">Alta</option>
                </select>
                @error('prioridad')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Técnico -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Técnico</label>
                <select 
                    wire:model="tecnico_id" 
                    class="w-full px-3 py-2 border border-blue-400 dark:border-blue-400 rounded-lg 
                           bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                           focus:outline-none focus:ring-2 focus:ring-blue-200"
                >
                    <option value="">Selecciona un técnico</option>
                    @foreach ($tecnicos as $tecnico)
                        <option value="{{ $tecnico->id }}">{{ $tecnico->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Botones -->
            <div class="flex gap-4 pt-4 border-t border-blue-500 dark:border-blue-700">
                
                <button 
                    type="submit" 
                    wire:loading.attr="disabled"
                    class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded-lg font-medium"
                >
                    <span wire:loading.remove>Guardar</span>
                    <span wire:loading wire:target="guardar">Guardando...</span>
                </button>
            </div>
        </form>
    </flux:modal>
</div>
