<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear permisos para cada módulo
        $permissions = [
            // Productos
            'ver producto', 'crear producto', 'editar producto', 'eliminar producto',
            
            // Proveedores
            'ver proveedor', 'crear proveedor', 'editar proveedor', 'eliminar proveedor',
            
            // Empleados
            'ver empleado', 'crear empleado', 'editar empleado', 'eliminar empleado',
            
            // Servicio Técnico (corregido el nombre)
            'ver servicioTecnico', 'crear servicioTecnico', 'tomar servicioTecnico',
            'confirmar servicioTecnico', 'cancelar servicioTecnico',
            
            // Recordatorios
            'ver recordatorios', 'crear recordatorios', 'editar recordatorios', 'eliminar recordatorios'
        ];

        // Crear todos los permisos
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Crear rol Super-Admin y asignar todos los permisos
        $roleAdmin = Role::firstOrCreate(['name' => 'Super-Admin', 'guard_name' => 'web']);
        $roleAdmin->syncPermissions(Permission::all());

        // Crear usuario admin (corregida la variable $adminUser que faltaba el signo $)
        $adminUser = User::create([
            'name' => 'Administrador',
            'email' => 'maderadiazdaniela@gmail.com',
            'password' => bcrypt('Madera21*'), // Siempre usar bcrypt para contraseñas
            'email_verified_at' => now()
        ]);

        // Asignar rol al usuario admin
        $adminUser->assignRole($roleAdmin);

        // (Opcional) Crear otros roles con permisos específicos
        $this->createOtherRoles();
    }

    /**
     * Crear roles adicionales con sus permisos
     */
    protected function createOtherRoles(): void
    {
        // Rol Técnico
        $roleTecnico = Role::firstOrCreate(['name' => 'tecnico', 'guard_name' => 'web']);
        $roleTecnico->syncPermissions([
            'ver servicioTecnico',
            'tomar servicioTecnico',
            'confirmar servicioTecnico',
            'cancelar servicioTecnico',
            'ver recordatorios'
        ]);

        // Rol Servicio-Cliente
        $roleServicio = Role::firstOrCreate(['name' => 'servicio-cliente', 'guard_name' => 'web']);
        $roleServicio->syncPermissions([
            'ver producto',
            'ver servicioTecnico',
            'crear servicioTecnico',
            'ver recordatorios',
            'crear recordatorios'
        ]);
    }
}