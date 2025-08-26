<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use Livewire\Volt\Volt;
use App\Livewire\Productos\Index;
use App\Livewire\Productos\Create;
use App\Livewire\Productos\Edit;
use App\Livewire\Productos\Show;
use App\Livewire\Proveedores\{Index as ProveedoresIndex, Create as ProveedoresCreate, Edit as ProveedoresEdit, Show as ProveedoresShow};
use App\Livewire\Empleado\{Index as EmpleadoIndex, Create as EmpleadoCreate, Edit as EmpleadoEdit, Show as EmpleadoShow};
use App\Livewire\Servicios\GestionServicios;
use App\Livewire\Servicios\AgendaServicios;
use App\Livewire\Servicios\CrearServicio;
use App\Livewire\Servicios\EditarServicio;
use App\Livewire\Servicios\HistorialServicios;
use App\Livewire\Admin\UserCreateForm;
use App\Livewire\Admin\UserRoleAssignment;
use App\Livewire\Admin\UserList;
use App\Livewire\Admin\UserEditForm;

use App\Http\Controllers\DashboardController;


// Ruta pública
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Grupo de rutas que requieren autenticación
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    // Rutas para Productos (CRUD completo)
    Route::middleware(['auth', 'verified', 'role:Super-Admin'])->group(function () {
    Route::prefix('productos')->group(function () {
    Route::get('/', Index::class)->name('productos.index');
    Route::get('/crear', Create::class)->name('productos.create');
    Route::get('/{producto}/editar', Edit::class)->name('productos.edit');
    Route::get('/{producto}', Show::class)->name('productos.show'); 
    });
    
    Route::prefix('proveedores')->group(function () {
    Route::get('/', ProveedoresIndex::class)->name('proveedores.index');
    Route::get('/crear', ProveedoresCreate::class)->name('proveedores.create');
    Route::get('/{proveedor}/editar', ProveedoresEdit::class)->name('proveedores.edit');
    Route::get('/{proveedor}', ProveedoresShow::class)->name('proveedores.show');
    });

     // Rutas para Empleados(CRUD completo)
    Route::prefix('empleado')->group(function () {
    Route::get('/', EmpleadoIndex::class)->name('empleado.index');
    Route::get('/crear', EmpleadoCreate::class)->name('empleado.create');
    Route::get('/{empleado}/editar', EmpleadoEdit::class)->name('empleado.edit');
    Route::get('/{empleado}', EmpleadoShow::class)->name('empleado.show'); 

    // Rutas para usuarios 
    });

    
    Route::prefix('users')->group(function () {
        Route::get('/', UserList::class)->name('users.index');
        Route::get('/create', UserCreateForm::class)->name('users.create');
        Route::get('/{user}/edit', UserEditForm::class)->name('users.edit');
        Route::get('/{user}/roles', UserRoleAssignment::class)->name('users.roles');
    });
    
    
        
    });
    
    //ruta de sevicios tecnicos
    Route::middleware(['auth', 'verified', 'role:Super-Admin|tecnico|servicio-cliente'])->group(function () {
        Route::prefix('servicios')->group(function () {
            // Servicios actuales (Livewire)
            Route::get('/gestion', GestionServicios::class)->name('servicios.gestion');
            Route::get('/', AgendaServicios::class)->name('servicios.agenda');  
            Route::get('/crear', CrearServicio::class)->name('servicios.crear');
            Route::get('/servicios/{servicioId}/editar', EditarServicio::class)->name('servicios.editar');


            

            
            // Historial de servicios completados (Livewire)
            Route::get('/historial', HistorialServicios::class)->name('servicios.historial');
        }); 
    });
    // Configuraciones (manteniendo tu estructura actual)
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Rutas de autenticación
require __DIR__.'/auth.php';