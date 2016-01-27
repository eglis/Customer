<?php
namespace Customer\Form\Element;

use Customer\Service\CustomerGroupService;
use Zend\Form\Element\Select;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\I18n\Translator\Translator;

class CustomerGroup extends Select implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;
    protected $translator;
    protected $service;

    public function __construct(CustomerGroupService $service, \Zend\Mvc\I18n\Translator $translator){
        parent::__construct();
        $this->service = $service;
        $this->translator = $translator;
    }

    public function init()
    {
        $data = array();

        $pages = $this->service->findAll();
        $data[''] = "";
        
        foreach ($pages as $page){
            $data[$page->getId()] = $this->translator->translate($page->getName());
        }
        asort($data);
        $this->setValueOptions($data);
    }
    
    public function setServiceLocator(ServiceLocatorInterface $sl)
    {
        $this->serviceLocator = $sl;
    }
    
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
