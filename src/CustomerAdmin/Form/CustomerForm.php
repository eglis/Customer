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
* @package Cms
* @subpackage Form
* @author Michelangelo Turillo <mturillo@shinesoftware.com>
* @copyright 2014 Michelangelo Turillo.
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
* @link http://shinesoftware.com
* @version @@PACKAGE_VERSION@@
*/

namespace CustomerAdmin\Form;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;
use Base\Hydrator\Strategy\DateTimeStrategy;

class CustomerForm extends Form {
	
	public function init() {
		$hydrator = new ClassMethods ();
		$hydrator->addStrategy('birthdate', new DateTimeStrategy());
		
		$this->setAttribute ( 'method', 'post' );
		$this->setHydrator ( $hydrator )->setObject ( new \Customer\Entity\Customer () );
		
		$this->add ( array ('name' => 'company', 'attributes' => array ('type' => 'text', 'class' => 'form-control' ), 'options' => array ('label' => _ ( 'Company' ) ) ) );
		$this->add ( array ('name' => 'firstname', 'attributes' => array ('type' => 'text', 'class' => 'form-control' ), 'options' => array ('label' => _ ( 'First name' ) ) ) );
		$this->add ( array ('name' => 'lastname', 'attributes' => array ('type' => 'text', 'class' => 'form-control' ), 'options' => array ('label' => _ ( 'Last name' ) ) ) );
		$this->add ( array ('name' => 'note', 'attributes' => array ('type' => 'textarea', 'class' => 'wysiwyg' ), 'options' => array ('label' => _ ( 'Private Notes' ) ) ) );

		$this->add ( array ('name' => 'birthdate', 'attributes' => array ('type' => 'text', 'class' => 'form-control' ), 'options' => array ('label' => _ ( 'Birth date' ) ) ) );
		$this->add ( array ('name' => 'birthplace', 'attributes' => array ('type' => 'text', 'class' => 'form-control' ), 'options' => array ('label' => _ ( 'Birth place' ) ) ) );
		$this->add ( array ('name' => 'birthdistrict', 'attributes' => array ('type' => 'text', 'class' => 'form-control' ), 'options' => array ('label' => _ ( 'Birth district' ) ) ) );
		$this->add ( array ('name' => 'birthcountry', 'attributes' => array ('type' => 'text', 'class' => 'form-control' ), 'options' => array ('label' => _ ( 'Birth country' ) ) ) );
		$this->add ( array ('name' => 'birthnationality', 'attributes' => array ('type' => 'text', 'class' => 'form-control' ), 'options' => array ('label' => _ ( 'Birth nationality' ) ) ) );
		$this->add ( array ('name' => 'taxpayernumber', 'attributes' => array ('type' => 'text', 'class' => 'form-control' ), 'options' => array ('label' => _ ( 'Tax payer number' ) ) ) );
		
		$this->add ( array ('type' => 'Customer\Form\Element\Legalform', 'name' => 'legalform_id', 'attributes' => array ('class' => 'form-control' ), 'options' => array ('label' => _ ( 'Legal form' ) ) ) );
		$this->add ( array ('type' => 'Customer\Form\Element\Companytype', 'name' => 'type_id', 'attributes' => array ('class' => 'form-control' ), 'options' => array ('label' => _ ( 'Company Type' ) ) ) );
		$this->add ( array ('type' => 'Customer\Form\Element\Status', 'name' => 'status_id', 'attributes' => array ('class' => 'form-control' ), 'options' => array ('label' => _ ( 'Status' ), 'section' => 'customers') ) );		
		
		$this->add ( array ('type' => 'Zend\Form\Element\File', 'name' => 'file', 'attributes' => array ('class' => '' ), 'options' => array ('label' => _ ( 'Upload Document' ) ), 'filters' => array (array ('required' => false )  ) ) );
		
		$this->add ( array ('name' => 'contact', 'attributes' => array ('class' => 'form-control' ), 'options' => array ('label' => _ ( 'Contact' ) ) ) );
		$this->add ( array ('type' => 'Customer\Form\Element\ContactType', 'name' => 'contacttype', 'attributes' => array ('class' => 'form-control' ), 'options' => array ('label' => _ ( 'Contact Type' ) ) ) );
		
		$this->add ( array ('type' => 'Zend\Form\Element\Select', 'name' => 'gender', 'attributes' => array ('class' => 'form-control' ), 'options' => array ('label' => _ ( 'Gender' ), 'value_options' => array ('M' => _ ( 'Male' ), 'F' => _ ( 'Female' ) ) ) ) );
		
		// This is a fieldset
		$this->add ( array ('name' => 'address', 'type' => '\Customer\Form\Fieldset\AddressFieldset', 'object' => '\Customer\Entity\Address', 'options' => array ('use_as_base_fieldset' => false ) ) );
		
		$this->add ( array ('name' => 'submit', 'attributes' => array ('type' => 'submit', 'class' => 'btn btn-success', 'value' => _ ( 'Save' ) ) ) );
		$this->add ( array ('name' => 'id', 'attributes' => array ('type' => 'hidden' ) ) );
	}
}