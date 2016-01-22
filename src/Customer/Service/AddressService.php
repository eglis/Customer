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
* @package Address
* @subpackage Service
* @author Michelangelo Turillo <mturillo@shinesoftware.com>
* @copyright 2014 Michelangelo Turillo.
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
* @link http://shinesoftware.com
* @version @@PACKAGE_VERSION@@
*/

namespace Customer\Service;

use \Customer\Entity\Address;
use Base\Service\ProvinceServiceInterface;
use Base\Service\RegionServiceInterface;
use Base\Service\CountryServiceInterface;
use Zend\EventManager\EventManager;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use GoogleMaps;

class AddressService implements AddressServiceInterface, EventManagerAwareInterface
{
	protected $tableGateway;
	protected $countryService;
	protected $regionService;
	protected $provinceService;
	protected $translator;
	protected $eventManager;
	
	public function __construct(TableGateway $address, CountryServiceInterface $country, RegionServiceInterface $region, ProvinceServiceInterface $province, \Zend\Mvc\I18n\Translator $translator ){
		$this->tableGateway = $address;
		$this->countryService = $country;
		$this->regionService = $region;
		$this->provinceService = $province;
		$this->translator = $translator;
	}
	
    /**
     * @inheritDoc
     */
    public function findAll()
    {
    	$records = $this->tableGateway->select(function (\Zend\Db\Sql\Select $select) {

        });
        
        return $records;
    }

    /**
     * @inheritDoc
     */
    public function find($id)
    {
    	if(!is_numeric($id)){
    		return false;
    	}
    	$rowset = $this->tableGateway->select(array('id' => $id));
    	$row = $rowset->current();
    	return $row;
    }

    /**
     * @inheritDoc
     */
    public function findByParameter($parameter, $value)
    {
    	if(empty($parameter) || empty($value)){
    		return false;
    	}

        $records = $this->tableGateway->select(function (\Zend\Db\Sql\Select $select) use ($parameter, $value) {
            $select->where(array($parameter => $value));
            $select->join('base_country', 'country_id = base_country.id', array ('name'), 'left');
        });

        $records->buffer();

    	return $records;
    }

    /**
     * @inheritDoc
     */
    public function delete($id)
    {
    	$this->tableGateway->delete(array(
    			'id' => $id
    	));
    }

    /**
     * @inheritDoc
     */
    public function save(\Customer\Entity\Address $record)
    {
    	$hydrator = new ClassMethods(true);
    	$country = null;
    	
    	// get the country name 
    	if($record->getCountryId()){
    		$country = $this->countryService->find($record->getCountryId());
    		$strCountry = $country->getName();
    	}
    	
    	// prepare the address string 
    	$strAddress = $record->getStreet() . " " . $record->getCode() . " " . $record->getCity() . " " . $strCountry;
    	
    	// get the data by Google Maps
    	$request = new \GoogleMaps\Request();
    	$request->setAddress($strAddress);
    	
    	$proxy = new \GoogleMaps\Geocoder();
    	$response = $proxy->geocode($request);
    	$results = $response->getResults();
    	
    	if(isset($results[0])){
    		$geometry = $results[0]->getGeometry()->getLocation();
    		$record->setLatitude($geometry->getLat());
    		$record->setLongitude($geometry->getLng());
    	}
    	
    	// extract the data from the object
    	$data = $hydrator->extract($record);
    	$id = (int) $record->getId();
    	
    	$this->getEventManager()->trigger(__FUNCTION__ . '.pre', null, array('data' => $data));  // Trigger an event
    	
    	if ($id == 0) {
    		unset($data['id']);
    		$this->tableGateway->insert($data); // add the record
    		$id = $this->tableGateway->getLastInsertValue();
    	} else {
    		$rs = $this->find($id);
    		if (!empty($rs)) {
    			$this->tableGateway->update($data, array (
    					'id' => $id
    			));
    		} else {
    			throw new \Exception('Record ID does not exist');
    		}
    	}
    	
    	$record = $this->find($id);
    	$this->getEventManager()->trigger(__FUNCTION__ . '.post', null, array('id' => $id, 'data' => $data, 'record' => $record));  // Trigger an event
    	return $record;
    }
    
    
	/* (non-PHPdoc)
     * @see \Zend\EventManager\EventManagerAwareInterface::setEventManager()
     */
     public function setEventManager (EventManagerInterface $eventManager){
         $eventManager->addIdentifiers(get_called_class());
         $this->eventManager = $eventManager;
     }

	/* (non-PHPdoc)
     * @see \Zend\EventManager\EventsCapableInterface::getEventManager()
     */
     public function getEventManager (){
       if (null === $this->eventManager) {
            $this->setEventManager(new EventManager());
        }

        return $this->eventManager;
     }

}