<?php

	/**
	 * Issue affects component table
	 *
	 * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>
	 ** @version 3.0
	 * @license http://www.opensource.org/licenses/mozilla1.1.php Mozilla Public License 1.1 (MPL 1.1)
	 * @package thebuggenie
	 * @subpackage tables
	 */

	/**
	 * Issue affects component table
	 *
	 * @package thebuggenie
	 * @subpackage tables
	 */
	class TBGIssueAffectsComponentTable extends B2DBTable 
	{

		const B2DBNAME = 'issueaffectscomponent';
		const ID = 'issueaffectscomponent.id';
		const SCOPE = 'issueaffectscomponent.scope';
		const ISSUE = 'issueaffectscomponent.issue';
		const COMPONENT = 'issueaffectscomponent.component';
		const CONFIRMED = 'issueaffectscomponent.confirmed';
		const STATUS = 'issueaffectscomponent.status';
		
		public function __construct()
		{
			parent::__construct(self::B2DBNAME, self::ID);
			parent::_addBoolean(self::CONFIRMED);
			parent::_addForeignKeyColumn(self::COMPONENT, B2DB::getTable('TBGComponentsTable'), TBGComponentsTable::ID);
			parent::_addForeignKeyColumn(self::ISSUE, TBGIssuesTable::getTable(), TBGIssuesTable::ID);
			parent::_addForeignKeyColumn(self::SCOPE, TBGScopesTable::getTable(), TBGScopesTable::ID);
			parent::_addForeignKeyColumn(self::STATUS, TBGListTypesTable::getTable(), TBGListTypesTable::ID);
		}
		
		public function getByIssueID($issue_id)
		{
			$crit = $this->getCriteria();
			$crit->addWhere(self::ISSUE, $issue_id);
			$res = $this->doSelect($crit);
			return $res;
		}
		
		public function getByIssueIDandComponentID($issue_id, $component_id)
		{
			$crit = $this->getCriteria();
			$crit->addWhere(self::COMPONENT, $component_id);
			$crit->addWhere(self::ISSUE, $issue_id);
			$res = $this->doSelectOne($crit);
			return $res;
		}
		
		public function setIssueAffected($issue_id, $component_id)
		{
			if (!$this->getByIssueIDandComponentID($issue_id, $component_id))
			{
				$crit = $this->getCriteria();
				$crit->addInsert(self::ISSUE, $issue_id);
				$crit->addInsert(self::COMPONENT, $component_id);
				$crit->addInsert(self::SCOPE, TBGContext::getScope()->getID());
				$this->doInsert($crit);
				return true;
			}
			else
			{
				return false;
			}
		}

		public function deleteByIssueIDandComponentID($issue_id, $component_id)
		{
			if (!$this->getByIssueIDandComponentID($issue_id, $component_id))
			{
				return false;
			}
			else
			{
				$crit = $this->getCriteria();
				$crit->addWhere(self::ISSUE, $issue_id);
				$crit->addWhere(self::COMPONENT, $component_id);
				$this->doDelete($crit);
				return true;
			}
		}
		
		public function confirmByIssueIDandComponentID($issue_id, $component_id, $confirmed = true)
		{
			if (!$this->getByIssueIDandComponentID($issue_id, $component_id))
			{
				return false;
			}
			else
			{
				$crit = $this->getCriteria();
				$crit->addUpdate(self::CONFIRMED, $confirmed);
				$this->doUpdateById($crit, $res->get(self::ID));
			}				
		}
		
		public function setStatusByIssueIDandComponentID($issue_id, $component_id, $status_id)
		{
			if (!$this->getByIssueIDandComponentID($issue_id, $component_id))
			{
				return false;
			}
			else
			{
				$crit = $this->getCriteria();
				$crit->addUpdate(self::STATUS, $status);
				$this->doUpdateById($crit, $res->get(self::ID));
			}				
		}
		
	}
