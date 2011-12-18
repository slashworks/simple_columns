<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Lingo4you 2011
 * @author     Mario Müller <http://www.lingo4u.de/>
 * @package    SimpleColumns
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

class SimpleColumnsHook extends Frontend {

	public function myGetContentElement($objElement, $strBuffer)
	{
		if ($objElement->simple_columns == '')
		{
			return $strBuffer;
		}
		
		if (preg_match('~(.*?)(<[a-z]+[^>]*>)(.*)~ism', $strBuffer, $match))
		{
			if (($objElement->simple_columns != '') && !empty($GLOBALS['SIMPLECOLUMNS']['style']))
			{
				$GLOBALS['TL_CSS'][] = $GLOBALS['SIMPLECOLUMNS']['style'];
				$GLOBALS['SIMPLECOLUMNS']['style'] = '';
			}
	
			if ($objElement->simple_columns != '')
			{
				global $simpleColumnCounter;
		
				if (!is_array($simpleColumnCounter))
				{
					$simpleColumnCounter = array(2=>0, 3=>0, 4=>0, 5=>0, 6=>0);
				}
		
				$scClass = 'sc sc' . $objElement->simple_columns;

				$columns = (strlen($objElement->simple_columns) == 1 ? (int)$objElement->simple_columns : (int)substr($objElement->simple_columns, 0, 1));
				$columnCount = (strlen($objElement->simple_columns) == 1 ? 1 : (int)substr($objElement->simple_columns, 2, 1));
		
				if ($simpleColumnCounter[$columns] == 0)
				{
					$scClass .= ' sc-first sc' . $objElement->simple_columns . '-first';
					$simpleColumnCounter[$columns] += $columnCount;
				}
				elseif ($simpleColumnCounter[$columns] < $columns-$columnCount)
				{
					$simpleColumnCounter[$columns] += $columnCount;
				}
				else
				{
					$scClass .= ' sc-last sc' . $objElement->simple_columns . '-last';
					$simpleColumnCounter[$columns] = 0;
					$objElement->simple_columns_close = true;
				}

				if ($this->simple_columns_close)
				{
					$scClass .= ' sc-close';
				}

				$count = 0;
				$match[2] = preg_replace('~(class="[^"]*)"~iU', '$1 '.$scClass.'"', $match[2], 1, $count);
				
				if ($count < 1)
				{
					$match[2] = str_replace('>', ' class="'.$scClass.'">', $match[2]);
				}
				
				$strBuffer = $match[1].$match[2].$match[3];

				if ($objElement->simple_columns_close && !empty($GLOBALS['SIMPLECOLUMNS']['close']))
				{
					$strBuffer .= $GLOBALS['SIMPLECOLUMNS']['close'];
				}
			}
		}

		return $strBuffer;
	}

}

?>