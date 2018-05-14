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
        $c->select(array('Product.id','sku','stock','price','weight','weight_unit'));
        $c->select($this->adapter->getSelectColumns('CommerceMultiLangProductData',
            'Data','',array('alias')));
        $c->select($this->adapter->getSelectColumns('CommerceMultiLangProductLanguage',
            'Language','',array('lang_key','name','description','category')));
        $c->select($this->adapter->getSelectColumns('CommerceMultiLangProductImage',
            'ProductImage','',array('main')));
        $c->select($this->adapter->getSelectColumns('CommerceMultiLangProductImageLanguage',
            'ImageLanguage','',array('title','image')));
        //$c->prepare();
        //$this->adapter->log(1,$c->toSQL());

        if ($c->prepare() && $c->stmt->execute()) {
            $this->extendedData =  $c->stmt->fetch(PDO::FETCH_ASSOC);
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
        return $this->extendedData['description'];
    }

    public function getImage() {
        if(!$this->extendedData) {
            $this->loadExtendedData();
        }
        return '/'.$this->extendedData['image'];

    }
}
?>