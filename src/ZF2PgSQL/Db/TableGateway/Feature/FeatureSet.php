<?php
/**
 * Insert Returning feature for PostgreSQL
 *
 * @link      https://github.com/devpreview/ZF2PgSQL
 * @copyright Copyright (c) 2013 Alexey Savchuk. (http://www.devpreview.ru)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ZF2PgSQL\Db\TableGateway\Feature;

use Zend\Db\TableGateway\Feature\FeatureSet as ZendFeatureSet;
use Zend\Db\TableGateway\Feature\AbstractFeature;

class FeatureSet extends ZendFeatureSet
{
    public function addFeatures(array $features, $withNames = false)
    {
        if($withNames === TRUE) {
            foreach ($features as $name => $feature) {
                $this->addFeature($feature, $name);
            }
        } else {
            foreach ($features as $feature) {
                $this->addFeature($feature);
            }
        }
        return $this;
    }
    
    public function addFeature(AbstractFeature $feature, $name = NULL)
    {
        if(empty($name)) {
            $this->features[] = $feature;
        } else {
            $this->features[(string)$name] = $feature;
        }
        $feature->setTableGateway($feature);
        return $this;
    }
    
    public function getFeatureByName($featureName)
    {
        $feature = false;
        if(isset($this->features[(string)$featureName])) {
            $feature = $this->features[(string)$featureName];
        }
        return $feature;
    }
}