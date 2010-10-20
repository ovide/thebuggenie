<?php

	/**
	 * CLI command class, main -> remote_list_issuefields
	 *
	 * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>
	 ** @version 3.0
	 * @license http://www.opensource.org/licenses/mozilla1.1.php Mozilla Public License 1.1 (MPL 1.1)
	 * @package thebuggenie
	 * @subpackage core
	 */

	/**
	 * CLI command class, main -> remote_list_issuefields
	 *
	 * @package thebuggenie
	 * @subpackage core
	 */
	class CliMainRemoteListIssuefields extends TBGCliRemoteCommand
	{

		protected function _setup()
		{
			$this->_command_name = 'remote_list_issuefields';
			$this->_description = "Query a remote server for a list of available issue fields per types";
			$this->addRequiredArgument('project_key', 'The project to show available issue fields for');
			$this->addRequiredArgument('issue_type', 'An issue type to show available issue fields for');
			parent::_setup();
		}

		public function do_execute()
		{
			$issuetype = $this->getProvidedArgument('issue_type', null);
			$project_key = $this->getProvidedArgument('project_key', null);

			$this->cliEcho('Querying ');
			$this->cliEcho($this->_getCurrentRemoteServer(), 'white', 'bold');
			$this->cliEcho(" for issuefields valid for issue types {$issuetype} for project {$project_key}\n\n");

			$response = $this->getRemoteResponse($this->getRemoteURL('project_list_issuefields', array('issuetype' => $issuetype, 'project_key' => $project_key, 'format' => 'json')));

			if (!empty($response))
			{
				$this->cliEcho($issuetype, 'yellow', 'bold');
				$this->cliEcho(" has the following available issue fields:\n");
				foreach ($response->issuefields as $field_key)
				{
					$this->cliEcho("  {$field_key}\n", 'yellow');
				}
				$this->cliEcho("\n");
				$this->cliEcho("When using ");
				$this->cliEcho('remote_update_issue', 'green');
				$this->cliEcho(" to update an issue, pass any of these issue fields\n");
				$this->cliEcho("as a valid parameter to update the issue details.\n");
				$this->cliEcho("\n");
				$this->cliEcho("Check the documentation for ");
				$this->cliEcho('remote_update_issue', 'green');
				$this->cliEcho(" for more information.\n");

			}
			else
			{
				$this->cliEcho("No issue fields available.\n\n");
			}
		}

	}