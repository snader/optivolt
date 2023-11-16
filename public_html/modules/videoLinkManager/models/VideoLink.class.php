<?php

class VideoLink extends Media
{
    public $online = 1;

    /**
     * check if file online/offline may be changed
     *
     * @return bool
     */
    public function isOnlineChangeable()
    {
        return true;
    }

    /**
     * check if file is editable
     *
     * @return bool
     */
    public function isEditable()
    {
        return true;
    }

    /**
     * check if file is deletable
     *
     * @return bool
     */
    public function isDeletable()
    {
        return true;
    }
}
