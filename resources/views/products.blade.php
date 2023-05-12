<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Products') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg" style="padding:10px;">
        <a style="padding:10px;display:inline-block;border:1px solid grey;border-radius:3px;background:#eee;"
           href="{{route('products.add')}}">Add Product</a>
        <style>
          .products td { padding:10px; font-size: 18px; }
        </style>
        <div class="container">
          <table class="products">
            <tr>
              <td>Image</td>
              <td>Name</td>
              <td>Price</td>
              <td>Quantity</td>
            </tr>
            @foreach ($products as $product)
              <tr>
                <td>
                  <img src="/{{ $product->image }}" style="max-width:50px;" />
                </td>
                <td>
                  {{ $product->name }}
                  <div style="font-size:12px;color:#555;">{{ $product->description }}</div>
                </td>
                <td>${{ $product->price }}</td>
                <td>{{ $product->quantity }}</td>
                <td><a style="color:blue;"
                       href="{{route('products.edit', $product->id)}}">Edit</a></td>
              </tr>
            @endforeach
          </table>
        </div>

        {{ $products->links() }}

      </div>

    </div>
  </div>
</x-app-layout>
