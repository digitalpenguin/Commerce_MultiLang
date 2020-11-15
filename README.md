---------------------------------------
Commerce_MultiLang
---------------------------------------
Version: 1.0.0-alpha1

You can find documentation in the wiki at [https://github.com/digitalpenguin/CommerceMultiLang/wiki](https://github.com/digitalpenguin/CommerceMultiLang/wiki)

[![CommerceMultiLang Product Variations](https://github.com/digitalpenguin/CommerceMultiLang/blob/master/core/components/commerce_multilang/docs/img/cml-variations.png "Click to zoom in!")](https://raw.githubusercontent.com/digitalpenguin/CommerceMultiLang/master/core/components/commercemultilang/docs/img/cml-variations.png)

WORK IN PROGRESS

TODO:
- Allow an initial image for the product create form.
- Cache everything!
- Build a breadcrumbs snippet that includes categories (resources) and products.
- Build a product bundle grid.
- Allow products to belong to multiple categories. A default category can replace the current singular category.

There is now a package which can be used to install the extra via the MODX package manager.

This is a wrapper for modmore's excellent MODX extra called Commerce. https://www.modmore.com/commerce/
Commerce doesn't come with multi-lingual products out of the box so CommerceMultiLang adds this for websites with more than one language.
CommerceMultiLang is an unofficial extension to Commerce made by Murray Wood at Digital Penguin Ltd Hong Kong. https://www.digitalpenguin.hk

Commerce is a a premium extra for MODX and using CommerceMultiLang requires a valid Commerce license.
https://www.modmore.com/commerce/

Requirements:
- MODX 2.6.5 or higher.
- Commerce 1.2.x or higher.
- Babel: https://modx.com/extras/package/babel
- A language routing extra. We recommend LangRouter: https://modx.com/extras/package/langrouter (Others may work but they haven't been tested.)

Dependencies
--------------
- Make sure Babel and LangRouter are already setup and working. http://jako.github.io/LangRouter/
- You'll need different contexts for each language. These should have been set up when you were installing Babel and LangRouter.
- Make sure friendly URLs and friendly alias paths are turned on.

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

