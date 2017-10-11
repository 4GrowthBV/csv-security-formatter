<?php

namespace InThere\CsvSecurityFormatter\Tests\Unit;

use PHPUnit\Framework\TestCase;

class CsvSecurityFormatterTest extends TestCase
{
    public function testExistence()
    {
        $this->assertTrue(class_exists('InThere\CsvSecurityFormatter\CsvSecurityFormatter'));
    }

    public function testCsvWithoutQuotesAndFormula()
    {
        $csvSecurityFormatter = new \InThere\CsvSecurityFormatter\CsvSecurityFormatter();
        $result = $csvSecurityFormatter(['a', 'b', 'c', 'd', 'e', 'f']);

        $this->assertSame('a,b,c,d,e,f', implode(',', $result));
    }

    public function testCsvWithQuotesAndWithoutFormula()
    {
        $csvSecurityFormatter = new \InThere\CsvSecurityFormatter\CsvSecurityFormatter();
        $result = $csvSecurityFormatter(['"a"', '"b"', '"c"', 'd', 'e', 'f']);

        $this->assertSame('"a","b","c",d,e,f', implode(',', $result));
    }

    public function testCsvWithoutQuotesAndWithFormulaEscaped()
    {
        $csvSecurityFormatter = new \InThere\CsvSecurityFormatter\CsvSecurityFormatter();
        $result = $csvSecurityFormatter(['=2*5', '"b"', '"c"', 'd', 'e', 'f']);

        $this->assertSame("\t=2*5,\"b\",\"c\",d,e,f", implode(',', $result));
    }

    public function testCsvWithQuotesAndWithFormulaEscaped()
    {
        $csvSecurityFormatter = new \InThere\CsvSecurityFormatter\CsvSecurityFormatter();
        $result = $csvSecurityFormatter(['"=2*5"', '"b"', '"c"', 'd', 'e', 'f']);

        $this->assertSame("\"\t=2*5\",\"b\",\"c\",d,e,f", implode(',', $result));
    }

    public function testCsvWithoutQuotesAndWithFormulaRemoved()
    {
        $csvSecurityFormatter = new \InThere\CsvSecurityFormatter\CsvSecurityFormatter(false);
        $result = $csvSecurityFormatter(['=2*5', 'b', 'c', 'd', 'e', 'f']);

        $this->assertSame("2*5,b,c,d,e,f", implode(',', $result));
    }

    public function testCsvWithQuotesAndWithFormulaRemoved()
    {
        $csvSecurityFormatter = new \InThere\CsvSecurityFormatter\CsvSecurityFormatter(false);
        $result = $csvSecurityFormatter(['"=2*5"', '"b"', '"c"', 'd', 'e', 'f']);

        $this->assertSame("\"2*5\",\"b\",\"c\",d,e,f", implode(',', $result));
    }
}
