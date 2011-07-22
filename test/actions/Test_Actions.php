<?php
/**
 * This class will test some actions.
 *
 * PHP Version 5
 *
 * @category   Pami
 * @package    Test
 * @subpackage Action
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @license    http://marcelog.github.com/ Apache License 2.0
 * @version    SVN: $Id$
 * @link       http://marcelog.github.com/
 *
 * Copyright 2011 Marcelo Gornstein <marcelog@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */
namespace PAMI\Client\Impl {
/**
 * This class will test some actions.
 *
 * PHP Version 5
 *
 * @category   Pami
 * @package    Test
 * @subpackage Action
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @license    http://marcelog.github.com/ Apache License 2.0
 * @link       http://marcelog.github.com/
 */
class Test_Actions extends \PHPUnit_Framework_TestCase
{
    private $_properties = array();

    public function setUp()
    {
        $this->_properties = array(
            'log4php.properties' => RESOURCES_DIR . DIRECTORY_SEPARATOR . 'log4php.properties'
        );
    }

    private function _start(array $write, \PAMI\Message\Action\ActionMessage $action)
    {
        global $mock_stream_socket_client;
        global $mock_stream_set_blocking;
        global $mockTime;
        global $standardAMIStart;
        $mockTime = true;
        $mock_stream_socket_client = true;
        $mock_stream_set_blocking = true;
        $options = array(
            'log4php.properties' => RESOURCES_DIR . DIRECTORY_SEPARATOR . 'log4php.properties',
        	'host' => '2.3.4.5',
            'scheme' => 'tcp://',
        	'port' => 9999,
        	'username' => 'asd',
        	'secret' => 'asd',
            'connect_timeout' => 10,
        	'read_timeout' => 10
        );
        $writeLogin = array(
        	"action: Login\r\nactionid: 1432.123\r\nusername: asd\r\nsecret: asd\r\n"
        );
        setFgetsMock($standardAMIStart, $writeLogin);
        $client = new \PAMI\Client\Impl\ClientImpl($options);
	    $client->open();
	    if ($action instanceof \PAMI\Message\Action\DBGetAction) {
            $event = array(
	        	'Response: Success',
                'EventList: start',
                'ActionID: 1432.123',
        		'',
	            'Event: DBGetResponse',
            	'ActionID: 1432.123',
                ''
	        );
	    } else {
            $event = array(
                'Response: Success',
                'ActionID: 1432.123',
        		''
            );
	    }
	    setFgetsMock($event, $write);
	    $result = $client->send($action);
	    $this->assertTrue($result instanceof \PAMI\Message\Response\ResponseMessage);
	    return $client;
    }
    /**
     * @test
     */
    public function can_absolute_timeout()
    {
        $write = array(
        	"action: AbsoluteTimeout\r\nactionid: 1432.123\r\nchannel: SIP/asd\r\ntimeout: 10\r\n"
        );
        $action = new \PAMI\Message\Action\AbsoluteTimeoutAction('SIP/asd', 10);
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_agent_logoff()
    {
        $write = array(implode("\r\n", array(
        	'action: AgentLogoff',
            'actionid: 1432.123',
            'agent: asd',
            'soft: true',
            ''
        )));
	    $action = new \PAMI\Message\Action\AgentLogoffAction('asd', true);
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_agents()
    {
        $write = array(implode("\r\n", array(
        	'action: Agents',
            'actionid: 1432.123',
            ''
        )));
	    $action = new \PAMI\Message\Action\AgentsAction;
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_atxfer()
    {
        $write = array(implode("\r\n", array(
        	'action: Atxfer',
            'actionid: 1432.123',
            'channel: channel',
            'exten: exten',
            'context: context',
            'priority: priority',
            ''
        )));
	    $action = new \PAMI\Message\Action\AttendedTransferAction('channel', 'exten', 'context', 'priority');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_bridge()
    {
        $write = array(implode("\r\n", array(
        	'action: Bridge',
            'actionid: 1432.123',
            'channel1: channel1',
        	'channel2: channel2',
            'tone: true',
            ''
        )));
	    $action = new \PAMI\Message\Action\BridgeAction('channel1', 'channel2', true);
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_change_monitor()
    {
        $write = array(implode("\r\n", array(
        	'action: ChangeMonitor',
            'actionid: 1432.123',
            'channel: channel',
        	'file: file',
            ''
        )));
	    $action = new \PAMI\Message\Action\ChangeMonitorAction('channel', 'file', true);
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_command()
    {
        $write = array(implode("\r\n", array(
        	'action: Command',
            'actionid: 1432.123',
            'command: command',
            ''
        )));
	    $action = new \PAMI\Message\Action\CommandAction('command');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_core_settings()
    {
        $write = array(implode("\r\n", array(
        	'action: CoreSettings',
            'actionid: 1432.123',
            ''
        )));
	    $action = new \PAMI\Message\Action\CoreSettingsAction;
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_dongle_show_devices()
    {
        $write = array(implode("\r\n", array(
        	'action: DongleShowDevices',
            'actionid: 1432.123',
            ''
        )));
	    $action = new \PAMI\Message\Action\DongleShowDevicesAction;
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_dongle_sms_send()
    {
        $write = array(implode("\r\n", array(
        	'action: DongleSendSMS',
            'actionid: 1432.123',
            'device: device',
            'number: number',
            'message: message',
            ''
        )));
	    $action = new \PAMI\Message\Action\DongleSendSMSAction('device', 'number', 'message');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_core_status()
    {
        $write = array(implode("\r\n", array(
        	'action: CoreStatus',
            'actionid: 1432.123',
            ''
        )));
	    $action = new \PAMI\Message\Action\CoreStatusAction;
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_create_config()
    {
        $write = array(implode("\r\n", array(
        	'action: CreateConfig',
            'actionid: 1432.123',
            'filename: file.conf',
            ''
        )));
	    $action = new \PAMI\Message\Action\CreateConfigAction('file.conf');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_dahdi_dndoff()
    {
        $write = array(implode("\r\n", array(
        	'action: DAHDIDNDOff',
            'actionid: 1432.123',
            'dahdichannel: channel',
            ''
        )));
	    $action = new \PAMI\Message\Action\DAHDIDNDOffAction('channel');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_dahdi_dndon()
    {
        $write = array(implode("\r\n", array(
        	'action: DAHDIDNDOn',
            'actionid: 1432.123',
            'dahdichannel: channel',
            ''
        )));
	    $action = new \PAMI\Message\Action\DAHDIDNDOnAction('channel');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_dahdi_dialoffhook()
    {
        $write = array(implode("\r\n", array(
        	'action: DAHDIDialOffhook',
            'actionid: 1432.123',
            'dahdichannel: channel',
        	'number: number',
            ''
        )));
	    $action = new \PAMI\Message\Action\DAHDIDialOffHookAction('channel', 'number');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_dahdi_hangup()
    {
        $write = array(implode("\r\n", array(
        	'action: DAHDIHangup',
            'actionid: 1432.123',
            'dahdichannel: channel',
            ''
        )));
	    $action = new \PAMI\Message\Action\DAHDIHangupAction('channel');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_dahdi_restart()
    {
        $write = array(implode("\r\n", array(
        	'action: DAHDIRestart',
            'actionid: 1432.123',
            ''
        )));
	    $action = new \PAMI\Message\Action\DAHDIRestartAction;
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_dahdi_show_channels()
    {
        $write = array(implode("\r\n", array(
        	'action: DAHDIShowChannels',
            'actionid: 1432.123',
            ''
        )));
	    $action = new \PAMI\Message\Action\DAHDIShowChannelsAction;
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_dbdel()
    {
        $write = array(implode("\r\n", array(
        	'action: DBDel',
            'actionid: 1432.123',
            'family: family',
        	'key: key',
            ''
        )));
	    $action = new \PAMI\Message\Action\DBDelAction('family', 'key');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_dbdeltree()
    {
        $write = array(implode("\r\n", array(
        	'action: DBDelTree',
            'actionid: 1432.123',
            'family: family',
        	'key: key',
            ''
        )));
	    $action = new \PAMI\Message\Action\DBDelTreeAction('family', 'key');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_dbget()
    {
        $write = array(implode("\r\n", array(
        	'action: DBGet',
            'actionid: 1432.123',
            'family: family',
        	'key: key',
            ''
        )));
	    $action = new \PAMI\Message\Action\DBGetAction('family', 'key');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_dbput()
    {
        $write = array(implode("\r\n", array(
        	'action: DBPut',
            'actionid: 1432.123',
            'family: family',
        	'key: key',
            'val: val',
            ''
        )));
	    $action = new \PAMI\Message\Action\DBPutAction('family', 'key', 'val');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_events_off()
    {
        $write = array(implode("\r\n", array(
        	'action: Events',
            'actionid: 1432.123',
            'eventmask: off',
            ''
        )));
	    $action = new \PAMI\Message\Action\EventsAction;
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_events()
    {
        $write = array(implode("\r\n", array(
        	'action: Events',
            'actionid: 1432.123',
            'eventmask: a,b,c',
            ''
        )));
	    $action = new \PAMI\Message\Action\EventsAction(array('a', 'b', 'c'));
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_extension_state()
    {
        $write = array(implode("\r\n", array(
        	'action: ExtensionState',
            'actionid: 1432.123',
            'exten: exten',
            'context: context',
            ''
        )));
	    $action = new \PAMI\Message\Action\ExtensionStateAction('exten', 'context');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_get_config()
    {
        $write = array(implode("\r\n", array(
        	'action: GetConfig',
            'actionid: 1432.123',
            'filename: file.conf',
            'category: category',
            ''
        )));
	    $action = new \PAMI\Message\Action\GetConfigAction('file.conf', 'category');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_get_configjson()
    {
        $write = array(implode("\r\n", array(
        	'action: GetConfigJSON',
            'actionid: 1432.123',
            'filename: file.conf',
            ''
        )));
	    $action = new \PAMI\Message\Action\GetConfigJSONAction('file.conf', 'category');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_get_var()
    {
        $write = array(implode("\r\n", array(
        	'action: Getvar',
            'actionid: 1432.123',
            'variable: var',
        	'channel: channel',
            ''
        )));
	    $action = new \PAMI\Message\Action\GetVarAction('var', 'channel');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_hangup()
    {
        $write = array(implode("\r\n", array(
        	'action: Hangup',
            'actionid: 1432.123',
        	'channel: channel',
            ''
        )));
	    $action = new \PAMI\Message\Action\HangupAction('channel');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_jabbersend()
    {
        $write = array(implode("\r\n", array(
        	'action: JabberSend',
            'actionid: 1432.123',
        	'jabber: jabber',
        	'jid: jid',
            'message: message',
            ''
        )));
	    $action = new \PAMI\Message\Action\JabberSendAction('jabber', 'jid', 'message');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_list_categories()
    {
        $write = array(implode("\r\n", array(
        	'action: ListCategories',
            'actionid: 1432.123',
            'filename: file.conf',
            ''
        )));
	    $action = new \PAMI\Message\Action\ListCategoriesAction('file.conf');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_list_commands()
    {
        $write = array(implode("\r\n", array(
        	'action: ListCommands',
            'actionid: 1432.123',
            ''
        )));
	    $action = new \PAMI\Message\Action\ListCommandsAction;
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_local_optimize_away()
    {
        $write = array(implode("\r\n", array(
        	'action: LocalOptimizeAway',
            'actionid: 1432.123',
        	'channel: channel',
            ''
        )));
	    $action = new \PAMI\Message\Action\LocalOptimizeAwayAction('channel');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_mailbox_count()
    {
        $write = array(implode("\r\n", array(
        	'action: MailboxCount',
            'actionid: 1432.123',
        	'mailbox: mailbox',
            ''
        )));
	    $action = new \PAMI\Message\Action\MailboxCountAction('mailbox');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_mailbox_status()
    {
        $write = array(implode("\r\n", array(
        	'action: MailboxStatus',
            'actionid: 1432.123',
        	'mailbox: mailbox',
            ''
        )));
	    $action = new \PAMI\Message\Action\MailboxStatusAction('mailbox');
        $client = $this->_start($write, $action);
    }

    /**
     * @test
     */
    public function can_meetme_list()
    {
        $write = array(implode("\r\n", array(
        	'action: MeetmeList',
            'actionid: 1432.123',
        	'conference: conference',
            ''
        )));
	    $action = new \PAMI\Message\Action\MeetmeListAction('conference');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_meetme_mute()
    {
        $write = array(implode("\r\n", array(
        	'action: MeetmeMute',
            'actionid: 1432.123',
        	'meetme: meetme',
        	'usernum: usernum',
            ''
        )));
	    $action = new \PAMI\Message\Action\MeetmeMuteAction('meetme', 'usernum');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_meetme_unmute()
    {
        $write = array(implode("\r\n", array(
        	'action: MeetmeUnmute',
            'actionid: 1432.123',
        	'meetme: meetme',
        	'usernum: usernum',
            ''
        )));
	    $action = new \PAMI\Message\Action\MeetmeUnmuteAction('meetme', 'usernum');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_module_check()
    {
        $write = array(implode("\r\n", array(
        	'action: ModuleCheck',
            'actionid: 1432.123',
        	'module: module',
            ''
        )));
	    $action = new \PAMI\Message\Action\ModuleCheckAction('module');
        $client = $this->_start($write, $action);
    }

    /**
     * @test
     */
    public function can_module_load()
    {
        $write = array(implode("\r\n", array(
        	'action: ModuleLoad',
            'actionid: 1432.123',
        	'module: module',
            'loadtype: load',
            ''
        )));
	    $action = new \PAMI\Message\Action\ModuleLoadAction('module');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_module_reload()
    {
        $write = array(implode("\r\n", array(
        	'action: ModuleLoad',
            'actionid: 1432.123',
        	'module: module',
            'loadtype: reload',
            ''
        )));
	    $action = new \PAMI\Message\Action\ModuleReloadAction('module');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_module_unload()
    {
        $write = array(implode("\r\n", array(
        	'action: ModuleLoad',
            'actionid: 1432.123',
        	'module: module',
            'loadtype: unload',
            ''
        )));
	    $action = new \PAMI\Message\Action\ModuleUnloadAction('module');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_monitor()
    {
        $write = array(implode("\r\n", array(
        	'action: Monitor',
            'actionid: 1432.123',
        	'channel: channel',
            'mix: true',
        	'format: wav',
        	'file: file',
            ''
        )));
	    $action = new \PAMI\Message\Action\MonitorAction('channel', 'file');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_voicemail_users_list()
    {
        $write = array(implode("\r\n", array(
        	'action: VoicemailUsersList',
            'actionid: 1432.123',
            ''
        )));
	    $action = new \PAMI\Message\Action\VoicemailUsersListAction;
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_pause_monitor()
    {
        $write = array(implode("\r\n", array(
        	'action: PauseMonitor',
            'actionid: 1432.123',
        	'channel: channel',
            ''
        )));
	    $action = new \PAMI\Message\Action\PauseMonitorAction('channel');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_unpause_monitor()
    {
        $write = array(implode("\r\n", array(
        	'action: UnpauseMonitor',
            'actionid: 1432.123',
        	'channel: channel',
            ''
        )));
	    $action = new \PAMI\Message\Action\UnpauseMonitorAction('channel');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_stop_monitor()
    {
        $write = array(implode("\r\n", array(
        	'action: StopMonitor',
            'actionid: 1432.123',
        	'channel: channel',
            ''
        )));
	    $action = new \PAMI\Message\Action\StopMonitorAction('channel');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_status()
    {
        $write = array(implode("\r\n", array(
        	'action: Status',
            'actionid: 1432.123',
        	'channel: channel',
            ''
        )));
	    $action = new \PAMI\Message\Action\StatusAction('channel');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_show_dialplan()
    {
        $write = array(implode("\r\n", array(
        	'action: ShowDialPlan',
            'actionid: 1432.123',
        	'context: context',
        	'extension: extension',
            ''
        )));
	    $action = new \PAMI\Message\Action\ShowDialPlanAction('context', 'extension');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_set_var()
    {
        $write = array(implode("\r\n", array(
        	'action: Setvar',
            'actionid: 1432.123',
        	'variable: variable',
        	'value: value',
            'channel: channel',
            ''
        )));
	    $action = new \PAMI\Message\Action\SetVarAction('variable', 'value', 'channel');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_reload()
    {
        $write = array(implode("\r\n", array(
        	'action: Reload',
            'actionid: 1432.123',
        	'module: module',
            ''
        )));
	    $action = new \PAMI\Message\Action\ReloadAction('module');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_ping()
    {
        $write = array(implode("\r\n", array(
        	'action: Ping',
            'actionid: 1432.123',
            ''
        )));
	    $action = new \PAMI\Message\Action\PingAction;
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_send_text()
    {
        $write = array(implode("\r\n", array(
        	'action: SendText',
            'actionid: 1432.123',
            'channel: channel',
            'message: message',
        	''
        )));
	    $action = new \PAMI\Message\Action\SendTextAction('channel', 'message');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_sip_show_registry()
    {
        $write = array(implode("\r\n", array(
        	'action: SIPshowregistry',
            'actionid: 1432.123',
        	''
        )));
	    $action = new \PAMI\Message\Action\SIPShowRegistryAction;
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_sip_peers()
    {
        $write = array(implode("\r\n", array(
        	'action: Sippeers',
            'actionid: 1432.123',
        	''
        )));
	    $action = new \PAMI\Message\Action\SIPPeersAction;
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_sip_notify()
    {
        $write = array(implode("\r\n", array(
        	'action: SIPnotify',
            'actionid: 1432.123',
            'channel: channel',
        	''
        )));
	    $action = new \PAMI\Message\Action\SIPNotifyAction('channel');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_sip_show_peer()
    {
        $write = array(implode("\r\n", array(
        	'action: SIPshowpeer',
            'actionid: 1432.123',
            'peer: peer',
        	''
        )));
	    $action = new \PAMI\Message\Action\SIPShowPeerAction('peer');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_sip_qualify_peer()
    {
        $write = array(implode("\r\n", array(
        	'action: Sipqualifypeer',
            'actionid: 1432.123',
            'peer: peer',
        	''
        )));
	    $action = new \PAMI\Message\Action\SIPQualifyPeerAction('peer');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_vgsm_sms_tx()
    {
        $write = array(implode("\r\n", array(
        	'action: vgsm_sms_tx',
            'actionid: 1432.123',
            'account: account',
            'x-sms-concatenate-total-messages: totalmsg',
            'x-sms-concatenate-sequence-number: seqnum',
            'x-sms-concatenate-refid: refid',
            'x-sms-class: class',
            'content: content',
            'x-sms-me: me',
            'content-transfer-encoding: encoding',
            'content-type: type',
            'to: to',
        	''
        )));
	    $action = new \PAMI\Message\Action\VGSMSMSTxAction;
	    $action->setAccount('account');
	    $action->setConcatTotalMsg('totalmsg');
	    $action->setConcatSeqNum('seqnum');
	    $action->setConcatRefId('refid');
	    $action->setSmsClass('class');
	    $action->setContent('content');
	    $action->setMe('me');
	    $action->setContentEncoding('encoding');
	    $action->setContentType('type');
	    $action->setTo('to');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_parked_calls()
    {
        $write = array(implode("\r\n", array(
        	'action: ParkedCalls',
            'actionid: 1432.123',
        	''
        )));
	    $action = new \PAMI\Message\Action\ParkedCallsAction;
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_queues()
    {
        $write = array(implode("\r\n", array(
        	'action: Queues',
            'actionid: 1432.123',
        	''
        )));
	    $action = new \PAMI\Message\Action\QueuesAction;
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_redirect()
    {
        $write = array(implode("\r\n", array(
        	'action: Redirect',
            'actionid: 1432.123',
            'channel: channel',
            'exten: extension',
            'context: context',
            'priority: priority',
            'extrapriority: extrapriority',
        	'extracontext: extracontext',
        	'extraexten: extraextension',
        	'extrachannel: extrachannel',
        	''
        )));
	    $action = new \PAMI\Message\Action\RedirectAction('channel', 'extension', 'context', 'priority');
	    $action->setExtraPriority('extrapriority');
	    $action->setExtraContext('extracontext');
	    $action->setExtraExtension('extraextension');
	    $action->setExtraChannel('extrachannel');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_queue_unpause()
    {
        $write = array(implode("\r\n", array(
        	'action: QueuePause',
            'actionid: 1432.123',
            'queue: queue',
            'reason: reason',
            'interface: interface',
            'paused: false',
        	''
        )));
	    $action = new \PAMI\Message\Action\QueueUnpauseAction('interface', 'queue', 'reason');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_queue_pause()
    {
        $write = array(implode("\r\n", array(
        	'action: QueuePause',
            'actionid: 1432.123',
            'queue: queue',
            'reason: reason',
            'interface: interface',
            'paused: true',
        	''
        )));
	    $action = new \PAMI\Message\Action\QueuePauseAction('interface', 'queue', 'reason');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_queue_summary()
    {
        $write = array(implode("\r\n", array(
        	'action: QueueSummary',
            'actionid: 1432.123',
            'queue: queue',
        	''
        )));
	    $action = new \PAMI\Message\Action\QueueSummaryAction('queue');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_queue_status()
    {
        $write = array(implode("\r\n", array(
        	'action: QueueStatus',
            'actionid: 1432.123',
            'queue: queue',
            'member: member',
        	''
        )));
	    $action = new \PAMI\Message\Action\QueueStatusAction('queue', 'member');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_queue_reset()
    {
        $write = array(implode("\r\n", array(
        	'action: QueueReset',
            'actionid: 1432.123',
            'queue: queue',
        	''
        )));
	    $action = new \PAMI\Message\Action\QueueResetAction('queue');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_queue_rule()
    {
        $write = array(implode("\r\n", array(
        	'action: QueueRule',
            'actionid: 1432.123',
            'rule: rule',
        	''
        )));
	    $action = new \PAMI\Message\Action\QueueRuleAction('rule');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_queue_remove()
    {
        $write = array(implode("\r\n", array(
        	'action: QueueRemove',
            'actionid: 1432.123',
            'queue: queue',
            'interface: interface',
        	''
        )));
	    $action = new \PAMI\Message\Action\QueueRemoveAction('queue', 'interface');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_queue_reload()
    {
        $write = array(implode("\r\n", array(
        	'action: QueueReload',
            'actionid: 1432.123',
            'queue: queue',
            'members: yes',
        	'rules: yes',
        	'parameters: yes',
        	''
        )));
	    $action = new \PAMI\Message\Action\QueueReloadAction('queue', true, true, true);
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_queue_penalty()
    {
        $write = array(implode("\r\n", array(
        	'action: QueuePenalty',
            'actionid: 1432.123',
            'interface: interface',
            'penalty: penalty',
        	'queue: queue',
        	''
        )));
	    $action = new \PAMI\Message\Action\QueuePenaltyAction('interface', 'penalty', 'queue');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_queue_log()
    {
        $write = array(implode("\r\n", array(
        	'action: QueueLog',
            'actionid: 1432.123',
        	'event: event',
        	'queue: queue',
        	'message: message',
            'interface: member',
            'uniqueid: uniqueid',
        	''
        )));
	    $action = new \PAMI\Message\Action\QueueLogAction('queue', 'event');
	    $action->setMessage('message');
	    $action->setMemberName('member');
	    $action->setUniqueId('uniqueid');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_queue_add()
    {
        $write = array(implode("\r\n", array(
        	'action: QueueAdd',
            'actionid: 1432.123',
            'interface: interface',
        	'queue: queue',
            'paused: true',
            'membername: member',
            'penalty: penalty',
            'stateinterface: state',
        	''
        )));
	    $action = new \PAMI\Message\Action\QueueAddAction('queue', 'interface');
	    $action->setPaused('true');
	    $action->setMemberName('member');
	    $action->setPenalty('penalty');
	    $action->setStateInterface('state');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_play_dtmf()
    {
        $write = array(implode("\r\n", array(
        	'action: PlayDTMF',
            'actionid: 1432.123',
            'channel: channel',
            'digit: 1',
        	''
        )));
	    $action = new \PAMI\Message\Action\PlayDTMFAction('channel', '1');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_park()
    {
        $write = array(implode("\r\n", array(
        	'action: Park',
            'actionid: 1432.123',
            'channel: channel1',
            'channel2: channel2',
            'timeout: timeout',
            'parkinglot: lot',
        	''
        )));
	    $action = new \PAMI\Message\Action\ParkAction('channel1', 'channel2', 'timeout', 'lot');
        $client = $this->_start($write, $action);
    }
    /**
     * @test
     */
    public function can_originate()
    {
        $write = array(implode("\r\n", array(
        	'action: Originate',
            'actionid: 1432.123',
            'channel: channel',
        	'codecs: a,b',
            'async: true',
            'account: account',
            'callerid: clid',
            'timeout: timeout',
            'data: data',
            'application: app',
            'priority: priority',
            'context: context',
            'exten: extension',
            'Variable: a=b',
        	''
        )));
	    $action = new \PAMI\Message\Action\OriginateAction('channel');
	    $action->setCodecs(array('a', 'b'));
	    $action->setAsync(true);
	    $action->setAccount('account');
	    $action->setCallerId('clid');
	    $action->setTimeout('timeout');
	    $action->setData('data');
	    $action->setApplication('app');
	    $action->setPriority('priority');
	    $action->setContext('context');
	    $action->setExtension('extension');
	    $action->setVariable('a', 'b');
        $client = $this->_start($write, $action);
    }
}
}