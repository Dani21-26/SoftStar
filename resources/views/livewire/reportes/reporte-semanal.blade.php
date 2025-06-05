<div>
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Reporte Semanal</h1>
        
        <div class="mb-4 flex gap-4">
            <input type="date" wire:model="fechaInicio" class="border rounded px-3 py-2">
            <input type="date" wire:model="fechaFin" class="border rounded px-3 py-2">
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">Código</th>
                        <th class="px-4 py-2">Cliente</th>
                        <th class="px-4 py-2">Técnico</th>
                        <th class="px-4 py-2">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($servicios as $servicio)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $servicio->codigo }}</td>
                        <td class="px-4 py-2">{{ $servicio->cliente }}</td>
                        <td class="px-4 py-2">{{ $servicio->tecnico->name ?? 'No asignado' }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs 
                                {{ $servicio->estado == 'completado' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($servicio->estado) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>