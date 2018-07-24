<?php 

namespace Voting\Infrastructure;

class MenuOptions
{
    public $title = '';
    public $capability = 'manage_options';
    public $isSubPage = false;
    public $slug;
    public $iconURL;
    public $position = 20;
    public $templateName = '';
    public $type;
    public $parentSlug;

    public function __construct(array $input = [])
    {
        if (isset($input['capability'])) {
            $this->capability = $input['capability'];
        }

        if (isset($input['title'])) {
            $this->title = $input['title'];
        }

        if (isset($input['isSubPage'])) {
            $this->isSubPage = $input['isSubPage'];
        }

        if (isset($input['slug'])) {
            $this->slug = $input['slug'];
        }

        if (isset($input['iconURL'])) {
            $this->iconURL = $input['iconURL'];
        }

        if (isset($input['templateName'])) {
            $this->templateName = $input['templateName'];
        }

        if (isset($input['type'])) {
            $this->type = $input['type'];
        }

        if (isset($input['parentSlug'])) {
            $this->parentSlug = $input['parentSlug'];
        }

        if (isset($input['position'])) {
            $this->position = $input['position'];
        }
    }
}