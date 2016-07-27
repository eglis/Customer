<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CustomerSettings\Controller;

use \Base\Service\SettingsServiceInterface;
use \CustomerSettings\Form\CustomerForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    protected $recordService;
    protected $customerForm;
	
	public function __construct(SettingsServiceInterface $recordService, CustomerForm $customerForm)
	{
		$this->recordService = $recordService;
        $this->customerForm = $customerForm;
	}
	
    public function indexAction ()
    {
    	$formData = array();

		// Get the custom settings of this module: "Cms"
		$records = $this->recordService->findByModule('Customer');
		
		if(!empty($records)){
			foreach ($records as $record){
				$formData[$record->getParameter()] = $record->getValue(); 
			}
		}
		
		// Fill the form with the data
        $this->customerForm->setData($formData);
		
    	$viewModel = new ViewModel(array (
    			'form' => $this->customerForm,
    	));
    
    	$viewModel->setTemplate('customer-settings/customer/index');
    	return $viewModel;
    }
	
    public function processAction ()
    {
    	
    	if (! $this->request->isPost()) {
    		return $this->redirect()->toRoute('zfcadmin/customer/settings');
    	}
    	
    	try{
	    	$settingsEntity = new \Base\Entity\Settings();
	    	
	    	$post = $this->request->getPost();
	    	$this->customerForm->setData($post);
	    	
	    	if (!$this->customerForm->isValid()) {
	    	
	    		// Get the record by its id
	    		$viewModel = new ViewModel(array (
	    				'error' => true,
	    				'form' => $this->customerForm,
	    		));
	    		$viewModel->setTemplate('customer-settings/customer/index');
	    		return $viewModel;
	    	}
	    	
	    	$data = $this->customerForm->getData();
	    	
	    	// Cleanup the custom settings
	   		$this->recordService->cleanup('Customer');
	    	
	    	foreach ($data as $parameter => $value){
	    		if($parameter == "submit"){
	    			continue;
	    		}
	
	    		$settingsEntity->setModule('Customer');
	    		$settingsEntity->setParameter($parameter);
	    		$settingsEntity->setValue($value);
	    		$this->recordService->save($settingsEntity); // Save the data in the database
	    		
	    	}
	    	
	    	$this->flashMessenger()->setNamespace('success')->addMessage('The information have been saved.');
    		
    	}catch(\Exception $e){
    		$this->flashMessenger()->setNamespace('error')->addMessage($e->getMessage());
    	}
    	
    	return $this->redirect()->toRoute('zfcadmin/customer/settings');
    }
}
