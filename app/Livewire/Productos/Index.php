<?php
namespace App\Livewire\Productos;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class Index extends Component
{
    use WithPagination;
    
    public $search = '';
    public $perPage = 5;
    public $sortField = 'nombre';
    public $sortDirection = 'asc';
    public $estado = '';
    public $categoria = '';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'nombre'],
        'sortDirection' => ['except' => 'asc'],
        'estado' => ['except' => ''],
        'categoria' => ['except' => '']
    ];
    
    protected $listeners = [
        'producto-creado' => '$refresh',
        'producto-actualizado' => '$refresh',
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

    public function delete($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->update(['estado' => 'inactivo']);
        
        session()->flash('success', 'Producto desactivado correctamente');
        $this->dispatch('producto-actualizado');
    }

    public function toggleStatus($id)
    {
        $producto = Producto::findOrFail($id);
        $newStatus = $producto->estado == 'activo' ? 'inactivo' : 'activo';
        $producto->update(['estado' => $newStatus]);
        
        $message = $newStatus == 'activo' ? 'Producto activado' : 'Producto desactivado';
        session()->flash('success', $message . ' correctamente');
    }

    public function edit($id)
    {
        $this->dispatch('abrir-modal-edicion', id: $id)->to(Edit::class);
    }

    public function mount()
    {
        $this->authorize('ver producto');
    }
    
    public function render()
    {
        $query = Producto::query()
            ->with(['proveedor', 'categoria'])
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('nombre', 'like', '%'.$this->search.'%')
                      ->orWhere('detalle', 'like', '%'.$this->search.'%')
                      ->orWhere('ubicacion', 'like', '%'.$this->search.'%')
                      ->orWhereHas('categoria', function($q) {
                          $q->where('nombre', 'like', '%'.$this->search.'%');
                      })
                      ->orWhereHas('proveedor', function($q) {
                          $q->where('nombre_empresa', 'like', '%'.$this->search.'%');
                      });
                });
            })
            ->when($this->estado, function($query) {
                $query->where('estado', $this->estado);
            })
            ->when($this->categoria, function($query) {
                $query->where('id_categoria', $this->categoria);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.productos.index', [
            'productos' => $query->paginate($this->perPage),
            'categorias' => Categoria::orderBy('nombre')->get()
        ]);
    }
}