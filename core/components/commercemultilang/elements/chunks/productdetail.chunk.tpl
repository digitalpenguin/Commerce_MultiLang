<h1>[[+name]]</h1>
<img title="[[+title]]" alt="[[+alt]]" src="[[+image]]">
<p>[[+description]]</p>

<form method="post" action="[[~[[++commerce.cart_resource]]]]">
    <input type="hidden" name="add_to_cart" value="1">
    [[+variations:notempty=`<select name="products" onchange="setProduct(value)">
        [[+variations]]
    </select>`]]<br><br>
    <label for="add-quantity">Quantity</label>
    <input id="add-quantity" type="number" name="products[ [[+id]] ][quantity]" value="1">
    <input type="submit" value="Add to Cart">
</form>
<script>
    function setProduct(value) {
        document.getElementById('add-quantity').setAttribute('name','products['+value+'][quantity]');
    }
</script>
