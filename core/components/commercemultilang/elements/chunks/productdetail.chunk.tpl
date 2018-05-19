<h1>[[+name]]</h1>
<img title="[[+title]]" alt="[[+alt]]" src="[[+image]]">
<p>[[+description]]</p>

<form method="post" action="[[~[[++commerce.cart_resource]]]]">
    <input type="hidden" name="add_to_cart" value="1">

    <label for="add-quantity">Quantity</label>
    <input id="add-quantity" type="number" name="products[ [[+id]] ][quantity]" value="1"><br>
    <select>
        [[+variations]]
    </select>
    <br>
    <input type="submit" value="Add to Cart">
</form>
