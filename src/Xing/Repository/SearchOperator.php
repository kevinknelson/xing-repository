<?php
/**
 * @package Xing\Repository
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Repository {
	use Xing\System\AEnum;

	/**
	 *
	 * @method static \Xing\Repository\SearchOperator IsEqualTo()
	 * @method static \Xing\Repository\SearchOperator IsNotEqualTo()
	 * @method static \Xing\Repository\SearchOperator IsInSet()
	 * @method static \Xing\Repository\SearchOperator IsNotInSet()
	 * @method static \Xing\Repository\SearchOperator IsGreaterThan()
	 * @method static \Xing\Repository\SearchOperator IsGreaterThanOrEqualTo()
	 * @method static \Xing\Repository\SearchOperator IsLessThan()
	 * @method static \Xing\Repository\SearchOperator IsLessThanOrEqualTo()
	 * @method static \Xing\Repository\SearchOperator AndNext()
	 * @method static \Xing\Repository\SearchOperator OrNext()
	 * @method static \Xing\Repository\SearchOperator GroupPreviousAndNext()
	 * @method static \Xing\Repository\SearchOperator GroupPreviousOrNext()
     *
     * @property string $PhpComparisonOperator
	 */
	class SearchOperator extends AEnum {
		const IsEqualTo					= 1;
		const IsNotEqualTo				= 2;
		const IsInSet					= 3;
		const IsNotInSet				= 4;
		const IsGreaterThan				= 5;
		const IsGreaterThanOrEqualTo 	= 6;
		const IsLessThan				= 7;
		const IsLessThanOrEqualTo		= 8;

		const AndNext					= 100;
		const OrNext					= 101;
		const GroupPreviousAndNext		= 102;
		const GroupPreviousOrNext		= 103;
	}
}