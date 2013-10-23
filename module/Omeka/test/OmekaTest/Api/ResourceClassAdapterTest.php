<?php
namespace OmekaTest\Api;

use Omeka\Api\Adapter\Entity\ResourceClassAdapter;
use Omeka\Model\Entity\ResourceClass;
use Omeka\Model\Entity\User;
use Omeka\Model\Entity\Vocabulary;
use Omeka\Test\MockBuilder;

class ResourceClassAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected $adapter;

    protected $data = array(
        'owner' => array('id' => 1),
        'vocabulary' => array('id' => 2),
        'local_name' => 'LocalName',
        'label' => 'Label',
        'comment' => 'Comment',
        'resource_type' => 'ResourceType',
        'is_default' => 'IsDefault',
    );

    public function setUp()
    {
        $this->adapter = new ResourceClassAdapter;
    }

    public function testGetEntityClass()
    {
        $this->assertEquals(
            'Omeka\Model\Entity\ResourceClass',
            $this->adapter->getEntityClass()
        );
    }

    public function testHydrate()
    {
        $builder = new MockBuilder;
        $entityRepository = $builder->getEntityRepository();
        $entityManager = $builder->getEntityManager();
        $serviceManager = $builder->getServiceManager('EntityManager', $entityManager);

        $entityManager->expects($this->exactly(2))
            ->method('getRepository')
            ->with($this->logicalOr(
                $this->equalTo('Omeka\Model\Entity\User'),
                $this->equalTo('Omeka\Model\Entity\Vocabulary')
            ))
            ->will($this->returnValue($entityRepository));
        $data = $this->data;
        $entityRepository->expects($this->exactly(2))
            ->method('find')
            ->with($this->logicalOr(
                $this->equalTo($data['owner']['id']),
                $this->equalTo($data['vocabulary']['id'])
            ))
            ->will($this->onConsecutiveCalls(new User, new Vocabulary));

        $this->adapter->setServiceLocator($serviceManager);
        $entity = new ResourceClass;
        $this->adapter->hydrate($this->data, $entity);

        $this->assertNull($entity->getOwner()->getId());
        $this->assertInstanceOf('Omeka\Model\Entity\User', $entity->getOwner());
        $this->assertInstanceOf('Omeka\Model\Entity\Vocabulary', $entity->getVocabulary());
        $this->assertEquals($this->data['local_name'], $entity->getLocalName());
        $this->assertEquals($this->data['label'], $entity->getLabel());
        $this->assertEquals($this->data['comment'], $entity->getComment());
        $this->assertEquals($this->data['resource_type'], $entity->getResourceType());
        $this->assertTrue($entity->getIsDefault());
    }

    public function testExtract()
    {
        $entity = new ResourceClass;
        $entity->setOwner(new User);
        $entity->setVocabulary(new Vocabulary);
        $entity->setLocalName($this->data['local_name']);
        $entity->setLabel($this->data['label']);
        $entity->setComment($this->data['comment']);
        $entity->setResourceType($this->data['resource_type']);
        $entity->setIsDefault($this->data['is_default']);
        $data = $this->adapter->extract($entity);
        $this->assertNull($data['id']);
        $this->assertInternalType('array', $data['owner']);
        $this->assertInternalType('array', $data['vocabulary']);
        $this->assertEquals($this->data['local_name'], $data['local_name']);
        $this->assertEquals($this->data['label'], $data['label']);
        $this->assertEquals($this->data['comment'], $data['comment']);
        $this->assertEquals($this->data['resource_type'], $data['resource_type']);
        $this->assertTrue($data['is_default']);
    }
}
