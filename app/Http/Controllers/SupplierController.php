<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Supplier::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('suppliers.edit', $row->id).'" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Editar</a>';
                    $btn .= ' <form action="'.route('suppliers.destroy', $row->id).'" method="POST" style="display:inline-block;">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'¿Seguro de eliminar?\')"><i class="fas fa-trash"></i></button>
                              </form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('suppliers.index');
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'phone' => 'nullable|max:20',
            'address' => 'nullable|text',
        ]);

        Supplier::create($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Proveedor creado exitosamente.');
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:suppliers,email,'.$id,
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy($id)
    {
        Supplier::findOrFail($id)->delete();
        return redirect()->route('suppliers.index')->with('success', 'Proveedor eliminado.');
    }
}
