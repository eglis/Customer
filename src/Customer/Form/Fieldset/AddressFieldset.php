<?php
namespace Customer\Form\Fieldset;
use Customer\Entity\Address;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethods;

class AddressFieldset extends Fieldset implements InputFilterProviderInterface
{

    public function init ()
    {
        $hydrator = new ClassMethods(true);
        
        $this->setHydrator($hydrator)->setObject(new Address());
        
        $this->add(array ( 
                'name' => 'street', 
                'attributes' => array ( 
                        'type' => 'text', 
                        'class' => 'form-control'
                ), 
                'options' => array ( 
                        'label' => _('Street')
                ), 
                'filters' => array ( 
                        array ( 
                                'name' => 'StringTrim'
                        )
                )
        ));
        
        $this->add(array ( 
                'name' => 'code', 
                'attributes' => array ( 
                        'type' => 'text', 
                        'class' => 'form-control'
                ), 
                'options' => array ( 
                        'label' => _('Zip')
                ), 
                'filters' => array ( 
                        array ( 
                                'name' => 'StringTrim'
                        )
                )
        ));
        
        $this->add(array ( 
                'name' => 'city', 
                'attributes' => array ( 
                        'type' => 'text', 
                        'class' => 'form-control'
                ), 
                'options' => array ( 
                        'label' => _('City')
                ), 
                'filters' => array ( 
                        array ( 
                                'name' => 'StringTrim'
                        )
                )
        ));
        
        $this->add(array ( 
                'type' => 'Base\Form\Element\Country', 
                'name' => 'country_id', 
                'attributes' => array ( 
                        'class' => 'form-control',
                		'onchange'   => 'onChangeCountry( this );'
                ), 
                'options' => array ( 
                        'label' => _('Country of residence'),
                		
                ), 
        ));
        
        $this->add(array ( 
                'type' => 'Select', 
                'name' => 'region_id', 
                'attributes' => array ( 
                        'class' => 'form-control',
                		'onchange'   => 'onChangeRegion( this );'
                ), 
                'options' => array ( 
                        'label' => _('Region'),
                		'disable_inarray_validator' => true,
                ), 
        ));
        
        $this->add(array ( 
                'type' => 'Select', 
                'name' => 'province_id', 
                'attributes' => array ( 
                        'class' => 'form-control',
                ), 
                'options' => array ( 
                        'label' => _('Province'),
                		'disable_inarray_validator' => true,
                ), 
        ));
        
        $this->add(array ( 
                'name' => 'id', 
                'attributes' => array ( 
                        'type' => 'hidden'
                )
        ));
    }
    
    /**
     * @return array
     \*/
    public function getInputFilterSpecification()
    {
    	return array(
    			'country_id' => array('required' => false),
    			'region_id' => array('required' => false),
    			'province_id' => array('required' => false),
    	);
    }
}