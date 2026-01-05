<?php

namespace App\DataTables;

use App\Models\Area;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AreasDataTable extends DataTable
{
    /**
     * Construye la clase DataTable.
     *
     * @param QueryBuilder $query Resultados del método query().
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', '<div class="btn-group btn-group-toggle gap-1" data-toggle="buttons">
            <a class="btn btn-success rounded " id="option_a1" href="{{Route("areas.edit",$id)}}" > <i class="bi bi-pencil-square"></i> </a>

            <form method="post" class="delete_item"  id="option_a3" action="{{Route("areas.destroy",$id)}}">
                @csrf
                @method("DELETE")
                <button type="submit" class="btn btn-danger" onclick="modalShow(event)" id="delete_{{$id}}" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-trash3"></i></button>
            </form>
        </div>')
           ->addColumn('Country', function (Area $area) {
                return $area->country->name;
            });

    }

    /**
     * Obtiene la consulta fuente de dataTable.
     */
    public function query(Area $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Método opcional si deseas usar el constructor html.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('areas-table')
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

            Column::make('name')->title('Nombre'),
            Column::make('address')->title('Dirección'),
            Column::make('Country')->title('País'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center')
        ];
    }

    /**
     * Obtiene el nombre de archivo para exportar.
     */
    protected function filename(): string
    {
        return 'Áreas_' . date('YmdHis');
    }
}