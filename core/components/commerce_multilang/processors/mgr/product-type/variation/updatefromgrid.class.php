<?php
/**
 * Update From Grid a product variation
 *
 * @package commerce_multilang
 * @subpackage processors
 */
require_once(dirname(__FILE__) . '/update.class.php');

class CMLProductVariationUpdateFromGridProcessor extends CMLProductVariationUpdateProcessor {
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
return 'CMLProductVariationUpdateFromGridProcessor';