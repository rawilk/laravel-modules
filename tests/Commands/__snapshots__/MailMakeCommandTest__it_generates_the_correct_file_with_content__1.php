<?php return '<?php

namespace Modules\\Blog\\Mail;

use Illuminate\\Bus\\Queueable;
use Illuminate\\Queue\\SerializesModels;
use Illuminate\\Contracts\\Queue\\ShouldQueue;
use Illuminate\\Mail\\Mailable;

class SomeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view(\'view.name\');
    }
}
';
