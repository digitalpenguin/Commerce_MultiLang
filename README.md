
Commerce_MultiLang
=
**A multilingual products module for Commerce on MODX.**

*Version: 1.0.0-pl*

Developed by Murray Wood at Digital Penguin in Hong Kong.


Introduction
-
Commerce for MODX defines products separately from any context or resource. This is a very good thing as it allows for faster speeds and more flexibility. It's also not opinionated, so you can build up your shop in any manner.
Having such flexibility however, does create difficulty in managing multiple language translations for each product. One option would be to use the lexicons, but that paves the way for a convoluted workflow when updating/creating products.

This is solved by Commerce_MultiLang.

Commerce_MultiLang brings multilingual products to Commerce on MODX. Multilingual Products are a custom product type, and can be selected when creating or updating a product.
They behave the same way as a regular product only they also carry easily editable translations.

![commerce_multilang](https://user-images.githubusercontent.com/5160368/103473202-f2625900-4dd0-11eb-852c-b8770cb44609.gif)

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

How it Works
==

Commerce_MultiLang will scan your contexts and include any context that has `cultureKey` context setting.
When creating a new product, you will see the option to create a `Multilingual Product`. In the edit window of a multilingual product, there is an extra tab which allows for the product name and product description to be entered in each language besides the default. The default language is entered the same as any standard product.
When the multilingual product is viewed on the customer-facing side of the webshop, the appropriate language will be displayed according to the active context.


Roadmap
==
- Allow for multilingual images to be used within each product. This way if text is included in the image, you can have a different one for each context.


History
==
This module used to be a lot larger. It was decided to split it up and let each have their own focus.
Check the "old" branch on this repository for the old version.