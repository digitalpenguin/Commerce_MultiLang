<h1>[[+cml.name]]</h1>
<img title="[[+cml.title]]" alt="[[+cml.alt]]" src="[[+cml.image]]">
<p>[[+cml.description]]</p>

<form method="post" action="[[~[[++commerce.cart_resource]]? &scheme=`full`]]">
    <input type="hidden" name="add_to_cart" value="1">
    [[+cml.variations:notempty=`<select name="products" onchange="setProduct(value)">
        [[+cml.variations]]
    </select>`]]<br><br>
    <label for="add-quantity">Quantity</label>
    <input id="add-quantity" type="number" name="products[ [[+cml.id]] ][quantity]" value="1">
    <input type="submit" value="Add to Cart">
</form>
<script>
    function setProduct(value) {
        document.getElementById('add-quantity').setAttribute('name','products['+value+'][quantity]');
    }
</script>
