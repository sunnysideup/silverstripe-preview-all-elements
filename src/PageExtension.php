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

        if ($this->getOwner()->getRequest()->getVar('previewall') && $this->getOwner()->hasMethod('ElementalArea')) {
            $elementalArea = $this->getOwner()->ElementalArea();
            if ($elementalArea) {
                $this->getOwner()->ElementalArea->LoadAllElements();
            }
        }
    }
}
