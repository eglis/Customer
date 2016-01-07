<?php
/**
* Copyright (c) 2014 Shine Software.
* All rights reserved.
*
* Redistribution and use in source and binary forms, with or without
* modification, are permitted provided that the following conditions
* are met:
*
* * Redistributions of source code must retain the above copyright
* notice, this list of conditions and the following disclaimer.
*
* * Redistributions in binary form must reproduce the above copyright
* notice, this list of conditions and the following disclaimer in
* the documentation and/or other materials provided with the
* distribution.
*
* * Neither the names of the copyright holders nor the names of the
* contributors may be used to endorse or promote products derived
* from this software without specific prior written permission.
*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
* "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
* LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
* FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
* COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
* INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
* BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
* LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
* CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
* LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
* ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
* POSSIBILITY OF SUCH DAMAGE.
*
* @package Customer
* @subpackage Controller
* @author Michelangelo Turillo <mturillo@shinesoftware.com>
* @copyright 2014 Michelangelo Turillo.
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
* @link http://shinesoftware.com
* @version @@PACKAGE_VERSION@@
*/

namespace CustomerAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\InputFilter\InputFilter;

class ContactTypeController extends AbstractActionController
{
	protected $contactTypeService;
	protected $datagrid;
	protected $form;
	protected $filter;
	protected $settings;
	
	public function __construct(\Customer\Service\ContactTypeServiceInterface $recordService,
								\CustomerAdmin\Form\ContactTypeForm $form,
								\CustomerAdmin\Form\ContactTypeFilter $formfilter,
								\ZfcDatagrid\Datagrid $datagrid,
								\Base\Service\SettingsServiceInterface $settings)
	{
		$this->contactTypeService = $recordService;
		$this->datagrid = $datagrid;
		$this->form = $form;
		$this->filter = $formfilter;
		$this->settings = $settings;
	}
	
	/**
	 * List of all records
	 */
	public function indexAction ()
	{
		// prepare the datagrid
		$this->datagrid->render();
		
		// get the datagrid ready to be shown in the template view
		$response = $this->datagrid->getResponse();
	
		if ($this->datagrid->isHtmlInitReponse()) {
			$view = new ViewModel();
			$view->addChild($response, 'grid');
			return $view;
		} else {
			return $response;
		}
	}
	
    /**
     * Add new information
     */
    public function addAction ()
    {
    	 
    	$form = $this->form;
    
    	$viewModel = new ViewModel(array (
    			'form' => $form,
    	));
    
    	$viewModel->setTemplate('customer-admin/contact-type/edit');
    	return $viewModel;
    }
    
    /**
     * Edit the main customer information
     */
    public function editAction ()
    {
    	$id = $this->params()->fromRoute('id');
    	
    	$form = $this->form;
    
    	// Get the record by its id
    	$record = $this->contactTypeService->find($id);
    	
    	// Bind the data in the form
    	if (! empty($record)) {
    		$form->bind($record);
    	}
    
    	$viewModel = new ViewModel(array (
    			'form' => $form,
    	));
    
    	return $viewModel;
    }
    
    /**
     * Prepare the data and then save them
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function processAction ()
    {
    	
    	if (! $this->request->isPost()) {
    		return $this->redirect()->toRoute(NULL, array (
    				'controller' => 'customer',
    				'action' => 'index'
    		));
    	}
    	
    	$request = $this->getRequest();
    	$post = $this->request->getPost();
    	$form = $this->form;
    	
    	$form->setData($post);
    	
    	$inputFilter = $this->filter;
    	
    	// set the input filter
    	$form->setInputFilter($inputFilter);
    	
    	if (!$form->isValid()) {
    		$viewModel = new ViewModel(array (
    				'error' => true,
    				'form' => $form,
    		));
    		
    		$viewModel->setTemplate('customer-admin/contact-type/edit');
    		return $viewModel;
    	}
    
    	// Get the posted vars
    	$data = $form->getData();

    	// Save the data in the database
    	$record = $this->contactTypeService->save($data);
    	
    	$this->flashMessenger()->setNamespace('success')->addMessage('The information have been saved.');
    
    	return $this->redirect()->toRoute('zfcadmin/customer/contacttype', array ('action' => 'edit', 'id' => $record->getId()));
    }
    
    /**
     * Delete the contact type record 
     *
     * @return \Zend\Http\Response
     */
    public function deleteAction ()
    {
    	$id = $this->params()->fromRoute('id');
    
    	if (is_numeric($id)) {
    
    		// Delete the record information
    		$this->contactTypeService->delete($id);
    
    		// Go back showing a message
    		$this->flashMessenger()->setNamespace('success')->addMessage('The record has been deleted!');
    		return $this->redirect()->toRoute('zfcadmin/customer/contacttype');
    	}
    
    	$this->flashMessenger()->setNamespace('danger')->addMessage('The record has been not found!');
    	return $this->redirect()->toRoute('zfcadmin/customer/contacttype');
    }
}
