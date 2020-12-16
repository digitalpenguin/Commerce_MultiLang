<?php
/**
 * Default English Lexicon Entries for Commerce_MultiLang
 *
 * @package commerce_multilang
 * @subpackage lexicon
 */

$_lang['commerce_multilang'] = 'Commerce_MultiLang';

$_lang['commerce.CMLProduct'] = 'Multilingual Product';
$_lang['commerce.add_CMLProduct'] = 'Add Multilingual Product';

$_lang['commerce_multilang.menu.commerce_multilang'] = 'Commerce_MultiLang';
$_lang['commerce_multilang.menu.commerce_multilang_desc'] = 'Multilingual products for Commerce.';

$_lang['commerce_multilang.global.search'] = 'Search';

$_lang['commerce_multilang.product.products'] = 'Products';
$_lang['commerce_multilang.product.intro_msg'] = 'Manage your products.';

$_lang['commerce_multilang.product.name'] = 'Name';
$_lang['commerce_multilang.product.variation_create'] = 'Create Product Variation';
$_lang['commerce_multilang.product.variation_edit'] = 'Edit Product Variation';
$_lang['commerce_multilang.product.variation_remove'] = 'Remove Product Variation';
$_lang['commerce_multilang.product.sku'] = 'SKU';
$_lang['commerce_multilang.product.type'] = 'Type';
$_lang['commerce_multilang.product.alias'] = 'Alias';
$_lang['commerce_multilang.product.stock'] = 'Stock';
$_lang['commerce_multilang.product.colour'] = 'Colour';
$_lang['commerce_multilang.product.size'] = 'Size';
$_lang['commerce_multilang.product.variations_intro'] = 'Specify Product Variations that are available to your products. Create a product type and then assign product variations to it.<br> For example, if your product type is clothing then you might want a "size" variation so you can have the same product with different sizes for sale.';
$_lang['commerce_multilang.product.image'] = 'Image';
$_lang['commerce_multilang.product.description'] = 'Description';
$_lang['commerce_multilang.product.category'] = 'Category';
$_lang['commerce_multilang.product.position'] = 'Position';
$_lang['commerce_multilang.product.weight'] = 'Weight';
$_lang['commerce_multilang.product.weight_unit'] = 'Weight Unit';
$_lang['commerce_multilang.product.unit'] = 'Unit';
$_lang['commerce_multilang.product.delivery_type'] = 'Delivery Type';
$_lang['commerce_multilang.product.price'] = 'Price';
$_lang['commerce_multilang.product.tax_group'] = 'Tax Group';
$_lang['commerce_multilang.product.create'] = 'Create product';
$_lang['commerce_multilang.product.update'] = 'Update product';
$_lang['commerce_multilang.product.remove'] = 'Remove product';
$_lang['commerce_multilang.product.remove_confirm'] = 'Are you sure you want to remove this product?';
$_lang['commerce_multilang.product.no_products'] = 'There are no products currently listed in this category.';

$_lang['commerce_multilang.err.product_name_ae'] = 'A product already exists with that name.';
$_lang['commerce_multilang.err.product_alias_ae'] = 'An alias can\'t be generated from this product name as it already exists. Try creating a different name then change it after.';
$_lang['commerce_multilang.err.product_nf'] = 'Product not found.';
$_lang['commerce_multilang.err.product_name_ns'] = 'Name is not specified.';
$_lang['commerce_multilang.err.product_remove'] = 'An error occurred while trying to remove the product.';
$_lang['commerce_multilang.err.product_save'] = 'An error occurred while trying to save the product.';

