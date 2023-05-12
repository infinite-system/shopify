<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Products') }} » Add
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="">

        <div class="md:grid md:grid-cols-3 md:gap-6">
          <div class="md:col-span-1 flex justify-between">
            <div class="px-4 sm:px-0">
              <h3 class="text-lg font-medium text-gray-900">Add Product</h3>

              <p class="mt-1 text-sm text-gray-600">
                Add product to the database and Shopify platform.
              </p>
            </div>

            <div class="px-4 sm:px-0">

            </div>
          </div>

          <div class="mt-5 md:mt-0 md:col-span-2">
            <form id="form">
              <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                <div class="grid grid-cols-6 gap-6">
                  <div class="col-span-6 sm:col-span-4">
                    <label class="block font-medium text-sm text-gray-700" for="name">Name</label>
                    <input id="name" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" placeholder="name" name="name" type="text" />
                  </div>
                  <div class="col-span-6 sm:col-span-4">
                    <label class="block font-medium text-sm text-gray-700" for="description">Description</label>
                    <textarea class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" id="description" name="description"></textarea>
                  </div>
                  <div class="col-span-6 sm:col-span-4">
                    <label class="block font-medium text-sm text-gray-700" for="price">Price</label>
                    <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" id="price" name="price" type="text" />
                  </div>
                  <div class="col-span-6 sm:col-span-4">
                    <label class="block font-medium text-sm text-gray-700" for="quantity">Quantity</label>
                    <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" id="quantity" name="quantity" type="text" />
                  </div>
                  <div class="col-span-6 sm:col-span-4">
                    <label class="block font-medium text-sm text-gray-700" for="image">Image</label>
                    <input id="image" name="image" type="file" />
                  </div>

                  <div class="col-span-6 sm:col-span-4">
                    <label class="block font-medium text-sm text-gray-700" for="image">Token</label>
                    <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" id="token" name="token" type="text" value="{{ Auth::user()->tokens[0]->plain_token ?? '' }}" />
                  </div>

                </div>
                <br />
                <pre id="response"></pre>
              </div>


              <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">

                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                  Add
                </button>
              </div>
            </form>
          </div>
        </div>


        <script>
          $(document).ready(function () {

            $("#form").submit(function (event) {

              event.preventDefault();

              // const formData = [];
              const formData = new FormData();

              let imageFile = $('input[type="file"][name="image"]')[0].files[0]
              imageFile = imageFile ? imageFile : ''

              formData.append("name", $('input[name="name"]').val());
              formData.append("description", $('textarea[name="description"]').val());
              formData.append("price", $('input[name="price"]').val());
              formData.append("quantity", $('input[name="quantity"]').val());
              formData.append("image", imageFile);

              $.ajax({
                type: "POST",
                url: "{{route('api.products.store')}}",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                  "accept": "application/json",
                  "Authorization": "Bearer "+$('input[name="token"]').val()
                }
              }).fail(function(xhr, data){

                console.log(xhr.responseText)
                $('#response').html(JSON.stringify(xhr.responseJSON, null, 2))
              })
                .done(function (data) {
                  console.log(data);
                  $('#response').html(JSON.stringify(data, null,2))
                });
            });
          });
        </script>



      </div>
    </div>
  </div>
</x-app-layout>
