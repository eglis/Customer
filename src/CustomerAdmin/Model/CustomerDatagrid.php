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
	 * @var SettingsService
	 */
	protected $settings;
	
	/**
	 * Datagrid Constructor
	 * 
	 * @param \Zend\Db\Adapter\Adapter $dbAdapter
	 * @param \ZfcDatagrid\Datagrid $datagrid
	 */
	public function __construct(\Zend\Db\Adapter\Adapter $dbAdapter, \ZfcDatagrid\Datagrid $datagrid, SettingsServiceInterface $settings )
	{
		$this->adapter = $dbAdapter;
		$this->grid = $datagrid;
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
		$grid->setId('customerGrid');
		
		$dbAdapter = $this->adapter;
		$select = new Select();
		$select->from(array ('c' => 'customer'));
		
		$RecordsPerPage = $this->settings->getValueByParameter('Customer', 'recordsperpage');
		 
		$grid->setDefaultItemsPerPage($RecordsPerPage);
		$grid->setDataSource($select, $dbAdapter);
		
		$colId = new Column\Select('id', 'c');
		$colId->setLabel('Id');
		$colId->setIdentity();
		$grid->addColumn($colId);
		 
		$col = new Column\Select('company', 'c');
		$col->setLabel(_('Company'));
		$col->setWidth(15);
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
		
		$col = new Column\Select('createdat', 'c');
		$col->setType($colType);
		$col->setLabel(_('Created At'));
		$grid->addColumn($col);
		
		// Add actions to the grid
		$showaction = new Column\Action\Button();
		$showaction->setAttribute('href', "/admin/customer/edit/" . $showaction->getColumnValuePlaceholder(new Column\Select('id', 'c')));
		$showaction->setAttribute('class', 'btn btn-xs btn-success');
		$showaction->setLabel(_('edit'));
		
		$delaction = new Column\Action\Button();
		$delaction->setAttribute('href', '/admin/customer/delete/' . $delaction->getRowIdPlaceholder());
		$delaction->setAttribute('onclick', "return confirm('Are you sure?')");
		$delaction->setAttribute('class', 'btn btn-xs btn-danger');
		$delaction->setLabel(_('delete'));
		
		$col = new Column\Action();
		$col->addAction($showaction);
		$col->addAction($delaction);
		$grid->addColumn($col);
		
		$grid->setToolbarTemplate('');
		
		return $grid;
	}

}

?>