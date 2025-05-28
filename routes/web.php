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
// Ruta pública
Route::get('/', function () {
    return view('welcome');
})->name('home');
Auth::routes(['register' => false]);
// Grupo de rutas que requieren autenticación
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::view('dashboard', 'dashboard')->name('dashboard'); 

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
        }); 
    });
    //ruta de sevicios tecnicos
    Route::middleware(['auth', 'verified', 'role:Super-Admin|tecnico|servicio-cliente'])->group(function () {
    Route::prefix('servicios')->group(function () {
    Route::get('/gestion', GestionServicios::class)->name('servicios.gestion');
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