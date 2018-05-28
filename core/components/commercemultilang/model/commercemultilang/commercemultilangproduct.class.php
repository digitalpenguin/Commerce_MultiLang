<?php
/**
 * @package commercemultilang
 */
class CommerceMultiLangProduct extends comProduct {
    protected $extendedData = null;

    public function loadExtendedData() {
        $c = $this->adapter->newQuery('CommerceMultiLangProduct');
        $c->setClassAlias('Product');
        $c->leftJoin('CommerceMultiLangProductData','Data','Data.product_id=Product.id');
        $c->leftJoin('CommerceMultiLangProductLanguage','Language',[
            'Language.product_id=Product.id',
            'Language.lang_key'  =>  $this->adapter->getOption('cultureKey')
        ]);
        $c->leftJoin('CommerceMultiLangProductImage','ProductImage',[
            'ProductImage.product_id=Product.id',
            'ProductImage.main'    =>  1
        ]);
        $c->leftJoin('CommerceMultiLangProductImageLanguage','ImageLanguage',[
            'ImageLanguage.product_image_id=ProductImage.id',
            'ImageLanguage.lang_key'    =>  $this->adapter->getOption('cultureKey')
        ]);
        $c->where(['Product.id' =>  $this->get('id')]);
        $c->select(['Product.id','sku','stock','price','weight','weight_unit']);
        $c->select($this->adapter->getSelectColumns('CommerceMultiLangProductData',
            'Data','',['alias']));
        $c->select($this->adapter->getSelectColumns('CommerceMultiLangProductLanguage',
            'Language','',['lang_key','name','description','category']));
        $c->select($this->adapter->getSelectColumns('CommerceMultiLangProductImage',
            'ProductImage','',['main']));
        $c->select($this->adapter->getSelectColumns('CommerceMultiLangProductImageLanguage',
            'ImageLanguage','',['title','image']));
        //$c->prepare();
        //$this->adapter->log(1,$c->toSQL());

        if ($c->prepare() && $c->stmt->execute()) {
            $this->extendedData =  $c->stmt->fetch(PDO::FETCH_ASSOC);

            $query = $this->commerce->adapter->newQuery('CommerceMultiLangAssignedVariation');
            $query->where([
                'product_id'    =>  $this->extendedData['id'],
                'lang_key'      =>  $this->commerce->adapter->getOption('cultureKey')
            ]);
            $query->select('id,name,value');
            if ($query->prepare() && $query->stmt->execute()) {
                $this->extendedData['variations'] = $query->stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }


        //$this->adapter->log(1,print_r($this->extendedData,true));

    }

    public function getName() {
        if(!$this->extendedData) {
            $this->loadExtendedData();
        }
        return $this->extendedData['name'];
    }

    public function getDescription() {
        if(!$this->extendedData) {
            $this->loadExtendedData();
        }
        if($this->extendedData['variations']) {
            $output = '';
            $count = count($this->extendedData['variations']);
            $idx = 1;
            foreach($this->extendedData['variations'] as $variation) {
                $output .= ucfirst($variation['name']).': '.$variation['value'];
                if($count != $idx) {
                    $output .= ', '; // Only add comma if not last item.
                }
                $idx++;
            }
            return $output.' | '.$this->extendedData['description'];
        }
        return $this->extendedData['description'];
    }

    public function getImage() {
        if(!$this->extendedData) {
            $this->loadExtendedData();
        }
        $uri = ltrim($this->adapter->getOption('commercemultilang.base_image_url',null,1), '/');
        return '/'.$uri.$this->extendedData['image'];

    }
}
?>