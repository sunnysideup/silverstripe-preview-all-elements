<?php

namespace Sunnysideup\PreviewAllElements;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Control\Director;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Extension;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DB;

/**
 * @property int $max_elements_for_preview
 * @property ElementalAreaExtension $owner
 * @property array $cacheData
 */

class ElementalAreaExtension extends Extension
{
    private static $max_elements_for_preview = 100;

    public function LoadAllElements(?string $className = BaseElement::class, ?array $sort = null)
    {
        if (Director::isLive()) {
            return;
        }
        if (! $sort) {
            $sort = DB::get_conn()->random() . ' ASC';
        }
        $owner = $this->getOwner();
        $list = $className::get();
        $count = $list->count();
        $limit = $owner->config()->get('max_elements_for_preview');
        if ($count > $limit) {
            $classes = ClassInfo::subclassesFor($className, false);
            $classCount = count($classes);
            $idArray = [];
            $maxPerClass = floor($limit / $classCount);
            if ($maxPerClass < 1) {
                $maxPerClass = 1;
            }
            foreach ($classes as $subClassName) {
                $idArray = array_merge(
                    $idArray,
                    $subClassName::get()
                        ->orderBy($sort)
                        ->limit($maxPerClass)
                        ->column('ID')
                );
            }
            $list = $className::get()
                ->filter(['ID' => $idArray])
                ->orderBy($sort);
        }

        $al = ArrayList::create();
        $list = $list->orderBy($sort);
        foreach ($list as $element) {
            if ($element->canView()) {
                $al->push($element);
            }
        }
        $owner->setElementsCached($al);
    }
}
