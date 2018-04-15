<?php
/**
 * Get list of Commerce tax groups
 *
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangTaxGroupGetListProcessor extends modProcessor {

    public function process() {
        $taxGroups = $this->modx->getCollection('comTaxGroup');
        $taxGroupsArray = array();
        foreach ($taxGroups as $taxGroup) {
            //$this->modx->log(1,print_r($taxGroup->toArray(),true));
            array_push($taxGroupsArray,$taxGroup->toArray());
        }
        $data = array();
        $data['success'] = true;
        $data['count'] = count($taxGroupsArray);
        $data['results'] = $taxGroupsArray;
        return $this->modx->toJSON($data);
    }
}
return 'CommerceMultiLangTaxGroupGetListProcessor';