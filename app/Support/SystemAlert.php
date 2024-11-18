<?php

namespace App\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Session;

class SystemAlert implements Arrayable
{
    /**
     * SystemAlert constructor.
     *
     * @param string $status
     * @param string|null $message
     */
    public function __construct(protected string $status = 'info', protected ?string $message = null) {}

    /**
     * Set success system alert message.
     *
     * @param string $message
     * @return self
     */
    public static function success(string $message): self
    {
        return new self('success', $message);
    }

    /**
     * Set error system alert message.
     *
     * @param string $message
     * @return self
     */
    public static function error(string $message): self
    {
        return new self('danger', $message);
    }

    /**
     * Set warning system alert message.
     *
     * @param string $message
     * @return self
     */
    public static function warning(string $message): self
    {
        return new self('warning', $message);
    }

    /**
     * Set info system alert message.
     *
     * @param string $message
     * @return self
     */
    public static function info(string $message): self
    {
        return new self('info', $message);
    }

    /**
     * Set alert status.
     *
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Set alert message.
     *
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Get alert configuration.
     *
     * @return array
     */
    public function getAlert(): array
    {
        return [
            'status'  => $this->status,
            'message' => $this->message,
        ];
    }

    /**
     * Put a message into flash session.
     *
     * @return void
     */
    public function toSession(): void
    {
        foreach ($this->toArray() as $key => $value) {
            Session::flash($key, $value);
        }
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return ['system_alert' => $this->getAlert()];
    }

    /**
     * Read an alert message from the session.
     *
     * @return array
     */
    public static function read(): array
    {
        return [
            'status'  => fn () => session()->get('system_alert.status'),
            'message' => fn () => session()->get('system_alert.message'),
        ];
    }
}