/* Product Images */
$_lang['commerce_multilang.product_image.title'] = 'Title';
$_lang['commerce_multilang.product_image.main'] = 'Main';
$_lang['commerce_multilang.product_image.alt'] = 'Alternative Text (SEO)';
$_lang['commerce_multilang.product_image.image'] = 'Image';
$_lang['commerce_multilang.product_image.description'] = 'Description';
$_lang['commerce_multilang.product_image.position'] = 'Position';
$_lang['commerce_multilang.product_image.add'] = 'Add Image';
$_lang['commerce_multilang.product_image.edit'] = 'Edit image';
$_lang['commerce_multilang.product_image.make_main'] = 'Make main image';
$_lang['commerce_multilang.product_image.make_main_confirm'] = 'Are you sure you want to make this the main image?';
$_lang['commerce_multilang.product_image.remove'] = 'Remove image';
$_lang['commerce_multilang.product_image.remove_confirm'] = 'Are you sure you want to remove this image?';

$_lang['commerce_multilang.err.product_image_name_ae'] = 'A product image already exists with that name.';
$_lang['commerce_multilang.err.product_image_nf'] = 'Product image not found.';
$_lang['commerce_multilang.err.product_image_title_ns'] = 'Image title is not specified.';
$_lang['commerce_multilang.err.product_image_remove'] = 'An error occurred while trying to remove the image.';
$_lang['commerce_multilang.err.product_image_save'] = 'An error occurred while trying to save the image.';

/* Product Variations */
$_lang['commerce_multilang.product_variation.product_variations'] = 'Product Variations';
$_lang['commerce_multilang.product_variation.intro'] = 'Product variations are complete products with their own SKU and alias however by default they are hidden from the customer when browsing product listings. Customers can select to buy a product variation (e.g. colour, size etc.) instead of the default listed product on the product detail screen.';
$_lang['commerce_multilang.product_variation.remove_error'] = 'This variation is currently being used by active products. You need to change the related product types before removing.';

/* Product Types */
$_lang['commerce_multilang.product_type.name'] = 'Name';
$_lang['commerce_multilang.product_type.description'] = 'Description';
$_lang['commerce_multilang.product_type.position'] = 'Position';
$_lang['commerce_multilang.product_type.create'] = 'Create Product Type';
$_lang['commerce_multilang.product_type.edit'] = 'Edit product type';
$_lang['commerce_multilang.product_type.update'] = 'Update product type';
$_lang['commerce_multilang.product_type.remove'] = 'Remove product type';
$_lang['commerce_multilang.product_type.remove_confirm'] = 'Are you sure you want to remove this product type?';

$_lang['commerce_multilang.err.product_type_name_ae'] = 'A product type already exists with that name.';
$_lang['commerce_multilang.err.product_type_nf'] = 'Product type not found.';
$_lang['commerce_multilang.err.product_type_name_ns'] = 'Product type name is not specified.';
$_lang['commerce_multilang.err.product_type_remove'] = 'An error occurred while trying to remove the product type.';
$_lang['commerce_multilang.err.product_type_save'] = 'An error occurred while trying to save the product type.';

/* Product Type Variations */
$_lang['commerce_multilang.product_type_variation.name'] = 'Name';
$_lang['commerce_multilang.product_type_variation.display_name'] = 'Display Name';
$_lang['commerce_multilang.product_type_variation.value'] = 'Value';
$_lang['commerce_multilang.product_type_variation.language'] = 'Language';
$_lang['commerce_multilang.product_type_variation.description'] = 'Description';
$_lang['commerce_multilang.product_type_variation.position'] = 'Position';
$_lang['commerce_multilang.product_type_variation.create'] = 'Create Variation';
$_lang['commerce_multilang.product_type_variation.edit'] = 'Edit variation';
$_lang['commerce_multilang.product_type_variation.remove'] = 'Remove variation';
$_lang['commerce_multilang.product_type_variation.remove_confirm'] = 'Are you sure you want to remove this variation?';

$_lang['commerce_multilang.err.product_type_variation_nf'] = 'Variation not found.';
$_lang['commerce_multilang.err.product_type_variation_name_ns'] = 'Variation name is not specified.';
$_lang['commerce_multilang.err.product_type_variation_remove'] = 'An error occurred while trying to remove the variation.';
$_lang['commerce_multilang.err.product_type_variation_save'] = 'An error occurred while trying to save the variation.';