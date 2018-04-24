---------------------------------------
CommerceMultiLang
---------------------------------------
Version: 0.0.1
Author: Murray Wood <murray@digitalpenguin.hk>
Company: Digital Penguin Ltd (Hong Kong)
---------------------------------------

This is a wrapper for Modmore's excellent MODX extra called Commerce. https://www.modmore.com/commerce/
Commerce doesn't come with multi-lingual products out of the box so CommerceMultiLang adds this for websites with more than one language.
CommerceMultiLang is an unofficial extension to Commerce made by Murray Wood at Digital Penguin Ltd Hong Kong. https://www.digitalpenguin.hk

Commerce is a a premium extra for MODX and using CommerceMultiLang requires a valid Commerce license.
https://www.modmore.com/commerce/pricing/

Even though Commerce is HTML-first, this extra has been built with the UI framework the rest of MODX currently uses in the manager: ExtJS. (Sorry Mark! :) )
Special thanks to @dimmy (Dimitri Hilverda) for advice given about some functionality such as loading with the resource viewport.

Requirements:
- MODX (of course!)
- Commerce
- Babel: https://modx.com/extras/package/babel
- A language routing extra. We recommend LangRouter: https://modx.com/extras/package/langrouter (Others may work but they haven't been tested.)

Setup:
- As we're still in early Alpha, there's no package yet. So it's really only for experienced MODX users at the moment. GPM didn't like extending from Commerce so it'll need to be built manually.
- You can clone the Github repo and add the files in the correct spots i.e. /assets/components/commercemultilang/ and /core/components/commercemultilang/
- Make a namespace called commercemultilang in the MODX manager that points to the directories above.
- Make a menu option that points to the CommerceMultiLang CMP e.g. /manager/?a=home&namespace=commercemultilang

- Make sure Babel and LangRouter are already setup and working. http://jako.github.io/LangRouter/
- You'll need different contexts for each language. These should have been set up when you were installing Babel and LangRouter.

- Each of these contexts should have a cultureKey setting and the value should be a language key such as en/zh/fr/de etc.
- make a system setting (not context setting) called commercemultilang.default_lang and set it to your default language. Example: en
- On each context, add a resource that will be the parent for all your categories (we use resources for categories but not products). You could name it 'Shop' for example.
- Then add a context setting to each context called commercemultilang.category_root_id and set the id of your parent category for that context.
- Add another resource to each context (NOT as a child to the parent category resource) and call it something like "Product Detail". This is the viewport that will have the resource data loaded into it.
- Add a context setting to each context called commercemultilang.product_detail_page and set the value to the idea of your product detail resource for that context.
- Add the redirectToProduct plugin and have it fire on the onPageNotFound system event.

