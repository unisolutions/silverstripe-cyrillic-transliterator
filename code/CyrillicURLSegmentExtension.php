<?php
/**
 * Add support for cyrillic transliteration in url segment.
 *
 * @package cyrillic-transliterator
 */
class CyrillicURLSegmentExtension extends DataExtension {

	public function updateURLSegment(&$t, $title) {
		$filter = URLSegmentFilter::create();

		// use default transliterator
		$title = $filter->getTransliterator()->toASCII($title);

		// set cyrillic transliterator
		$filter->setTransliterator(CyrillicTransliterator::create());

		$t = $filter->filter($title);

		// Fallback to generic page name if path is empty (= no valid, convertable characters)
		if (!$t || $t == '-' || $t == '-1') $t = "page-" . $this->owner->ID;
	}

}
