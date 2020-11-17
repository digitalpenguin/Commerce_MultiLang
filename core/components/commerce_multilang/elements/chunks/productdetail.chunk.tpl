<h1>[[+cml.name]]</h1>
<img title="[[+cml.main_image_title]]" alt="[[+cml.main_image_alt]]" src="/[[+cml.main_image]]">
<p>[[+cml.description]]</p>
[[+cml.secondary_images]]

<form method="post" action="[[~[[++commerce.cart_resource]]? &scheme=`full`]]">
    <input type="hidden" name="add_to_cart" value="1">
    <input id="cml-product-id" type="hidden" name="product" value="[[+cml.id]]">
    [[+cml.variations:notempty=`<select name="products" onchange="setProduct(value)">
        [[+cml.variations]]
    </select>`]]<br><br>
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