<?php
namespace Packaged\DalSchema\Databases\Mysql;

use Packaged\Enum\AbstractEnum;

class MySQLCharacterSet extends AbstractEnum
{
  const BIG5 = 'big5';
  const DEC8 = 'dec8';
  const CP850 = 'cp850';
  const HP8 = 'hp8';
  const KOI8R = 'koi8r';
  const LATIN1 = 'latin1';
  const LATIN2 = 'latin2';
  const SWE7 = 'swe7';
  const ASCII = 'ascii';
  const UJIS = 'ujis';
  const SJIS = 'sjis';
  const HEBREW = 'hebrew';
  const TIS620 = 'tis620';
  const EUCKR = 'euckr';
  const KOI8U = 'koi8u';
  const GB2312 = 'gb2312';
  const GREEK = 'greek';
  const CP1250 = 'cp1250';
  const GBK = 'gbk';
  const LATIN5 = 'latin5';
  const ARMSCII8 = 'armscii8';
  const UTF8 = 'utf8';
  const UTF8MB3 = 'utf8mb3';
  const UCS2 = 'ucs2';
  const CP866 = 'cp866';
  const KEYBCS2 = 'keybcs2';
  const MACCE = 'macce';
  const MACROMAN = 'macroman';
  const CP852 = 'cp852';
  const LATIN7 = 'latin7';
  const UTF8MB4 = 'utf8mb4';
  const CP1251 = 'cp1251';
  const UTF16 = 'utf16';
  const UTF16LE = 'utf16le';
  const CP1256 = 'cp1256';
  const CP1257 = 'cp1257';
  const UTF32 = 'utf32';
  const BINARY = 'binary';
  const GEOSTD8 = 'geostd8';
  const CP932 = 'cp932';
  const EUCJPMS = 'eucjpms';
  const GB18030 = 'gb18030';

  public static function getDisplayValue($value)
  {
    switch($value)
    {
      case self::BIG5:
        return 'Big5 Traditional Chinese';
      case self::DEC8:
        return 'DEC West European';
      case self::CP850:
        return 'DOS West European';
      case self::HP8:
        return 'HP West European';
      case self::KOI8R:
        return 'KOI8-R Relcom Russian';
      case self::LATIN1:
        return 'cp1252 West European';
      case self::LATIN2:
        return 'ISO 8859-2 Central European';
      case self::SWE7:
        return '7bit Swedish';
      case self::ASCII:
        return 'US ASCII';
      case self::UJIS:
        return 'EUC-JP Japanese';
      case self::SJIS:
        return 'Shift-JIS Japanese';
      case self::HEBREW:
        return 'ISO 8859-8 Hebrew';
      case self::TIS620:
        return 'TIS620 Thai';
      case self::EUCKR:
        return 'EUC-KR Korean';
      case self::KOI8U:
        return 'KOI8-U Ukrainian';
      case self::GB2312:
        return 'GB2312 Simplified Chinese';
      case self::GREEK:
        return 'ISO 8859-7 Greek';
      case self::CP1250:
        return 'Windows Central European';
      case self::GBK:
        return 'GBK Simplified Chinese';
      case self::LATIN5:
        return 'ISO 8859-9 Turkish';
      case self::ARMSCII8:
        return 'ARMSCII-8 Armenian';
      case self::UTF8:
      case self::UTF8MB3:
        return 'UTF-8 Unicode';
      case self::UCS2:
        return 'UCS-2 Unicode';
      case self::CP866:
        return 'DOS Russian';
      case self::KEYBCS2:
        return 'DOS Kamenicky Czech-Slovak';
      case self::MACCE:
        return 'Mac Central European';
      case self::MACROMAN:
        return 'Mac West European';
      case self::CP852:
        return 'DOS Central European';
      case self::LATIN7:
        return 'ISO 8859-13 Baltic';
      case self::UTF8MB4:
        return 'UTF-8 Unicode';
      case self::CP1251:
        return 'Windows Cyrillic';
      case self::UTF16:
        return 'UTF-16 Unicode';
      case self::UTF16LE:
        return 'UTF-16LE Unicode';
      case self::CP1256:
        return 'Windows Arabic';
      case self::CP1257:
        return 'Windows Baltic';
      case self::UTF32:
        return 'UTF-32 Unicode';
      case self::BINARY:
        return 'Binary pseudo charset';
      case self::GEOSTD8:
        return 'GEOSTD8 Georgian';
      case self::CP932:
        return 'SJIS for Windows Japanese';
      case self::EUCJPMS:
        return 'UJIS for Windows Japanese';
      case self::GB18030:
        return 'China National Standard GB18030';
    }
    return parent::getDisplayValue($value);
  }

