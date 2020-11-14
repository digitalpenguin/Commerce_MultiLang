<?php
/**
 * Get list of Commerce delivery types
 *
 * @package commerce_multilang
 * @subpackage processors
 */
class CMLDeliveryTypeGetListProcessor extends modProcessor {

    public function process() {
        $deliveryTypes = $this->modx->getCollection('comDeliveryType');
        $deliveryTypesArray = array();
        foreach ($deliveryTypes as $deliveryType) {
            //$this->modx->log(1,print_r($deliveryType->toArray(),true));
            array_push($deliveryTypesArray,$deliveryType->toArray());
        }
        $data = array();
        $data['success'] = true;
        $data['count'] = count($deliveryTypesArray);
        $data['results'] = $deliveryTypesArray;
        return $this->modx->toJSON($data);
    }
}
return 'CMLDeliveryTypeGetListProcessor';