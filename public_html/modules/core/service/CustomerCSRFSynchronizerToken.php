<?php

class CustomerCSRFSynchronizerToken extends CSRFSynchronizerToken
{
    public const SESSION = 'customer' . parent::SESSION;
}