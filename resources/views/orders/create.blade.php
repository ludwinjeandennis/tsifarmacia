@extends("layouts.app")

@section('title', 'Nueva Venta')

@section('style')
<style>
    /* POS Custom Styles */
    .pos-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .medicine-card {
        cursor: pointer;
        transition: transform 0.2s;
    }
    .medicine-card:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .cart-table th, .cart-table td {
        vertical-align: middle !important;
    }
    .qty-input {
        width: 70px;
    }
    .total-display {
        background-color: #28a745; 
        color: white; 
        padding: 15px; 
        border-radius: 5px; 
        font-size: 1.5rem; 
        font-weight: bold;
        text-align: right;
    }
</style>
@endsection

@section("header", "Punto de Venta / Crear Pedido")

@section("breadcrumb")
    <li class="breadcrumb-item"><a href="{{route("index")}}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{route("orders.index")}}">Pedidos</a></li>
    <li class="breadcrumb-item active">Nueva Venta</li>
@endsection

@section('content')

<div class="container-fluid">
    <form action="{{route('orders.store')}}" method="post" id="posForm">
        @csrf
        
        <!-- Top Bar: Settings -->
        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-cogs"></i> Datos de la Venta</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Farmacia / Sucursal</label>
                            <select name="PharmacyName" class="form-control select2" required>
                                @foreach($pharmacy as $phar)
                                    <option value="{{$phar->name}}">{{$phar->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Cliente</label>
                            <select name="userName" class="form-control select2" required>
                                <option value="" disabled selected>Seleccione Cliente</option>
                                @foreach($users as $user)
                                    <option value="{{$user->name}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>¿Seguro Médico?</label>
                            <select name="insured" class="form-control">
                                <option value="No">No</option>
                                <option value="Si">Sí</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Doctor (Opcional)</label>
                            <select name="DocName" class="form-control select2">
                                <option value="">Ninguno</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{$doctor->name}}">{{$doctor->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left: Medicine Selector -->
            <div class="col-md-5">
                <div class="card card-primary card-outline h-100">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-pills"></i> Catálogo</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Buscar Medicamento</label>
                            <select id="medicineSelector" class="form-control select2" style="width: 100%;">
                                <option value="" selected disabled>Buscar...</option>
                                @foreach($medicine as $med)
                                    <option value="{{$med->id}}" 
                                            data-price="{{$med->price}}" 
                                            data-name="{{$med->name}}">
                                        {{$med->name}} - ${{ number_format($med->price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary w-100 mt-3" onclick="addToCart()">
                            <i class="fas fa-plus"></i> Agregar al Carrito
                        </button>

                        <hr>
                        <div class="alert alert-info">
                            <small><i class="fas fa-info-circle"></i> Seleccione un medicamento y presione agregar.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Cart -->
            <div class="col-md-7">
                <div class="card card-dark h-100">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-shopping-cart"></i> Carrito de Compra
                        </h3>
                    </div>
                    <div class="card-body table-responsive p-0" style="height: 400px;">
                        <table class="table table-striped table-valign-middle text-center cart-table">
                            <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th>Acción</th>
                            </tr>
                            </thead>
                            <tbody id="cartTableBody">
                                <!-- Cart Items will be here -->
                                <tr id="emptyCartRow">
                                    <td colspan="5" class="text-muted">El carrito está vacío</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h5>Total a Pagar:</h5>
                            </div>
                            <div class="col-6">
                                <div class="total-display" id="grandTotal">$0.00</div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg btn-block mt-3" id="btnCheckout" disabled>
                            <i class="fas fa-check-circle"></i> Confirmar Venta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Hidden Template for Form Submission -->
<div id="hiddenInputsContainer"></div>

@endsection

@section("scripts")
<script>
    // Cart Data Structure
    let cart = [];

    // Initialize Select2
    $(document).ready(function() {
        $('.select2').select2();
    });

    function addToCart() {
        const selector = $('#medicineSelector');
        const id = selector.val();
        
        if (!id) {
            toastr.warning('Por favor seleccione un medicamento.');
            return;
        }

        const option = selector.find(':selected');
        const name = option.data('name');
        const price = parseFloat(option.data('price'));

        // Check if exists
        const existingItem = cart.find(item => item.id == id);

        if (existingItem) {
            existingItem.qty += 1;
        } else {
            cart.push({
                id: id,
                name: name,
                price: price,
                qty: 1
            });
        }

        renderCart();
        selector.val(null).trigger('change'); // Reset selector
    }

    function renderCart() {
        const tbody = document.getElementById('cartTableBody');
        tbody.innerHTML = '';
        let total = 0;

        if (cart.length === 0) {
            tbody.innerHTML = '<tr id="emptyCartRow"><td colspan="5" class="text-muted">El carrito está vacío</td></tr>';
            document.getElementById('btnCheckout').disabled = true;
            document.getElementById('grandTotal').innerText = "$0.00";
            updateHiddenInputs();
            return;
        }

        document.getElementById('btnCheckout').disabled = false;

        cart.forEach((item, index) => {
            const subtotal = item.price * item.qty;
            total += subtotal;

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-left font-weight-bold">${item.name}</td>
                <td>$${item.price.toFixed(2)}</td>
                <td>
                    <input type="number" min="1" class="form-control qty-input mx-auto" 
                           value="${item.qty}" 
                           onchange="updateQty(${index}, this.value)">
                </td>
                <td class="font-weight-bold text-success">$${subtotal.toFixed(2)}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeFromCart(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('grandTotal').innerText = "$" + total.toFixed(2);
        updateHiddenInputs();
    }

    function updateQty(index, newQty) {
        newQty = parseInt(newQty);
        if (newQty < 1) newQty = 1;
        
        cart[index].qty = newQty;
        renderCart();
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function updateHiddenInputs() {
        const container = document.getElementById('hiddenInputsContainer'); // This doesn't exist in DOM, need to append to form? No, I put it inside container but outside form. Wait.
        // Actually, the hidden inputs MUST be inside the form to be submitted.
        // Let's create them inside the form.
        
        // Remove old hidden inputs from form
        const form = document.getElementById('posForm');
        // Clear previous hidden meds/qtys
        const oldInputs = form.querySelectorAll('.dynamic-input');
        oldInputs.forEach(el => el.remove());

        cart.forEach(item => {
            // Medicine ID
            const inputMed = document.createElement('input');
            inputMed.type = 'hidden';
            inputMed.name = 'med[]';
            inputMed.value = item.id;
            inputMed.classList.add('dynamic-input');
            form.appendChild(inputMed);

            // Quantity
            const inputQty = document.createElement('input');
            inputQty.type = 'hidden';
            inputQty.name = 'qty[]';
            inputQty.value = item.qty;
            inputQty.classList.add('dynamic-input');
            form.appendChild(inputQty);
        });
    }
</script>
@endsection