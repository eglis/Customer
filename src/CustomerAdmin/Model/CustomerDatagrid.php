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
 * @subpackage Model
 * @author Michelangelo Turillo <mturillo@shinesoftware.com>
 * @copyright 2014 Michelangelo Turillo.
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link http://shinesoftware.com
 * @version @@PACKAGE_VERSION@@
 */

namespace CustomerAdmin\Model;

use \Base\Service\SettingsServiceInterface;
use ZfcDatagrid;
use ZfcDatagrid\Column;
use ZfcDatagrid\Column\Type;
use ZfcDatagrid\Column\Style;
use ZfcDatagrid\Column\Formatter;
use ZfcDatagrid\Filter;
use ZfcDatagrid\Action;
use Zend\Db\Sql\Select;

class CustomerDatagrid {
	
	/**
	 *
	 * @var \ZfcDatagrid\Datagrid
	 */
	protected $grid;

	/**
	 *
	 * @var \Zend\Db\Adapter\Adapter
	 */
	protected $adapter;

	/**
	 *
	 * @var \Customer\Service\CustomerService
	 */
	protected $mainservice;

	/**
	 *
	 * @var \Base\Service\StatusService
	 */
	protected $statusservice;

	/**
	 *
	 * @var SettingsService
	 */
	protected $settings;

	/**
	 * CustomerDatagrid constructor.
	 *
	 * @param \Zend\Db\Adapter\Adapter $dbAdapter
	 * @param \Customer\Service\CustomerService $mainservice
	 * @param \Base\Service\StatusService $status
	 * @param ZfcDatagrid\Datagrid $datagrid
	 * @param SettingsServiceInterface $settings
	 */
	public function __construct(
			\Zend\Db\Adapter\Adapter $dbAdapter,
			\Customer\Service\CustomerService $mainservice,
			\Base\Service\StatusService $status,
			\ZfcDatagrid\Datagrid $datagrid,
			SettingsServiceInterface $settings )
	{
		$this->adapter = $dbAdapter;
		$this->grid = $datagrid;
		$this->mainservice = $mainservice;
		$this->statusservice = $status;
		$this->settings = $settings;
	}
	
	/**
	 *
	 * @return \ZfcDatagrid\Datagrid
	 */
	public function getGrid()
	{
		return $this->grid;
	}
	
	/**
	 * Consumers list
	 *
	 * @return \ZfcDatagrid\Datagrid
	 */
	public function getDatagrid()
	{
		$grid = $this->getGrid();
		$grid->setTitle('Customers');

		$grid->setId('customerGrid');
		$grid->setToolbarTemplateVariables(array('globalActions' =>
						array(_('New Customer') => '/admin/customer/add'),
						array(_('New Customer') => '/admin/customer/add')
				)
		);

		$dbAdapter = $this->adapter;
		$select = new Select();
		$select->from(array ('c' => 'customer'));
		$select->join('user', 'c.user_id = user.user_id', array ('email'), 'left');
		$select->join('base_status', 'status_id = base_status.id', array ('id'), 'left');

		// Status array
		$arrStatus = array();
		$status = $this->statusservice->findAll('customers');
		foreach ($status as $s) {
			$arrStatus[$s->getId()] = $s->getStatus();
		}

		$grid->setDefaultItemsPerPage($this->settings->getValueByParameter('Customer', 'recordsperpage'));
		$grid->setDataSource($select, $dbAdapter);

		$col = new Column\Select('id', 'c');
		$col->setLabel('Id');
		$col->setIdentity();
		$grid->addColumn($col);

		$col = new Column\Select('company', 'c');
		$col->setLabel(_('Company'));
		$col->setWidth(40);
		$grid->addColumn($col);

		$col = new Column\Select('firstname', 'c');
		$col->setLabel(_('Last name'));
		$col->setWidth(15);
		$grid->addColumn($col);

		$col = new Column\Select('lastname', 'c');
		$col->setLabel(_('First name'));
		$col->setWidth(15);
		$grid->addColumn($col);

		$colType = new Type\DateTime('Y-m-d H:i:s', \IntlDateFormatter::SHORT, \IntlDateFormatter::SHORT);
		$colType->setSourceTimezone('Europe/Rome');
		$colType->setOutputTimezone('UTC');
		$colType->setLocale('it_IT');

		$col = new Column\Select('id', 'base_status');
		$col->setLabel('Status');
		$col->setWidth(10);
		$col->setReplaceValues($arrStatus);
		$col->setFilterSelectOptions($arrStatus);
		$col->setTranslationEnabled(true);
		$grid->addColumn($col);

		$col = new Column\Select('email', 'user');
		$col->setLabel(_('Email'));
		$col->addFormatter(new Formatter\Email());
		$col->addStyle(new Style\Bold());
		$col->setWidth(15);
		$grid->addColumn($col);

		$col = new Column\Select('createdat', 'c');
		$col->setType($colType);
		$col->setLabel(_('Created At'));
		$grid->addColumn($col);

		// Add actions to the grid
		$showaction = new Column\Action\Icon();
		$showaction->setAttribute('href', "/admin/customer/edit/" . $showaction->getColumnValuePlaceholder(new Column\Select('id', 'c')));
		$showaction->setAttribute('class', 'btn btn-xs btn-success');
		$showaction->setIconClass('glyphicon glyphicon-pencil');

		$delaction = new Column\Action\Icon();
		$delaction->setAttribute('href', '/admin/customer/delete/' . $delaction->getRowIdPlaceholder());
		$delaction->setAttribute('onclick', "return confirm('Are you sure?')");
		$delaction->setAttribute('class', 'btn btn-xs btn-danger');
		$delaction->setIconClass('glyphicon glyphicon-remove');

		$col = new Column\Action();
		$col->addAction($showaction);
		$col->addAction($delaction);
		$grid->addColumn($col);


		$grid->addMassAction(new Action\Mass(_('Enable'), '/admin/customer/massaction/enable', true));
		$grid->addMassAction(new Action\Mass(_('Disable'), '/admin/customer/massaction/disable', true));
		$grid->addMassAction(new Action\Mass(_('Delete'), '/admin/customer/massaction/delete', true));


		#$grid->setToolbarTemplateVariables(['myVariable' => 123]);
		#$grid->setToolbarTemplate('zfc-datagrid/toolbar/customer');

		return $grid;
	}

}

?>