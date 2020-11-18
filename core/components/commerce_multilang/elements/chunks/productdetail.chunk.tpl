<h1>[[+cml.name]]</h1>
<h2>[[+cml.description]]</h2>

<img title="[[+cml.main_image_title]]" alt="[[+cml.main_image_alt]]" src="/[[+cml.main_image]]">
<div style="display:flex;">
    [[+cml.secondary_images]]
</div>

<form method="post" action="[[~[[++commerce.cart_resource]]? &scheme=`full`]]">
    <input type="hidden" name="add_to_cart" value="1">
    <input id="cml-product-id" type="hidden" name="product" value="[[+cml.id]]">
    [[+cml.variations:notempty=`<label>Select: </label><select name="products" onchange="setProduct(value)">
        [[+cml.variations]]
    </select>`]]
    <br>
    <label for="add-quantity">Quantity</label>
    <input type="number" name="quantity" value="1">
    <input type="submit" value="Add to Cart">
</form>

<div>
    [[+cml.content]]
</div>

<script>
    function setProduct(value) {
        document.getElementById('cml-product-id').setAttribute('value',value);
    }
</script>