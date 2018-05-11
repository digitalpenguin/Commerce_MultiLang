<h1>[[+name]]</h1>
<img title="[[+title]]" alt="[[+alt]]" src="/[[+image]]">
<p>[[+description]]</p>

<form method="post" action="[[~[[++commerce.cart_resource]]]]">
    <input type="hidden" name="add_to_cart" value="[[+id]]">

    <label for="add-quantity">Quantity</label>
    <input id="add-quantity" type="number" name="products[1][quantity]" value="1">

    <input type="submit" value="Add to Cart">
</form>