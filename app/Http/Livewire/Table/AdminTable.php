<?php

namespace App\Http\Livewire\Table;

use App\Models\Admin as AdminModel;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;

class AdminTable extends LivewireDatatable
{
    public function builder()
    {
        return AdminModel::query();
    }

    public function columns()
    {
        return [
            Column::name('username')
                ->label('Username')
                ->searchable(),

            Column::name('email')
                ->label('Email')
                ->searchable(),

            Column::name('no_telepon')
                ->label('No. Telepon'),

            Column::callback(['id_admin'], function ($id) {
                return view('components.actions', [
                    'route' => 'admin',
                    'id'    => $id,
                ]);
            })->label('Aksi'),
        ];
    }
}
