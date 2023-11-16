<?php

class TemplateGroup extends Model
{

    public $templateGroupId;
    public $templateGroupName; // description of the template, where is it used for
    public $templateVariables; //text field with usable template variables [variable_replace_name]
    public $name; // unqiue name for template group to use in code, for special handling, instead of ID

    /**
     * validate object
     */

    public function validate()
    {
        if (empty($this->templateGroupName)) {
            $this->setPropInvalid('templateGroupName');
        }
        if (!empty($this->templateGroupName) && ($oTemplateGroup = TemplateGroupManager::getTemplateGroupByGroupName($this->templateGroupName))) {
            if ($oTemplateGroup->templateGroupId != $this->templateGroupId) {
                $this->setPropInvalid('templateGroupName');
            }
        }
        if (!empty($this->name) && ($oTemplateGroup = TemplateGroupManager::getTemplateGroupByName($this->name))) {
            if ($oTemplateGroup->templateGroupId != $this->templateGroupId) {
                $this->setPropInvalid('name');
            }
        }
    }

    /**
     * check if deletable
     *
     * @return boolean
     */
    public function isDeletable()
    {
        return count(TemplateManager::getTemplatesByFilter(['templateGroupId' => $this->templateGroupId ? $this->templateGroupId : -1])) == 0;
    }

}

?>