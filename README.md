
Commerce_MultiLang
=
**A multilingual products module for Commerce on MODX.**

*Version: 1.0.0-alpha1*

Developed by Murray Wood at Digital Penguin in Hong Kong.

Alpha Version (soon to be beta)
-
CAUTION - This module is not yet recommended for production. If you find any bugs please create an issue for it.
While in Alpha, upgrading from a previous version will most likely break your site since there are a lot of fundamental changes being made. 

Introduction
-
Commerce_MultiLang brings multilingual products to Commerce on MODX. Manage the extended products on a 
custom-built manager page which automatically replaces the core Commerce products page, as well as the 
Commerce template variables.

Product variations are also built into this module which allow you to set your own product type and add whatever 
variations you choose. Similar to how you previously might have done with the product matrix TV, only now you're 
not just limited to two.

Variations are options that a customer can select when purchasing a product.
For example, you could create a ***product type*** called "Clothing" and assign three variations to the product type. Such as:
- colour
- size 
- age-range

Note that you're not limited to three, a product type can have as many variations as needed. Or none at all.
Commerce_Multilang reads the `cultureKey` setting on each context you have set up so it knows which languages to use. 

Once you've set a product to be a certain product type, you'll see the assigned variations in the update product window.
The variation fields will automatically display on each language tab waiting to be filled in.

Commerce_MultiLang is attempting to keep products separate to resources, so one product does not equal one resource.
However, an option for both might be possible in the future. 

Requirements
-
Commerce is a premium extra for MODX, and although Commerce_MultiLang is open source, Commerce requires a valid license.
https://www.modmore.com/commerce/

Requirements:
- MODX 2.6.5 or higher.
- Commerce 1.2.x or higher.
- Babel: https://modx.com/extras/package/babel
- A language routing extra. We recommend LangRouter: https://modx.com/extras/package/langrouter (Others may work but they haven't been tested.)

Dependencies
-
- Make sure Babel and LangRouter are already setup and working. http://jako.github.io/LangRouter/
- You'll need different contexts for each language. These should have been set up when you were installing Babel and LangRouter.
- Make sure friendly URLs and friendly alias paths are turned on.
<hr>

System Settings
---------------
- A system setting called commerce_multilang.default_lang needs to be set with your default language code. (e.g. en, zh, fr etc.) This setting must be the cultureKey that is set on one of your contexts.


Required Resources
------------------
- *Shop* - On each context add a "Shop" resource. (Of course the language of "Shop" will differ depending on the language.)
- *Categories* - On each context, under "Shop", add a resource that will be the parent for all your categories (we use resources for categories but not products). You could name it 'Categories' for example.
- *Cart* - On each context, under "Shop" but not under "Categories", add the "Cart" resource.
- *Checkout* - On each context, under "Shop" but not under "Categories", add the "Checkout" resource.

*Viewport* - On each context, you need to also add a blank resource to be used as a viewport. This can go anywhere (hide from menus) and will have the resource data loaded into it.

Context Settings
----------------
Add these to each context. (In addition to the context settings required by Babel and LangRouter.)

- *commerce_multilang.category_root_id* - This is the id of the "Categories" resource for that context.
- *commerce.cart_resource* - The id of your cart resource.
- *commerce.checkout_resource* - The id of your checkout resource.
- *commerce_multilang.product_detail_page* - This is the id of your viewport resource. (Product detail page will be loaded into this resource by a plugin.)

Snippets
-

**_cml.getProductList_**

cml.getProductList retrieves a list of multilingual products to display on a category page or home page etc. 
You can format the output to your taste. The products will display in the language the `cultureKey` on the 
current context is set to.

`Snippet parameters`

| Parameter         | Value                            | Description                            | 
| :-------------    |:--------                         |:------------------------------------- | 
| &categories       | resource `id`                    | Specify the resource id of the category you wish to display products for. Currently a product can only be assigned to a single category but this will change soon. Once implemented, you will be able to assign multiple categories by listing their id numbers separated by commas.  | 
| &tpl              | chunk name                        | Specify the name of the chunk you wish to use as a template for each product preview.  |

`Example`
```
[[!cml.getProductList?
    &categories=`21`
    &tpl=`productPreview`
    &sortdir=`DESC`
    &sortby=`published_on`
    &limit=`25`
]]
```

`Snippet placeholders`

