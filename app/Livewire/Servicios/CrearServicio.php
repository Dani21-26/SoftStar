<?php

namespace App\Livewire\Servicios;

use Livewire\Component;
use App\Models\User;
use App\Models\ServicioTecnico;
use App\Models\Producto;


class CrearServicio extends Component
{
    public $cliente, $router, $litebean, $direccion, $falla_reportada, $prioridad = 'media', $tecnico_id;
    public $tecnicos = [];
    public $showModal = false;

    public function mount()
    {
        $this->tecnicos = User::role('tecnico')->get(); 
    }

    public function guardar()
    {
        $this->dispatch('close-modal', name: 'crear-servicio');

        $this->validate([
            'cliente' => 'required',
            'router' => 'required',
            'litebean' => 'required',
            'direccion' => 'required',
            'falla_reportada' => 'required',
            'prioridad' => 'required',
            'tecnico_id' => 'nullable|exists:users,id',
        ]);

        $clienteNormalizado = trim(mb_strtolower($this->cliente));
        $serviciosMes = ServicioTecnico::whereRaw('LOWER(TRIM(cliente)) = ?', [$clienteNormalizado])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Guardar siempre
        ServicioTecnico::create([
            'cliente' => $this->cliente,
            'router' => $this->router,
            'litebean' => $this->litebean,
            'direccion' => $this->direccion,
            'falla_reportada' => $this->falla_reportada,
            'prioridad' => $this->prioridad,
            'estado' => 'pendiente',
            'tecnico_id' => $this->tecnico_id,
        ]);

        $this->reset([
            'cliente', 'router', 'litebean', 'direccion',
            'falla_reportada', 'prioridad', 'tecnico_id', 'showModal'
        ]);

        // Si supera el límite, mostramos advertencia y luego éxito
        if ($serviciosMes >= 3) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => '¡Atención!',
                'text' => 'Este cliente ya tiene tres o más servicios registrados este mes. El problema podría persistir.',
                'confirmButtonText' => 'OK',
                'nextSwal' => [
                    'icon' => 'success',
                    'title' => '¡Servicio creado!',
                    'text' => 'Servicio registrado exitosamente.',
                    'confirmButtonText' => 'OK',
                    'redirect' => route('servicios.agenda'),
                ]
            ]);
        } else {
            // Solo éxito
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Servicio creado!',
                'text' => 'Servicio registrado exitosamente.',
                'confirmButtonText' => 'OK',
                'redirect' => route('servicios.agenda'),
                'delay' => 50,
            ]);
        }

        $this->dispatch('servicioCreado');
    }

    public function render()
    {
        return view('livewire.servicios.crear-servicio');
    }
}