  public function getDefaultCollation(): ?MySQLCollation
  {
    switch($this->getValue())
    {
      case self::BIG5:
        return new MySQLCollation(MySQLCollation::BIG5_CHINESE_CI);
      case self::DEC8:
        return new MySQLCollation(MySQLCollation::DEC8_SWEDISH_CI);
      case self::CP850:
        return new MySQLCollation(MySQLCollation::CP850_GENERAL_CI);
      case self::HP8:
        return new MySQLCollation(MySQLCollation::HP8_ENGLISH_CI);
      case self::KOI8R:
        return new MySQLCollation(MySQLCollation::KOI8R_GENERAL_CI);
      case self::LATIN1:
        return new MySQLCollation(MySQLCollation::LATIN1_SWEDISH_CI);
      case self::LATIN2:
        return new MySQLCollation(MySQLCollation::LATIN2_GENERAL_CI);
      case self::SWE7:
        return new MySQLCollation(MySQLCollation::SWE7_SWEDISH_CI);
      case self::ASCII:
        return new MySQLCollation(MySQLCollation::ASCII_GENERAL_CI);
      case self::UJIS:
        return new MySQLCollation(MySQLCollation::UJIS_JAPANESE_CI);
      case self::SJIS:
        return new MySQLCollation(MySQLCollation::SJIS_JAPANESE_CI);
      case self::HEBREW:
        return new MySQLCollation(MySQLCollation::HEBREW_GENERAL_CI);
      case self::TIS620:
        return new MySQLCollation(MySQLCollation::TIS620_THAI_CI);
      case self::EUCKR:
        return new MySQLCollation(MySQLCollation::EUCKR_KOREAN_CI);
      case self::KOI8U:
        return new MySQLCollation(MySQLCollation::KOI8U_GENERAL_CI);
      case self::GB2312:
        return new MySQLCollation(MySQLCollation::GB2312_CHINESE_CI);
      case self::GREEK:
        return new MySQLCollation(MySQLCollation::GREEK_GENERAL_CI);
      case self::CP1250:
        return new MySQLCollation(MySQLCollation::CP1250_GENERAL_CI);
      case self::GBK:
        return new MySQLCollation(MySQLCollation::GBK_CHINESE_CI);
      case self::LATIN5:
        return new MySQLCollation(MySQLCollation::LATIN5_TURKISH_CI);
      case self::ARMSCII8:
        return new MySQLCollation(MySQLCollation::ARMSCII8_GENERAL_CI);
      case self::UTF8:
        return new MySQLCollation(MySQLCollation::UTF8MB3_GENERAL_CI);
      case self::UCS2:
        return new MySQLCollation(MySQLCollation::UCS2_GENERAL_CI);
      case self::CP866:
        return new MySQLCollation(MySQLCollation::CP866_GENERAL_CI);
      case self::KEYBCS2:
        return new MySQLCollation(MySQLCollation::KEYBCS2_GENERAL_CI);
      case self::MACCE:
        return new MySQLCollation(MySQLCollation::MACCE_GENERAL_CI);
      case self::MACROMAN:
        return new MySQLCollation(MySQLCollation::MACROMAN_GENERAL_CI);
      case self::CP852:
        return new MySQLCollation(MySQLCollation::CP852_GENERAL_CI);
      case self::LATIN7:
        return new MySQLCollation(MySQLCollation::LATIN7_GENERAL_CI);
      case self::UTF8MB4:
        return new MySQLCollation(MySQLCollation::UTF8MB4_GENERAL_CI);
      case self::CP1251:
        return new MySQLCollation(MySQLCollation::CP1251_GENERAL_CI);
      case self::UTF16:
        return new MySQLCollation(MySQLCollation::UTF16_GENERAL_CI);
      case self::UTF16LE:
        return new MySQLCollation(MySQLCollation::UTF16LE_GENERAL_CI);
      case self::CP1256:
        return new MySQLCollation(MySQLCollation::CP1256_GENERAL_CI);
      case self::CP1257:
        return new MySQLCollation(MySQLCollation::CP1257_GENERAL_CI);
      case self::UTF32:
        return new MySQLCollation(MySQLCollation::UTF32_GENERAL_CI);
      case self::BINARY:
        return new MySQLCollation(MySQLCollation::BINARY);
      case self::GEOSTD8:
        return new MySQLCollation(MySQLCollation::GEOSTD8_GENERAL_CI);
      case self::CP932:
        return new MySQLCollation(MySQLCollation::CP932_JAPANESE_CI);
      case self::EUCJPMS:
        return new MySQLCollation(MySQLCollation::EUCJPMS_JAPANESE_CI);
      case self::GB18030:
        return new MySQLCollation(MySQLCollation::GB18030_CHINESE_CI);
    }
    return null;
  }
}
