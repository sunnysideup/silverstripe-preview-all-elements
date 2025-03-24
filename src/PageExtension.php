<?php

namespace Sunnysideup\PreviewAllElements;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Control\Director;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Extension;
use SilverStripe\ORM\DB;

class PageExtension extends Extension
{
    public function onAfterInit()
    {
        if (Director::isLive()) {
            return;
        }
        if ($this->owner->getRequest()->getVar('previewall')) {
            $elementalArea = $this->owner->ElementalArea();
            if ($elementalArea) {
                $this->owner->ElementalArea->LoadAllElements();
            }
        }
    }
}
