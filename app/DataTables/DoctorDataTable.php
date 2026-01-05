<?php

namespace App\DataTables;


use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DoctorDataTable extends DataTable
{
    /**
     * Construye la clase DataTable.
     *
     * @param QueryBuilder $query Resultados del método query().
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
//        dd(new EloquentDataTable($query));

        return (new EloquentDataTable($query))

            ->addColumn('action', function (User $doctor){
                return $this->toggleBan($doctor->id) . '
                    <a class="btn btn-success rounded mx-1" id="option_a1" href="' .route("doctors.edit",$doctor->id).'" title="editar"> <i class="bi bi-pencil-square"></i> </a>
                    <form method="post" class="delete_item"  id="option_a3" action="' .route("doctors.destroy",$doctor->id).'">
                        <input type="hidden" name="_token" value="' .csrf_token(). '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit " class="btn btn-danger" onclick="modalShow(event)" id="delete_ ' . $doctor->id . '" data-bs-toggle="modal" data-bs-target="#exampleModal" title="eliminar"><i class="bi bi-trash3"></i></button>
                    </form>
                </div>';
            })
            ->addColumn('pharmacy', function ($user) {
                return $user->pharmacy->name;
            })
            ->setRowId('id');
    }

    /**
     * Obtiene la consulta fuente de dataTable.
     */
    public function query(User $model): QueryBuilder
    {

        if (auth()->user()->hasRole("pharmacy")) {
            return $model->where("pharmacy_id", "=", auth()->user()->pharmacy_id)->whereHas('roles', function ($role) {
                return $role->where("name", "doctor");
            });
        } else {
            return $model->newQuery()->whereHas('roles', function ($role) {
                return $role->where("name", "doctor");
            });
        }
    }

    /**
     * Método opcional si deseas usar el constructor html.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('doctors-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
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
            Column::make('national_id')->title('Identificación Nacional'),
            Column::make('email')->title('Correo Electrónico'),
            Column::computed('pharmacy')->title('Farmacia')
                ->visible(
                    auth()->user()->hasRole("admin")
                ),
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
        return 'Doctores_' . date('YmdHis');
    }


    private function toggleBan($id)
    {
        $isBan = User::find($id)->isBanned();

         return     '<div class="btn-group btn-group-toggle" data-toggle="buttons">
                      <form method="post" class="ban_doctor"  id="option_a3" action="' . ($isBan ? route("doctors.unban",$id) : route("doctors.ban",$id)) .'">
                        <input type="hidden" name="_token" value="' .csrf_token(). '">
                        <input type="hidden" name="_method" value="PUT">
                      <button type="submit" class="btn btn-secondary rounded" title="' . ($isBan ? "Desbloquear doctor" : "Bloquear doctor") . '"><i class="bi ' .  ($isBan ?  "bi-unlock-fill" :  "bi-person-lock") .'"></i></button>
                    </form>';

    }
}