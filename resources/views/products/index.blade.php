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