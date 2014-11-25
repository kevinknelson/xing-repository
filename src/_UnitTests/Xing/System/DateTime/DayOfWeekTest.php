<?php

namespace _UnitTests\Xing\Mapping\System\DateTime {
    use Xing\System\DateTime\DayOfWeek;

    class DayOfWeekTest extends \PHPUnit_Framework_TestCase {
        public function test_all() {
            $sunday     = DayOfWeek::Sunday();
            $saturday   = $sunday->previous();
            $monday     = $saturday->next()->next()->next()->next()->next()->next()->next()->next()->next();

            $this->assertEquals( DayOfWeek::Saturday, $saturday->Value);
            $this->assertEquals( DayOfWeek::Sunday, $sunday->Value);
            $this->assertEquals( DayOfWeek::Monday, $monday->Value);
        }
    }
}
