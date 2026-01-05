<?php

namespace App\DataTables;

use App\Models\Medicine;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MedicinesDataTable extends DataTable
{
    /**
     * Construye la clase DataTable.
     *
     * @param QueryBuilder $query Resultados del método query().
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', '
                <div class="btn-group btn-group-toggle gap-1" data-toggle="buttons">
                    <a class="btn btn-success rounded" id="option_a1" href="{{Route("medicines.edit",$id)}}" title="editar"> <i class="bi bi-pencil-square"></i> </a>
                    <form method="post" class="delete_item"  id="option_a3" action="{{Route("medicines.destroy",$id)}}">
                        @csrf
                        @method("DELETE")
                        <button type="submit" class="btn btn-danger" onclick="modalShow(event)" id="delete_{{$id}}" data-bs-toggle="modal" data-bs-target="#exampleModal" title="eliminar"><i class="bi bi-trash3"></i></button>
                    </form>
                </div>')
            ->setRowId('id')->addColumn('name', function (Medicine $medicine) {
                return $medicine->name;
            })->addColumn('type', function (Medicine $medicine) {
                return $medicine->type;
            })->addColumn('price', function (Medicine $medicine) {
                return "$ ".$medicine->price;
            });
    }

    /**
     * Obtiene la consulta fuente de dataTable.
     */
    public function query(Medicine $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Método opcional si deseas usar el constructor html.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('medicines-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Obtiene la definición de columnas de dataTable.
     */
    public function getColumns(): array
    {
        return [

            Column::make('id'),
            Column::make('name')->title('Nombre'),
            Column::make('type')->title('Tipo'),
            Column::make('price')->title('Precio'),
            Column::computed('action')->title('Acciones')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),
        ];
    }

    /**
     * Obtiene el nombre de archivo para exportar.
     */
    protected function filename(): string
    {
        return 'Medicamentos_' . date('YmdHis');
    }
}