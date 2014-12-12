<?php
/**
 * @package Xing\Models\Search
 * @copyright 2013 Kevin K. Nelson (xingcreative.com)
 * Licensed under the MIT license
 */
namespace Xing\Models\Search {
	use Xing\System\AEnum;

	/**
	 *
	 * @method static SearchOperator IsEqualTo()
	 * @method static SearchOperator IsNotEqualTo()
	 * @method static SearchOperator IsInSet()
	 * @method static SearchOperator IsNotInSet()
	 * @method static SearchOperator IsGreaterThan()
	 * @method static SearchOperator IsGreaterThanOrEqualTo()
	 * @method static SearchOperator IsLessThan()
	 * @method static SearchOperator IsLessThanOrEqualTo()
	 * @method static SearchOperator IsBetween()
	 * @method static SearchOperator AndNext()
	 * @method static SearchOperator OrNext()
	 * @method static SearchOperator GroupPreviousAndNext()
	 * @method static SearchOperator GroupPreviousOrNext()
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
		const IsBetween					= 9;

		const AndNext					= 100;
		const OrNext					= 101;
		const GroupPreviousAndNext		= 102;
		const GroupPreviousOrNext		= 103;
	}
}