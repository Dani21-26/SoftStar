<div>
    <flux:modal name="crear-proveedor" class="md:w-1000" wire:model="showModal">
        <form wire:submit.prevent="save" class="space-y-6">
            <div>
                <flux:heading size="lg">Nuevo Proveedor</flux:heading>
            </div>

            <!-- Nombre empresa -->
            <flux:input label="Nombre de la empresa" placeholder="Ej: Tech Solutions SA"
                wire:model.defer="nombre_empresa" />

            <!-- Contacto -->
            <flux:input label="Nombre del contacto" placeholder="Ej: Juan Pérez" wire:model.defer="contacto_nombre" />

            <!-- Teléfono -->
            <flux:input label="Teléfono" placeholder="Ej: +56912345678" wire:model.defer="telefono" />

            <!-- Dirección -->
            <flux:input label="Dirección" placeholder="Ej: Av. Principal 123" wire:model.defer="direccion" />

            <!-- Correo -->
            <flux:input label="Correo electrónico" type="email" placeholder="contacto@empresa.com"
                wire:model.defer="correo" />

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Guardar</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
