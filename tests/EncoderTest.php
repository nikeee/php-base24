<?php

declare(strict_types=1);

namespace Afonso\Base24;

class EncoderTest extends \PHPUnit\Framework\TestCase
{
    private $encoder;

    protected function setUp(): void
    {
        $this->encoder = new Encoder();
    }

    /**
     * @dataProvider getTestData
     */
    public function testEncode($decoded, $encoded): void
    {
        $this->assertSame(strtoupper($encoded), $this->encoder->encode($decoded));
    }

    /**
     * @dataProvider getBinaryTestData
     */
    public function testBinaryStringEncode($decoded, $encoded): void
    {
        $this->assertSame(strtoupper($encoded), $this->encoder->encodeBinaryString($decoded));
    }

    /**
     * @dataProvider getTestData
     */
    public function testDecode($decoded, $encoded): void
    {
        $this->assertSame($decoded, $this->encoder->decode($encoded));
    }

    /**
     * @dataProvider getBinaryTestData
     */
    public function testBinaryStringDecode($decoded, $encoded): void
    {
        $this->assertSame($decoded, $this->encoder->decodeBinaryString($encoded));
    }

    public function testEncodeThrowsExceptionIfInputLengthIsNotMultipleOf4(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Input to encode must have a length multiple of 4');
        $this->encoder->encode([0]);
    }

    public function testDecodeThrowsExceptionIfInputLengthIsNotMultipleOf7(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Input to decode must have a length multiple of 7');
        $this->encoder->decode('A');
    }

    public function testDecodeThrowsExceptionIfInputContainsCharacterNotInAlphabet(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Input to decode contains an invalid character');
        $this->encoder->decode('ZZZZZZI');
    }

    const TEST_MAPPINGS = [
        ["00000000", "ZZZZZZZ"],
        ["000000000000000000000000", "ZZZZZZZZZZZZZZZZZZZZZ"],
        ["00000001", "ZZZZZZA"],
        ["000000010000000100000001", "ZZZZZZAZZZZZZAZZZZZZA"],
        ["00000002", "ZZZZZZC"],
        ["00000004", "ZZZZZZB"],
        ["00000008", "ZZZZZZ4"],
        ["00000010", "ZZZZZZP"],
        ["00000020", "ZZZZZA4"],
        ["00000030", "ZZZZZCZ"],
        ["00000040", "ZZZZZCP"],
        ["00000080", "ZZZZZ34"],
        ["00000100", "ZZZZZHP"],
        ["00000200", "ZZZZZW4"],
        ["00000400", "ZZZZARP"],
        ["00000800", "ZZZZ2K4"],
        ["00001000", "ZZZZFCP"],
        ["00002000", "ZZZZ634"],
        ["00004000", "ZZZABHP"],
        ["00008000", "ZZZC4W4"],
        ["00010000", "ZZZB8RP"],
        ["00020000", "ZZZG5K4"],
        ["00040000", "ZZZRYCP"],
        ["00080000", "ZZAKX34"],
        ["00100000", "ZZ229HP"],
        ["00200000", "ZZEFPW4"],
        ["00400000", "ZZT7GRP"],
        ["00800000", "ZAAESK4"],
        ["01000000", "ZCCK7CP"],
        ["02000000", "ZB32E34"],
        ["04000000", "Z4HETHP"],
        ["08000000", "ZP9KZW4"],
        ["10000000", "AG8CARP"],
        ["1234567887654321", "A64KHWZ5WEPAGG"],
        ["20000000", "CSHB2K4"],
        ["25896984125478546598563251452658", "2FC28KTA66WRST4XAHRRCF237S8Z"],
        ["40000000", "3694FCP"],
        ["80000000", "53PP634"],
        ["88553311", "5YEATXA"],
        ["FF0001FF001101FF01023399", "XGES63FZZ247C7ZC2ZA6G"],
        ["FFFFFFFF", "X5GGBH7"],
        ["FFFFFFFFFFFFFFFFFFFFFFFF", "X5GGBH7X5GGBH7X5GGBH7"],
        ['00000000', 'ZZZZZZZ'],
        ['000000000000000000000000', 'ZZZZZZZZZZZZZZZZZZZZZ'],
        ['00000001', 'ZZZZZZA'],
        ['000000010000000100000001', 'ZZZZZZAZZZZZZAZZZZZZA'],
        ['00000010', 'ZZZZZZP'],
        ['00000030', 'ZZZZZCZ'],
        ['1234567887654321', 'A64KHWZ5WEPAGG'],
        ['1234567887654321', 'a64khwz5wepagg'],
        ['25896984125478546598563251452658', '2FC28KTA66WRST4XAHRRCF237S8Z'],
        ['25896984125478546598563251452658', '2fc28kta66wrst4xahrrcf237s8z'],
        ['88553311', '5YEATXA'],
        ['FF0001FF001101FF01023399', 'XGES63FZZ247C7ZC2ZA6G'],
        ['FF0001FF001101FF01023399', 'xges63fzz247c7zc2za6g'],
        ['FFFFFFFF', 'X5GGBH7'],
        ['FFFFFFFFFFFFFFFFFFFFFFFF', 'X5GGBH7X5GGBH7X5GGBH7'],
        ['FFFFFFFFFFFFFFFFFFFFFFFF', 'x5ggbh7x5ggbh7x5ggbh7'],
    ];

    public function getTestData(): array
    {
        $testData = [];
        foreach (self::TEST_MAPPINGS as $mapping) {
            [$decoded, $encoded] = $mapping;

            $testData[] = [array_map('hexdec', str_split($decoded, 2)), $encoded];
        }
        return $testData;
    }

    public function getBinaryTestData(): array
    {
        $testData = [];
        foreach (self::TEST_MAPPINGS as $mapping) {
            [$decoded, $encoded] = $mapping;
            $testData[] = [hex2bin($decoded), $encoded];
        }
        return $testData;
    }
}
