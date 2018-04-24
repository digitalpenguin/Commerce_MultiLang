<?php
/**
 * Update From Grid a product image
 *
 * @package commercemultilang
 * @subpackage processors
 */
require_once(dirname(__FILE__) . '/update.class.php');

class CommerceMultiLangProductImageUpdateFromGridProcessor extends CommerceMultiLangProductUpdateProcessor {
    public function initialize() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $data = $this->modx->fromJSON($data);
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $this->setProperties($data);
        $this->unsetProperty('data');

        return parent::initialize();
    }

}
return 'CommerceMultiLangProductImageUpdateFromGridProcessor';