| Placeholder                   | Description                            
| :-------------                |:-------------------------------------  
| `[[+cml.list.image]]`         | Displays the URI to your main image.
| `[[+cml.list.product_link]]`  | Link to your product detail page.
| `[[+cml.list.name]]`          | Product name (displays the language set by the `cultureKey` for the current context.
| `[[+cml.list.description]]`   | Product description (displays the language set by the `cultureKey` for the current context.
| `[[+cml.list.sku]]`           | Product SKU.
| `[[+cml.list.price]]`         | Price of the product.

___

**_cml.productDetail_**

cml.productDetail goes on your product detail resource. This resource acts as a viewport and all products are loaded through
the single resource 

| Parameter        | Value          | Description                            | 
| :-------------   | :-----         | :------------------------------------- | 
| &variationNames  | `1`            | Optional: Set this to `1` if you would like the names/labels of the product variations to display in the select box in addition to the values. e.g. `Color: Blue, Size: Medium, Age Range: Adults` instead of just `Blue, Medium, Adults`. | 
| &tpl             | chunk name     | Optional: Specify the name of the chunk you wish to use as a template for each product preview. If you specify `default` it will use the default chunk. If you don't use this parameter, the placeholders will be available on the base template itself without constraining the output within a chunk.  |
| &imageTpl        | chunk name     | Optional: Specify the name of the chunk you wish to use as a template for each of the secondary gallery images (if any).  |
| &variationTpl    | chunk name     | Optional: Specify the name of the chunk you wish to use as a template for variation select options.  |

`Example`
```
[[!cml.productDetail?
    &tpl=`myChunkTpl`
    &imageTpl=`myImageChunkTpl`
    &variationNames=`1`
]]
```

`Snippet placeholders`

| Placeholder                   | Description                            
| :-------------                |:-------------------------------------  
| `[[+cml.product_id]]`         | The product's id. This can be useful to use if add your own custom snippets (product reviews, related products etc.) and you want to use the product id as a parameter.
| `[[+cml.main_image]]`         | Displays the URI to your main image.
| `[[+cml.secondary_images]]`   | Displays all secondary images for the product. Output can be customised by the `&imageTpl` snippet parameter.
| `[[+cml.name]]`               | Product name (displays the language set by the `cultureKey` for the current context.
| `[[+cml.description]]`        | Product description (displays the language set by the `cultureKey` for the current context.
| `[[+cml.sku]]`                | Product SKU.
| `[[+cml.price]]`              | Price of the product.
| `[[+cml.variations]]`         | Displays select options for product variations. These can be modified with the `variationTpl` and `variationNames` snippet parameters.
| `[[+cml.content]]`            | Main content area for the product (displays the language set by the `cultureKey` for the current context.

___

**_cml.getLanguageLinks_**

`cml.getLanguageLinks` works in much the same way as Babel's `BabelLinks` snippet, but it is only meant for the product detail page.
As the product detail page doesn't function as a normal resource (just a viewport), the snippet `BabelLinks` doesn't work.
This is why `cml.getLanguageLinks` exists.

| Parameter        | Value          | Description                            
| :-------------   | :-----         | :------------------------------------- 
| &separator       | Any string     | Optional: Adds a divider you specify between the language links.
| &tpl             | chunk name     | Optional: Add your own custom chunk tpl for the output of each link.

`Example:`
```
[[!cml.getLanguageLinks?
    &separator=` / `
    &tpl=`myChunkTpl`
]]
```

___

**IMPORTANT**: There is no provided snippet to list your product categories. Since categories are resources, you can
use either `getResources` or `pdoResources` for this. Note that `pdoResources` is part of the `pdoTools` extra.

Use the context setting `[[++commerce_multilang.categories_root_id]]` as the value for the `parents` parameter.

`Example:`
```
[[pdoResources?
    &parents=`[[++commerce_multilang.categories_root_id]]`
    &tpl=`myCategoryChunkTpl`
]]
```

Chunks
-
It's important to remember not to modify the default chunks as they will get overwritten on future upgrades.
Always duplicate the chunk and give it a unique name, which you then specify as a snippet parameter.




WORK IN PROGRESS
==

TODO:
- Mimic the Commerce top nav tabs so the user feels like they haven't actually left the Commerce cmp.
- Add a parameter to the `cml.productDetail` snippet that allows variations to be separated into different select boxes instead of all variations shown in the same option row. For example instead of `Size:Medium, Colour:Blue` on the one row, you could display a `Sizes` select box and a `Colours` select box.
- Build a breadcrumbs snippet that includes categories (resources) and products.
- Build a product bundle grid.
- Allow products to belong to multiple categories. A default category can replace the current singular category.



