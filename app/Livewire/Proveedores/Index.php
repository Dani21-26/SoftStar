<?php

namespace App\Livewire\Proveedores;

use Livewire\Component;
use App\Models\Proveedor;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    
    public $search = '';
    public $perPage = 4;
    public $sortField = 'nombre_empresa';
    public $sortDirection = 'asc';
    public $estado = 'activo';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortField',
        'sortDirection',
        'estado' => ['except' => 'activo']
    ];
    
    protected $listeners = [
        'proveedor-creado' => '$refresh',
        'proveedor-actualizado' => '$refresh',
        'abrir-modal-flux' => '$refresh'
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortField = $field;
    }

    // Eliminación lógica (cambia estado a inactivo)
    public function delete($id_proveedor)
    {
        $proveedor = Proveedor::findOrFail($id_proveedor);
        $proveedor->update(['estado' => 'inactivo']);
        
        session()->flash('success', 'Proveedor desactivado correctamente');
        $this->dispatch('proveedor-actualizado');
    }

    // Cambiar estado (activo/inactivo)
    public function toggleStatus($id_proveedor)
    {
        $proveedor = Proveedor::findOrFail($id_proveedor);
        $newStatus = $proveedor->estado == 'activo' ? 'inactivo' : 'activo';
        $proveedor->update(['estado' => $newStatus]);
        
        $message = $newStatus == 'activo' ? 'Proveedor activado' : 'Proveedor desactivado';
        session()->flash('success', $message . ' correctamente');
    }

    public function edit($id_proveedor)
    {
        $this->dispatch('abrir-modal-edicion', id: $id_proveedor)->to(Edit::class);
    }
    public function mount()
    {
        $this->authorize('ver proveedor');
    }

    public function render()
    {
        return view('livewire.proveedores.index', [
            'proveedores' => Proveedor::query()
                ->when($this->search, function($query) {
                    $query->where('nombre_empresa', 'like', '%'.$this->search.'%')
                        ->orWhere('contacto_nombre', 'like', '%'.$this->search.'%');
                })
                ->when($this->estado, function($query) {
                    $query->where('estado', $this->estado);
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage)
        ]);
    }
}