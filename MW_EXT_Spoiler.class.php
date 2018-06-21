<?php

namespace MediaWiki\Extension\MW_EXT_Spoiler;

use OutputPage, Parser, PPFrame, Skin;

/**
 * Class MW_EXT_Spoiler
 * ------------------------------------------------------------------------------------------------------------------ */
class MW_EXT_Spoiler {

	/**
	 * Clear DATA (escape html).
	 *
	 * @param $string
	 *
	 * @return string
	 * -------------------------------------------------------------------------------------------------------------- */

	private static function clearData( $string ) {
		$outString = htmlspecialchars( trim( $string ), ENT_QUOTES );

		return $outString;
	}

	/**
	 * Get MediaWiki issue.
	 *
	 * @param $string
	 *
	 * @return string
	 * -------------------------------------------------------------------------------------------------------------- */

	private static function getMsgText( $string ) {
		$outString = wfMessage( 'mw-ext-spoiler-' . $string )->inContentLanguage()->text();

		return $outString;
	}

	/**
	 * Register tag function.
	 *
	 * @param Parser $parser
	 *
	 * @return bool
	 * @throws \MWException
	 * -------------------------------------------------------------------------------------------------------------- */

	public static function onParserFirstCallInit( Parser $parser ) {
		$parser->setHook( 'spoiler', __CLASS__ . '::onRenderTag' );

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
	 * -------------------------------------------------------------------------------------------------------------- */

	public static function onRenderTag( $input, $args = [], Parser $parser, PPFrame $frame ) {
		// Argument: title.
		$getTitle = self::clearData( $args['title'] ?? '' ?: '' );
		$outTitle = empty( $getTitle ) ? self::getMsgText( 'title' ) : $getTitle;

		// Get content.
		$getContent = trim( $input );
		$outContent = $parser->recursiveTagParse( $getContent, $frame );

		// Out HTML.
		$outHTML = '<details class="mw-ext-spoiler">';
		$outHTML .= '<summary>' . $outTitle . '</summary>';
		$outHTML .= '<div class="mw-ext-spoiler-body"><div class="mw-ext-spoiler-content">' . $outContent . '</div></div>';
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
	 * -------------------------------------------------------------------------------------------------------------- */

	public static function onBeforePageDisplay( OutputPage $out, Skin $skin ) {
		$out->addModuleStyles( [ 'ext.mw.spoiler.styles' ] );

		return true;
	}
}
