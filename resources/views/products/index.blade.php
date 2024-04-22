<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products overview</title>
    @vite(['resources/scss/global.scss'])
</head>

<body>
    <div class="container">
        <h1>Products</h1>

        <!-- If controller sends a success or error message, display them -->
        @if(Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
        @elseif (Session::has('error'))
        <div class="alert alert-error">
            {{ Session::get('error') }}
        </div>
        @endif

        @if (auth()->user()->is_admin)
        <a href="{{route('products.create')}}">Add a new product</a>
        @endif
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price in USD</th>
                    <th>Price in EUR</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productList as $product) <!-- Foreach Loop to display all product details -->
                <tr>
                    <td>{{$product->id}}</td>
                    <td>{{$product->name}}</td>
                    <td>{{$product->price}}</td>
                    <td>{{$product->price_eur}}</td>
                </tr>
                @empty
                <tr>
                    <td>No products found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>