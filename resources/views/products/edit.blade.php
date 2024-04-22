<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wijzig reservering</title>
    @vite(['resources/scss/global.scss'])
</head>

<body>
    <div class="container">
        <h1>Edit product</h1>

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

        <div> <!-- Form to update score details -->
            <form action="{{ route('products.update', [$product->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <label for="name"><span>Name</span></label>
                <input type="text" name="name" id="name" value="{{ $product->name }}" required>

                <label for="price"><span>Price</span></label>
                <input type="number" name="price" id="price" value="{{ $product->price }}" required>

                <input type="submit" value="Edit">
            </form>
        </div>
    </div>
</body>

</html>