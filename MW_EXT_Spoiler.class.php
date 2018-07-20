<?php

namespace MediaWiki\Extension\MW_EXT_Spoiler;

use OutputPage, Parser, PPFrame, Skin;
use MediaWiki\Extension\MW_EXT_Kernel\MW_EXT_Kernel;

/**
 * Class MW_EXT_Spoiler
 */
class MW_EXT_Spoiler {

	/**
	 * Register tag function.
	 *
	 * @param Parser $parser
	 *
	 * @return bool
	 * @throws \MWException
	 */
	public static function onParserFirstCallInit( Parser $parser ) {
		$parser->setHook( 'spoiler', [ __CLASS__, 'onRenderTag' ] );

		return true;
	}

	/**
	 * Render tag function.
	 *
	 * @param $input
	 * @param array $args
	 * @param Parser $parser
	 * @param PPFrame $frame
	 *
	 * @return string
	 */
	public static function onRenderTag( $input, $args = [], Parser $parser, PPFrame $frame ) {
		// Argument: title.
		$getTitle = MW_EXT_Kernel::outClear( $args['title'] ?? '' ?: '' );
		$outTitle = empty( $getTitle ) ? MW_EXT_Kernel::getMessageText( 'spoiler', 'title' ) : $getTitle;

		// Get content.
		$getContent = trim( $input );
		$outContent = $parser->recursiveTagParse( $getContent, $frame );

		// Out HTML.
		$outHTML = '<details class="mw-ext-spoiler navigation-not-searchable">';
		$outHTML .= '<summary>' . $outTitle . '</summary>';
		$outHTML .= '<div class="mw-ext-spoiler-body"><div class="mw-ext-spoiler-content">' . "\n\r" . $outContent . "\n\r" . '</div></div>';
		$outHTML .= '</details>';

		// Out parser.
		$outParser = $outHTML;

		return $outParser;
	}

	/**
	 * Load resource function.
	 *
	 * @param OutputPage $out
	 * @param Skin $skin
	 *
	 * @return bool
	 */
	public static function onBeforePageDisplay( OutputPage $out, Skin $skin ) {
		$out->addModuleStyles( [ 'ext.mw.spoiler.styles' ] );

		return true;
	}
}
