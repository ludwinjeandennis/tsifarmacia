<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PurchaseOrder::with('supplier')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('supplier_name', function($row) {
                    return $row->supplier ? $row->supplier->name : 'N/A';
                })
                ->editColumn('status', function($row) {
                    $badges = [
                        'Pending' => 'warning',
                        'Approved' => 'info',
                        'Received' => 'success',
                        'Cancelled' => 'danger'
                    ];
                    $bg = $badges[$row->status] ?? 'secondary';
                    return '<span class="badge badge-'.$bg.'">'.$row->status.'</span>';
                })
                ->editColumn('expected_delivery_date', function($row) {
                    return $row->expected_delivery_date ? $row->expected_delivery_date->format('d/m/Y') : '-';
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-info btn-sm viewOrder">Ver</a>';
                    if($row->status == 'Pending') {
                        $btn .= ' <a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-success btn-sm approveOrder">Aprobar</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        $suppliers = Supplier::all();
        $medicines = Medicine::all();
        return view('purchase_orders.index', compact('suppliers', 'medicines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'medicines' => 'required|array',
            'medicines.*.id' => 'required',
            'medicines.*.qty' => 'required|numeric|min:1',
        ]);

        $po = PurchaseOrder::create([
            'supplier_id' => $request->supplier_id,
            'status' => 'Pending',
            'total_amount' => 0,
            'notes' => $request->notes
        ]);

        return response()->json(['success' => 'Orden de Compra generada exitosamente.']);
    }

    public function create()
    {
        // Not used - modal handles creation
        return redirect()->route('purchase_orders.index');
    }

    public function show($id)
    {
        $order = PurchaseOrder::with('supplier')->find($id);
        return response()->json($order);
    }

    public function edit($id)
    {
        $order = PurchaseOrder::with('supplier')->find($id);
        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $order = PurchaseOrder::find($id);
        $order->update([
            'status' => $request->status ?? $order->status,
            'notes' => $request->notes ?? $order->notes
        ]);

        return response()->json(['success' => 'Orden actualizada correctamente.']);
    }

    public function destroy($id)
    {
        PurchaseOrder::find($id)->delete();
        return response()->json(['success' => 'Orden eliminada correctamente.']);
    }
}
