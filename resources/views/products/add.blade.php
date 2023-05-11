
        <form action="{{route('api.products.store')}}"
              method="post"
              enctype="multipart/form-data">

          @csrf

          <div>
            <label for="name">Name</label>
            <input id="name" name="name" type="text" />
          </div>
          <div>
            <label for="description">Description</label>
            <textarea id="description" name="description"></textarea>
          </div>
          <div>
            <label for="price">Price</label>
            <input id="price" name="price" type="text" />
          </div>
          <div>
            <label for="quantity">Quantity</label>
            <input id="quantity" name="quantity" type="text" />
          </div>
          <div>
            <label for="image">Image</label>
            <input id="image" name="image" type="file" />
          </div>
          <button>Add</button>
        </form>


