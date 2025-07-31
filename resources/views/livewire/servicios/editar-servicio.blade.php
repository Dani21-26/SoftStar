<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Editar Servicio</h2>

    <form wire:submit="actualizar" class="space-y-4">
        <div>
            <label class="block text-sm font-medium">Cliente</label>
            <input type="text" wire:model.defer="cliente" class="mt-1 block w-full border rounded p-2" />
            @error('cliente') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Router</label>
            <input type="text" wire:model.defer="router" class="mt-1 block w-full border rounded p-2" />
            @error('router') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Litebeam</label>
            <input type="text" wire:model.defer="litebean" class="mt-1 block w-full border rounded p-2" />
            @error('litebean') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Dirección</label>
            <input type="text" wire:model.defer="direccion" class="mt-1 block w-full border rounded p-2" />
            @error('direccion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Falla Reportada</label>
            <textarea wire:model.defer="falla_reportada" class="mt-1 block w-full border rounded p-2"></textarea>
            @error('falla_reportada') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Prioridad</label>
            <select wire:model.defer="prioridad" class="mt-1 block w-full border rounded p-2">
                <option value="">Seleccione una opción</option>
                <option value="alta">Alta</option>
                <option value="media">Media</option>
                <option value="baja">Baja</option>
            </select>
            @error('prioridad') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Técnico Asignado</label>
            <select wire:model.defer="tecnico_id" class="mt-1 block w-full border rounded p-2">
                <option value="">Seleccione un técnico</option>
                @foreach($tecnicos as $tecnico)
                    <option value="{{ $tecnico->id }}">{{ $tecnico->name }}</option>
                @endforeach
            </select>
            @error('tecnico_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="pt-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
