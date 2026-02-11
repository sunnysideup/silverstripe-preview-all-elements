<?php

namespace Sunnysideup\PreviewAllElements;

use SilverStripe\Control\Director;
use SilverStripe\Core\Extension;

class PageExtension extends Extension
{
    public function onAfterInit()
    {
        if (Director::isLive()) {
            return;
        }
        if ($this->owner->getRequest()->getVar('previewall') && $this->owner->hasMethod('ElementalArea')) {
            $elementalArea = $this->owner->ElementalArea();
            if ($elementalArea) {
                $this->owner->ElementalArea->LoadAllElements();
            }
        }
    }
}
