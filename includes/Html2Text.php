<?php
/******************************************************************************
 * Copyright (c) 2010 Jevon Wright and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * or
 *
 * LGPL which is available at http://www.gnu.org/licenses/lgpl.html
 *
 *
 * Contributors:
 *    Jevon Wright - initial API and implementation
 ****************************************************************************/

namespace Html2TextCBXMCRatingReview;

use Soundasleep\Html2Text as SoundasleepHtml2Text;

class Html2Text {

	static function convert( $html ) {
		//trigger_error( "Please update addon plugins as this method is deprecated now.", E_USER_NOTICE );
		return SoundasleepHtml2Text::convert( $html );
	}

	static function fixNewlines( $text ) {
		//trigger_error( "Please update addon plugins as this method is deprecated now.", E_USER_NOTICE );
		return SoundasleepHtml2Text::fixNewlines( $text );
	}
}//end class Html2Text