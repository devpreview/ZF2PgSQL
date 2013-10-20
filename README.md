ZF2PgSQL
========

ZF2PgSQL - PostgreSQL specific features for Zend Framework 2.

InsertReturningFeature Example
==============================

    namespace Application\Model;
    
    use Zend\Db\TableGateway\TableGateway;
    use Zend\Db\Adapter\AdapterInterface;
    use ZF2PgSQL\Db\Sql\Insert;
    use ZF2PgSQL\Db\TableGateway\Feature\InsertReturningFeature;
    use ZF2PgSQL\Db\TableGateway\Feature\FeatureSet;

    class AlbumTable extends TableGateway
    {
      public function __construct(AdapterInterface $adapter)
      {
        parent::__construct(
            'album',
            $adapter,
            (new FeatureSet())->addFeature(new InsertReturningFeature, 'InsertReturning')
        );
      }
      
      /* ... Some methods ... */
      
      public function saveAlbum(Album $album)
      {
        $insert = true; // TODO: Insert or update
        $data = Array(); // TODO: Inser data
        
        if($insert) {
          $insert = new Insert($this->getTable());
          $insert->values($data);
          $insert->returning(array('id')); // RETURNING columns
          $this->insertWith($insert);
          $results = $this->getFeatureSet()
                ->getFeatureByName('InsertReturning')
                ->getReturning();
          $albumId = $results['id']; // Profit!!!
        } else {
          /* ... update table ... */
        }
      }
    }
  
Module
======

* ZF2PgSQL/Module - Module class;
* ZF2PgSQL\Db\Sql\Insert - extends Zend\Db\Sql\Insert with RETURNING statement;
* ZF2PgSQL\Db\TableGateway\Feature\InsertReturningFeature - TableGateway feature for RETURNING statement;
* ZF2PgSQL\Db\TableGateway\Feature\FeatureSet - extends Zend\Db\TableGateway\Feature\FeatureSet with get feature by name.
