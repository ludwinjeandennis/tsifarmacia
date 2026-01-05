<x-mail::message>
# Confirmar Precio del Pedido

Información de tu Pedido:

<x-mail::table>
| Artículo      | Cantidad     | Precio   |
| ------------- |:-------------:| --------:|
@foreach($orderInfo as $info)
| {{$info['medicine']}}      | {{$info['quantity']}}      | {{$info['price']}}$      |
@endforeach
| Precio Total  |            | {{$totalPrice}}    |
</x-mail::table>


<x-mail::button :url="$confirmUrl" color="success">
    Confirmar Pedido
</x-mail::button>

<x-mail::button :url="$cancelUrl" color="error">
    Cancelar Pedido
</x-mail::button>


</x-mail::message>