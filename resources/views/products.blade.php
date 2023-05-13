<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Products') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg" style="padding:10px;">
        <div style="text-align: right">
        <a style="padding:8px 15px;display:inline-block;border:1px solid #ccc;border-radius:3px;background:#eee;"
           href="{{route('products.add')}}">+ Add Product</a>
        </div>
        <style>
          .products { width: 100%; }
          .products td { padding:10px; font-size: 16px; }
          .products thead td { color:#666; font-size:12px; }
        </style>
        <div class="container">
          <table class="products">
            <thead>
            <tr>
              <td>Edit</td>
              <td>Image</td>
              <td>Name</td>
              <td>Description</td>
              <td>Price</td>
              <td>Quantity</td>
            </tr>
            </thead>
            @foreach ($products as $product)
              <tr>
                <td><a style="color:blue;"
                       href="{{route('products.edit', $product->id)}}">Edit</a></td>
                <td>
                  @if ($product->image)
                    <img src="{{config('app.url')}}/{{ $product->image }}" style="max-width:50px;" />
                  @endif
                </td>
                <td>
                  {{ $product->name }}
                </td>
                <td>
                  <div style="font-size:14px;color:#555;">{{ $product->description }}</div>
                </td>
                <td>${{ $product->price }}</td>
                <td>{{ $product->quantity }}</td>
              </tr>
            @endforeach
          </table>
        </div>

        {{ $products->links() }}

      </div>

    </div>
  </div>
</x-app-layout>
