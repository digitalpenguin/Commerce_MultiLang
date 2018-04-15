<?php
/**
 * Get list of weight units from system setting
 *
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangWeightUnitGetListProcessor extends modProcessor {

    public function process() {
        $weightUnits = explode(',',$this->modx->getOption('commerce.allowed_weight_units'));
        foreach ($weightUnits as $key => $value) {
            $unit = array('weight_unit'=>$value);
            $weightUnits[$key] = $unit;
        }
        //$this->modx->log(1,print_r($weightUnits,true));
        $data['success'] = true;
        $data['total'] = count($weightUnits);
        $data['results'] = $weightUnits;

        return $this->modx->toJSON($data);
    }
}
return 'CommerceMultiLangWeightUnitGetListProcessor';