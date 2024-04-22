<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create product</title>
    @vite(['resources/scss/global.scss'])
</head>
<body>
    <div class="container">
        <h1>Geleverde Producten</h1>

        <div>
            <form action="{{ route('products.store') }}" method="POST">
                @csrf
                <label for="name"><span>Name</span></label>
                <input type="text" name="name" id="name" required>

                <label for="price"><span>Price</span></label>
                <input type="decimal" name="price" id="price" required>

                <input type="submit" value="Sla op">
            </form>

            @if(Session::has('error'))
            <div class="alert alert-error">
                {{ Session::get('error') }}
            </div>
            @endif
        </div>
    </div>
</body>
</html>