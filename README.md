
Commerce_MultiLang
=
**A multilingual products module for Commerce on MODX.**

*Version: 1.0.0-pl*

Developed by Murray Wood at Digital Penguin in Hong Kong.


Introduction
-
Commerce_MultiLang brings multilingual products to Commerce on MODX. Multilingual Products are a custom product type, and can be selected when creating or updating a product.

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

Roadmap
==
- Allow for multilingual images to be used within each product. This way if text is included in the image, you can have a different one for each context.


History
==
This module used to be a lot larger. It was decided to split it up and let each have their own focus.
Check the "old" branch on this repository for the old version.