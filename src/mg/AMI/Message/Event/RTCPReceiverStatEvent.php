<?php
namespace AMI\Message\Event;

use AMI\Message\Event\EventMessage;

class RTCPReceiverStatEvent extends EventMessage
{
    /**
     * Returns key: 'Privilege'.
     *
     * @return string
     */
    public function getPrivilege()
    {
        return $this->getKey('Privilege');
    }

    /**
     * Returns key: 'SSRC'.
     *
     * @return string
     */
    public function getSSRC()
    {
        return $this->getKey('SSRC');
    }

    /**
     * Returns key: 'ReceivedPackets'.
     *
     * @return string
     */
    public function getReceivedPackets()
    {
        return $this->getKey('ReceivedPackets');
    }
    
    /**
     * Returns key: 'LostPackets'.
     *
     * @return string
     */
    public function getLostPackets()
    {
        return $this->getKey('LostPackets');
    }

    /**
     * Returns key: 'Jitter'.
     *
     * @return string
     */
    public function getJitter()
    {
        return $this->getKey('Jitter');
    }

    /**
     * Returns key: 'Transit'.
     *
     * @return string
     */
    public function getTransit()
    {
        return $this->getKey('Transit');
    }

    /**
     * Returns key: 'RRCount'.
     *
     * @return string
     */
    public function getRRCount()
    {
        return $this->getKey('RRCount');
    }
    
    /**
     * Constructor.
     *
     * @param string $rawContent Literal message as received from ami.
     * 
     * @return void
     */
    public function __construct($rawContent)
    {
        parent::__construct($rawContent);
    }
}