<div class="max-w-3xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg ">
    <!-- Encabezado -->
    <div class="flex justify-between items-center pb-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
            Editar Servicio
        </h2>
        <button 
            type="button" 
            onclick="window.history.back()" 
            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none"
        >
            ✕
        </button>
    </div>

    <form wire:submit.prevent="actualizar" class="space-y-5 pt-4">
        <!-- Cliente -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
            <input 
                type="text" 
                wire:model.defer="cliente" 
                class="w-full px-3 py-2  border border-blue-400 dark:border-blue-400 rounded-lg 
                       bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                       focus:outline-none focus:ring-2 focus:ring-blue-200"
            />
            @error('cliente') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Router -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Router</label>
            <input 
                type="text" 
                wire:model.defer="router" 
                class="w-full px-3 py-2 border border-blue-400 dark:border-blue-400  rounded-lg 
                       bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                       focus:outline-none focus:ring-2 focus:ring-blue-200"
            />
            @error('router') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Litebeam -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Litebeam</label>
            <input 
                type="text" 
                wire:model.defer="litebean" 
                class="w-full px-3 py-2 border border-blue-400 dark:border-blue-400 rounded-lg 
                       bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                       focus:outline-none focus:ring-2 focus:ring-blue-200"
            />
            @error('litebean') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Dirección -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dirección</label>
            <input 
                type="text" 
                wire:model.defer="direccion" 
                class="w-full px-3 py-2 border border-blue-400 dark:border-blue-400  rounded-lg 
                       bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                       focus:outline-none focus:ring-2 focus:ring-blue-200"
            />
            @error('direccion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Falla Reportada -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Falla Reportada</label>
            <textarea 
                wire:model.defer="falla_reportada" 
                rows="3"
                class="w-full px-3 py-2 border  border-blue-400 dark:border-blue-400  rounded-lg 
                       bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                       focus:outline-none focus:ring-2 focus:ring-blue-200"
            ></textarea>
            @error('falla_reportada') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Prioridad -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prioridad</label>
            <select 
                wire:model.defer="prioridad" 
                class="w-full px-3 py-2 border border-blue-400 dark:border-blue-400 rounded-lg 
                       bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                       focus:outline-none focus:ring-2 focus:ring-blue-200"
            >
                <option value="">Seleccione una opción</option>
                <option value="alta">Alta</option>
                <option value="media">Media</option>
                <option value="baja">Baja</option>
            </select>
            @error('prioridad') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Técnico -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Técnico Asignado</label>
            <select 
                wire:model.defer="tecnico_id" 
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
        <div class="flex gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
            <button 
                type="button" 
                onclick="window.history.back()" 
                class="flex-1 px-4 py-2 bg-red-500 hover:bg-red-700 text-white 
                       dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100 
                       rounded-lg font-medium"
            >
                Cancelar
            </button>
            <button 
                type="submit" 
                class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium"
            >
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
