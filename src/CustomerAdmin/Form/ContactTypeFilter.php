<?php
namespace CustomerAdmin\Form;
use Zend\InputFilter\InputFilter;

class ContactTypeFilter extends InputFilter
{

    public function __construct ()
    {
    	$this->add(array (
    			'name' => 'name',
    			'required' => true,
    			'filters' => array(
    					array('name' => 'StringTrim'),
    			)
    	));
    }
}