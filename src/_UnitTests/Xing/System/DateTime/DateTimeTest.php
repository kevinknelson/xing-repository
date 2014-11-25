<?php

namespace _UnitTests\Xing\Mapping\System\DateTime {
    use Xing\System\DateTime\DateTime;
    use Xing\System\DateTime\DayOfWeek;
    use Xing\System\DateTime\Timezone;

    class DateTimeTest extends \PHPUnit_Framework_TestCase {
        public function test_getDateTimeInTimezone() {
            $date       = new DateTime('2010-01-31 12:00:00', Timezone::Cst()->PhpTimezone);
            $asIfEst    = DateTime::getDateTimeInTimezone($date, Timezone::Est()->PhpTimezone);
            $this->assertEquals( $date->__toString(), $asIfEst->__toString() );
            $this->assertTrue( $date->getTimestamp() > $asIfEst->getTimestamp() );
        }
        public function test_get() {
            $date       = new DateTime('2015-01-31 12:15:20', Timezone::Cst()->PhpTimezone);
            $this->assertEquals(2015,$date->Year);
            $this->assertEquals(1,$date->Month);
            $this->assertEquals(31,$date->Day);
            $this->assertEquals(12,$date->Hours);
            $this->assertEquals(15,$date->Minutes);
            $this->assertEquals(20,$date->Seconds);
            $this->assertEquals(13,$date->addHours(1)->Hours);
            $this->assertTrue( $date->DayOfWeek->is( DayOfWeek::Saturday ) );
            $this->assertTrue( $date->DayOfWeekInt === DayOfWeek::Saturday );
        }
        public function test_atTimezone() {
            $date       = new DateTime('2015-01-31 12:15:20', Timezone::Cst()->PhpTimezone);
            $newDate    = $date->atTimezone( Timezone::Est()->PhpTimezone );

            $this->assertEquals(12, $date->Hours); //immutability test
            $this->assertEquals(13, $newDate->Hours);
        }
        public function test_atTime() {
            $date       = new DateTime('2015-01-31 12:15:20', Timezone::Cst()->PhpTimezone);
            $newDate    = $date->atTime(14,25,30);

            $this->assertEquals(14,$newDate->Hours);
            $this->assertEquals(25,$newDate->Minutes);
            $this->assertEquals(30,$newDate->Seconds);

            //test immutability
            $this->assertEquals(12,$date->Hours);
            $this->assertEquals(15,$date->Minutes);
            $this->assertEquals(20,$date->Seconds);
        }
        public function test_atHour() {
            $date       = new DateTime('2015-01-31 12:15:20', Timezone::Cst()->PhpTimezone);
            $newDate    = $date->atHour(14);

            $this->assertEquals('14:15:20',$newDate->format('H:i:s'));

            //test immutability
            $this->assertEquals('12:15:20',$date->format('H:i:s'));
        }
        public function test_atMinutes() {
            $date       = new DateTime('2015-01-31 12:15:20', Timezone::Cst()->PhpTimezone);
            $modified   = $date->atMinutes(30);

            $this->assertEquals('12:30:20',$modified->format('H:i:s'));

            //test immutability
            $this->assertEquals('12:15:20',$date->format('H:i:s'));
        }
        public function test_atSeconds() {
            $date       = new DateTime('2015-01-31 12:15:20', Timezone::Cst()->PhpTimezone);
            $modified   = $date->atSeconds(30);

            $this->assertEquals('12:15:30',$modified->format('H:i:s'));

            //test immutability
            $this->assertEquals('12:15:20',$date->format('H:i:s'));
        }
        public function test_atYear() {
            $date       = new DateTime('2015-01-30 12:15:20', Timezone::Cst()->PhpTimezone);
            $modified   = $date->atYear(2014);
            $this->assertEquals('2014-01-30', $modified->format('Y-m-d'));

            //test immutability
            $this->assertEquals('2015-01-30',$date->format('Y-m-d'));
        }
        public function test_atMonth() {
            $date       = new DateTime('2015-01-30 12:15:20', Timezone::Cst()->PhpTimezone);
            $modified   = $date->atMonth(4);
            $this->assertEquals('2015-04-30', $modified->format('Y-m-d'));

            //test immutability
            $this->assertEquals('2015-01-30',$date->format('Y-m-d'));
        }
        public function test_atDay() {
            $date       = new DateTime('2015-01-30 12:15:20', Timezone::Cst()->PhpTimezone);
            $modified   = $date->atDay(20);
            $this->assertEquals('2015-01-20', $modified->format('Y-m-d'));

            //test immutability
            $this->assertEquals('2015-01-30',$date->format('Y-m-d'));
        }
        public function test_addYears() {
            $date       = new DateTime('2015-01-30 12:15:20', Timezone::Cst()->PhpTimezone);
            $modified   = $date->addYears(1);
            $this->assertEquals('2016-01-30', $modified->format('Y-m-d'));

            //test immutability
            $this->assertEquals('2015-01-30',$date->format('Y-m-d'));
        }
        public function test_addMonths() {
            $date       = new DateTime('2015-01-31 12:15:20', Timezone::Cst()->PhpTimezone);
            $modified   = $date->addMonths(1);
            $this->assertEquals('2015-02-28', $modified->format('Y-m-d'));

            //test immutability
            $this->assertEquals('2015-01-31',$date->format('Y-m-d'));
        }
        public function test_addWeeks() {
            $date       = new DateTime('2015-01-31 12:15:20', Timezone::Cst()->PhpTimezone);
            $modified   = $date->addWeeks(3);
            $this->assertEquals('2015-02-21', $modified->format('Y-m-d'));

            //test immutability
            $this->assertEquals('2015-01-31',$date->format('Y-m-d'));
        }
        public function test_addDays() {
            $date       = new DateTime('2015-01-31 12:15:20', Timezone::Cst()->PhpTimezone);
            $modified   = $date->addDays(1.25);
            $this->assertEquals('2015-02-01 18:15:20', $modified->format('Y-m-d H:i:s'));
            $modified   = $date->addDays(1.5);
            $this->assertEquals('2015-02-02 00:15:20', $modified->format('Y-m-d H:i:s'));

            //test immutability
            $this->assertEquals('2015-01-31',$date->format('Y-m-d'));
        }
        public function test_addHours() {
            $date       = new DateTime('2015-01-31 12:15:20', Timezone::Cst()->PhpTimezone);
            $modified   = $date->addHours(1.25);
            $this->assertEquals('2015-01-31 13:30:20', $modified->format('Y-m-d H:i:s'));
            $modified   = $date->addHours(121.25);
            $this->assertEquals('2015-02-05 13:30:20', $modified->format('Y-m-d H:i:s'));

            //test immutability
            $this->assertEquals('2015-01-31 12:15:20',$date->format('Y-m-d H:i:s'));
        }
        public function test_addMinutes() {
            $date       = new DateTime('2015-01-31 12:15:20', Timezone::Cst()->PhpTimezone);
            $modified   = $date->addMinutes(34.5);
            $this->assertEquals('2015-01-31 12:49:50', $modified->format('Y-m-d H:i:s'));

            //test immutability
            $this->assertEquals('2015-01-31 12:15:20',$date->format('Y-m-d H:i:s'));
        }
        public function test_addSeconds() {
            $date       = new DateTime('2015-01-31 12:15:20', Timezone::Cst()->PhpTimezone);
            $modified   = $date->addSeconds(86400);
            $this->assertEquals('2015-02-01 12:15:20', $modified->format('Y-m-d H:i:s'));

            //test immutability
            $this->assertEquals('2015-01-31 12:15:20',$date->format('Y-m-d H:i:s'));
        }
        public function test_getStartOfWeek() {
            $date       = new DateTime('2015-01-31 12:15:20', Timezone::Cst()->PhpTimezone);
            $modified   = $date->getStartOfWeek( DayOfWeek::Sunday );
            $this->assertEquals('2015-01-25 12:15:20', $modified->format('Y-m-d H:i:s'));
            $modified   = $modified->getStartOfWeek( DayOfWeek::Sunday );
            $this->assertEquals('2015-01-25 12:15:20', $modified->format('Y-m-d H:i:s'));
            $modified   = $modified->getStartOfWeek( DayOfWeek::Monday );
            $this->assertEquals('2015-01-19 12:15:20', $modified->format('Y-m-d H:i:s'));

            $modified   = $date->getStartOfWeek( DayOfWeek::Monday );
            $this->assertEquals('2015-01-26 12:15:20', $modified->format('Y-m-d H:i:s'));
            $modified   = $modified->getStartOfWeek( DayOfWeek::Monday );
            $this->assertEquals('2015-01-26 12:15:20', $modified->format('Y-m-d H:i:s'));
            $modified   = $modified->getStartOfWeek( DayOfWeek::Sunday );
            $this->assertEquals('2015-01-25 12:15:20', $modified->format('Y-m-d H:i:s'));

            //test immutability
            $this->assertEquals('2015-01-31 12:15:20',$date->format('Y-m-d H:i:s'));
        }
        public function test_getFirstDayOfMonth() {
            $date       = new DateTime('2015-01-31 12:15:20', Timezone::Cst()->PhpTimezone);
            $modified   = $date->getFirstDayOfMonth();
            $this->assertEquals('2015-01-01 12:15:20', $modified->format('Y-m-d H:i:s'));

            //test immutability
            $this->assertEquals('2015-01-31 12:15:20',$date->format('Y-m-d H:i:s'));
        }
        public function test_getLastDayOfMonth() {
            $date       = new DateTime('2015-01-30 12:15:20', Timezone::Cst()->PhpTimezone);
            $modified   = $date->getLastDayOfMonth();
            $this->assertEquals('2015-01-31', $modified->format('Y-m-d'));

            //test immutability
            $this->assertEquals('2015-01-30',$date->format('Y-m-d'));
        }
        public function test_asSerializable() {
            $date       = new DateTime('2015-01-30 12:15:20', Timezone::Cst()->PhpTimezone);
            $this->assertEquals('Fri, 30 Jan 2015 12:15:20 -0600', $date->asSerializable());
        }
    }
}
