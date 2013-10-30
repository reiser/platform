<?php

namespace Oro\Bundle\EmailBundle\Form\Model;

class Email
{
    /**
     * @var string
     */
    protected $gridId;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string[]
     */
    protected $to;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $body;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->to = array();
    }

    /**
     * Get id of emails datagrid
     *
     * @return string
     */
    public function getGridId()
    {
        return $this->gridId;
    }

    /**
     * Set id of emails datagrid
     *
     * @param string $gridId
     * @return $this
     */
    public function setGridId($gridId)
    {
        $this->gridId = $gridId;

        return $this;
    }

    /**
     * Get FROM email address
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set FROM email address
     *
     * @param string $from
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get TO email addresses
     *
     * @return string[]
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set TO email addresses
     *
     * @param string[] $to
     * @return $this
     */
    public function setTo(array $to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get email subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set email subject
     *
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get email body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set email body
     *
     * @param string $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }
}
