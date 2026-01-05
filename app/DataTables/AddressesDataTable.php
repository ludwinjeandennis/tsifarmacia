<?php

namespace App\DataTables;

use App\Models\Address;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AddressesDataTable extends DataTable
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
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <a class="btn btn-success rounded" id="option_a1" href="{{Route("addresses.edit",$id)}}"title="editar"> <i class="bi bi-pencil-square"></i> </a>
                    <a class="btn btn-primary rounded mx-1" id="option_a2" href="{{Route("addresses.show",$id)}}" title="ver" ><i class="bi bi-file-earmark-text-fill"></i> </a>
                    <form method="post" class="delete_item"  id="option_a3" action="{{Route("addresses.destroy",$id)}}">
                        @csrf
                        @method("DELETE")
                        <button type="submit" class="btn btn-danger" onclick="modalShow(event)" id="delete_{{$id}}" data-bs-toggle="modal" data-bs-target="#exampleModal" title="eliminar"><i class="bi bi-trash3"></i></button>
                    </form>
                </div>')
            ->addColumn('area', function (Address $address) {
                return $address->area->name;
            })
            ->addColumn('user', function (Address $address) {
                return $address->user->name;
            })
            ->addColumn('ismain', function (Address $address) {
                return $address->is_main;
            })
            ->setRowId('id');
    }

    /**
     * Obtiene la consulta fuente de dataTable.
     */
    public function query(Address $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Método opcional si deseas usar el constructor html.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('addresses-table')
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
            Column::make('street_name')->title('Nombre de la Calle'),
            Column::make('building_number')->title('Número del Edificio'),
            Column::make('floor_number')->title('Número del Piso'),
            Column::make('flat_number')->title('Número del Departamento'),
            Column::computed('ismain',"¿Es Principal?"),
            Column::computed('area',"Área"),
            Column::computed('user',"Nombre de Usuario"),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];

    }

    /**
     * Obtiene el nombre de archivo para exportar.
     */
    protected function filename(): string
    {
        return 'Direcciones_' . date('YmdHis');
    }
}