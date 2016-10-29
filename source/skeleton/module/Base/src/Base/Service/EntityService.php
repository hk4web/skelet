<?php

namespace Docapost\Base\Service;

use Docapost\Base\Entity\AbstractEntity;
use Docapost\Base\Exception\Exception;
use Docapost\Base\Form\Form;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Zend\InputFilter\InputFilterInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;

class EntityService extends AbstractService implements CrudInterface
{
    const FORM_DEFAULT = 'default';
    const HYDRATOR_DEFAULT = 'default';
    const INPUT_FILTER_DEFAULT = 'default';

    /**
     * Forms
     *
     * @var array
     */
    protected $forms = array();

    /**
     * Hydrators
     *
     * @var array
     */
    protected $hydrators = array();

    /**
     * Input Filters
     *
     * @var array
     */
    protected $inpuFilters = array();

    /**
     * Repository
     *
     * @var EntityRepository
     */
    protected $repository;

    /**
     * Entity Manager
     *
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * Return Form
     *
     * @param string $type
     * @return Form
     * @throws Exception
     */
    public function getForm($type = self::FORM_DEFAULT)
    {
        if (isset($this->forms[$type])) {
            return $this->forms[$type];
        } elseif (isset($this->forms[self::FORM_DEFAULT])) {
            return $this->forms[self::FORM_DEFAULT];
        } else {
            throw new Exception("No form found. You must set a default form.");
        }
    }

    /**
     * Define Form
     *
     * @param Form $form
     * @param string $type
     * @return AbstractEntityService
     */
    public function setForm(Form $form, $type = self::FORM_DEFAULT)
    {
        $this->forms[$type] = $form;
        return $this;
    }

    /**
     * Return Hydrator
     *
     * @param string $type
     * @return HydratorInterface
     * @throws Exception
     */
    public function getHydrator($type = self::HYDRATOR_DEFAULT)
    {
        if (isset($this->hydrators[$type])) {
            return $this->hydrators[$type];
        } elseif (isset($this->hydrators[self::HYDRATOR_DEFAULT])) {
            return $this->hydrators[self::HYDRATOR_DEFAULT];
        } else {
            throw new Exception("No form found. You must set a default form.");
        }
    }

    /**
     * Define Hydrator
     *
     * @param HydratorInterface $hydrator
     * @param string $type
     * @return AbstractEntityService
     */
    public function setHydrator(HydratorInterface $hydrator, $type = self::HYDRATOR_DEFAULT)
    {
        $this->hydrators[$type] = $hydrator;
        return $this;
    }

    /**
     * Return Input Filter
     *
     * @param string $type
     * @return InputFilterInterface
     * @throws Exception
     */
    public function getInputFilter($type = self::INPUT_FILTER_DEFAULT)
    {
        if (isset($this->inpuFilters[$type])) {
            return $this->inpuFilters[$type];
        } elseif (isset($this->inpuFilters[self::INPUT_FILTER_DEFAULT])) {
            return $this->inpuFilters[self::INPUT_FILTER_DEFAULT];
        } else {
            throw new Exception("No form found. You must set a default form.");
        }
    }

    /**
     * Define Input Filter
     *
     * @param InputFilterInterface $inputFilter
     * @param string $type
     * @return AbstractEntityService
     */
    public function setInputFilter(InputFilterInterface $inputFilter, $type = self::INPUT_FILTER_DEFAULT)
    {
        $this->inpuFilters[$type] = $inputFilter;
        return $this;
    }

    /**
     * return Repository
     *
     * @return EntityRepository
     * @throws Exception
     */
    public function getRepository()
    {
        if (!$this->repository instanceof EntityRepository) {
            throw new Exception("No repository found. You must set repository before.");
        }

        return $this->repository;
    }

    /**
     * Define Repository
     *
     * @param EntityRepository $repository
     * @return AbstractEntityService
     */
    public function setRepository(EntityRepository $repository)
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     * Return Entity Manager
     *
     * @return EntityManager
     * @throws Exception
     */
    public function getEntityManager()
    {
        if (!$this->entityManager instanceof EntityManager) {
            throw new Exception("No entity manager found. You must set entity manager before.");
        }

        return $this->entityManager;
    }

    /**
     * Define Entity Manager
     *
     * @param EntityManager $entityManager
     * @return AbstractEntityService
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    public function create($entity = null)
    {
        if (null === $entity) {
            $entity = $this->getEntity();
        }

        $entityManager = $this->getEntityManager();
        $entityManager->persist($entity);
        $entityManager->flush();

        $this->getLog()->info("Creation of '" . get_class($entity) . "' ($entity)");

        return true;
    }

    public function read($entity = null)
    {
        if (null === $entity) {
            $entity = $this->getEntity();
        }

        if (method_exists($entity, 'getId')) {
            $entity = $entity->getId();
        }

        $this->getRepository()->find($entity);

        return $entity;
    }

    public function update($entity = null)
    {
        if (null === $entity) {
            $entity = $this->getEntity();
        }

        $entityManager = $this->getEntityManager();
        $entityManager->persist($entity);
        $entityManager->flush();

        $this->getLog()->info("Update of '" . get_class($entity) . "' ($entity)");

        return true;
    }

    public function delete($entity = null)
    {
        if (null === $entity) {
            $entity = $this->getEntity();
        }

        $entityManager = $this->getEntityManager();
        $entityManager->remove($entity);
        $entityManager->flush();

        $this->getLog()->notice("Removal of '" . get_class($entity) . "' ($entity)");

        return true;
    }

    public function toArray($entity = null)
    {
        if (null === $entity) {
            $entity = $this->getEntity();
        }

        $hydrator = $this->getHydrator();

        if (null === $hydrator) {
            $this->getLog()->notice("No hydrator found for " . get_class($entity) . "' ($entity)");
            return get_object_vars($entity);
        }

        return $hydrator->extract($entity);
    }

    /**
     * Translate Entity
     *
     * @param AbstractEntity $entity
     * @return string
     */
    public function translate(AbstractEntity $entity, $options = null)
    {
        //\Zend\Debug\Debug::dump($options);exit;
        if (isset($options['translate']['prefix'])) {
            $prefix = $options['translate']['prefix'];
        } else {
            $prefix = '';
        }

        if (isset($options['translate']['default'])) {
            $default = $options['translate']['default'];
        } elseif (method_exists($entity, 'getLabel')) {
            $default = $entity->getLabel();
        } elseif (method_exists($entity, 'getName')) {
            $default = $entity->getName();
        } elseif (method_exists($entity, '__toString')) {
            $default = $entity->__toString();
        } else {
            throw new Exception("Error");
        }

        if (isset($options['translate']['format'])) {
            $format = $options['translate']['format'];
        } else {
            $format = $default;
        }

        if (isset($options['translate']['params'])) {
            $params = array();
        } else {
            $params = $this->toArray($entity);
        }

        return $this->getServiceLocator()->get('ViewHelperManager')->get('translate')->render(
            $prefix . $format, null, null, $params, $default
        );
    }

    /**
     * Translate Entity Collection
     *
     * @param $entities
     * @return array
     */
    public function translateCollection($entities, $options = null)
    {
        $result = array();
        foreach ($entities as $entity) {
            $result[$entity->getId()] = $this->translate($entity, $options);
        }

        if (isset($options['sort'])) {
            $sort = $options['sort'];
        } else {
            $sort = 'value';
        }

        switch ($sort) {
            case 'ksort' :
            case 'key' :
                ksort($result);
                break;
            case 'asort' :
            case 'value' :
            default :
                asort($result);
                break;
        }

        return $result;
    }
}