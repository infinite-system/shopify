<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Products') }}  » <a class="link" style="float:right;padding-right:25px;font-size:18px; display:inline-block;"
                              href="{{route('products.add')}}">+ Add Product</a>
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg" style="">
        <div style="text-align: right">

        </div>
        <style>
          .products { width: 100%; }
          .products tr { cursor:pointer;  }
          .products td { padding:10px 0 10px 20px; font-size: 16px;}
          .products tr:hover {background: #f3f7f8; }
          .products tr:hover td {}
          .products thead td { color:#666; font-size:12px; }
          a.link { color:#0066ff;font-size:14px; }
          a.link:hover { color: #054bb2;font-size:14px;text-decoration: underline }
          a.del { color: #ff001e;font-size:12px; }
          a.del:hover { color: #c2000f;font-size:12px; }
        </style>
        <div class="container2">
          <table class="products" cellspacing="1">
            <thead>
            <tr>
              <td>Edit</td>
              <td>Image</td>
              <td>Name</td>
              <td>Description</td>
              <td>Price</td>
              <td>Quantity</td>
              <td>Delete</td>
            </tr>
            </thead>
            @foreach ($products as $product)
              <tr onclick="window.location.href='{{route('products.edit', $product->id)}}'">
                <td><a class="link"
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
                <td><a class="del"
                       onclick="event.stopPropagation();deleteProduct({{$product->id}})"
                       href="javascript:void(0);"
                  >Delete</a></td>
              </tr>
            @endforeach
          </table>

          <div style="padding:25px 10px 15px;">
            Delete Reponse: <span style="color:#555;font-size:14px;">Try deleting an item & then refresh the page.</span><br />
            <pre id="response"></pre>
          </div>
        </div>

        <script>

          const formData = new FormData();
          formData.append("_method", 'DELETE');

          function deleteProduct(id) {

            const deleteIt = confirm('Are you sure?')

            if (deleteIt) {

              $.ajax({
                type: "POST",
                url: "{{config('app.url').'/api/products/'}}" + id,
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                  "accept": "application/json",
                  "Authorization": "Bearer {{ Auth::user()->tokens[0]->plain_token ?? '' }}"
                }
              }).fail(function(xhr, data) {
                console.log(xhr.responseText)
                $('#response').html(JSON.stringify(xhr.responseJSON, null, 2))
              })
                .done(function(data) {
                  console.log(data);
                  $('#response').html(JSON.stringify(data, null, 2))
                });

            }
          }
        </script>

        {{ $products->links() }}

      </div>

    </div>
  </div>
</x-app-layout>
