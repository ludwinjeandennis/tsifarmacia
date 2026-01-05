<?php

namespace App\DataTables;

use App\Http\Resources\RevenueResource;
use App\Http\Services\RevenueService;
use App\Models\Pharmacy;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RevenueDataTable extends DataTable
{
    public function __construct(protected RevenueService $revenueService)
    {
    }

    /**
     * Construye la clase DataTable.
     *
     * @param QueryBuilder $query Resultados del método query().
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->addColumn('Pharmacy_Name', function (Pharmacy $pharmacy) {
                return $pharmacy->name;
            })->addColumn('Total_Orders', function (Pharmacy $pharmacy) {
                $test=new RevenueResource($pharmacy);
                return $this->revenueService->calcRevenue($pharmacy)["Total_Orders"];
            })->addColumn('Total_Revenue', function (Pharmacy $pharmacy) {
                $test=new RevenueResource($pharmacy);
                return "$ ".$this->revenueService->calcRevenue($pharmacy)["Total_Revenue"];
            });
    }

    /**
     * Obtiene la consulta fuente de dataTable.
     */
    public function query(Pharmacy $model): QueryBuilder
    {
        if (auth()->user()->hasRole("pharmacy")) {
            return $model->where("owner_id", "=", auth()->user()->id);
        }
        return $model->newQuery();
    }

    /**
     * Método opcional si deseas usar el constructor html.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('Revenues-table')
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
            Column::make('Pharmacy_Name')->title('Nombre de Farmacia')
                ->visible(
                    auth()->user()->hasRole("admin")
                ),
            Column::make('Total_Orders')->title('Total de Pedidos'),
            Column::make('Total_Revenue')->title('Ingresos Totales'),
        ];
    }

    /**
     * Obtiene el nombre de archivo para exportar.
     */
    protected function filename(): string
    {
        return 'Ingresos_' . date('YmdHis');
    }
}