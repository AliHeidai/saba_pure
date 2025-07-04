<?php
include 'NumToWord.php';

class numToWord_Fa extends numToWord {

  protected $digit1 = array(
      0 => 'صفر',
      1 => 'یک',
      2 => 'دو',
      3 => 'سه',
      4 => 'چهار',
      5 => 'پنج',
      6 => 'شش',
      7 => 'هفت',
      8 => 'هشت',
      9 => 'نه',
  );
  protected $digit1_5 = array(
      1 => 'یازده',
      2 => 'دوازده',
      3 => 'سیزده',
      4 => 'چهارده',
      5 => 'پانزده',
      6 => 'شانزده',
      7 => 'هفده',
      8 => 'هجده',
      9 => 'نوزده',
  );
  protected $digit2 = array(
      1 => 'ده',
      2 => 'بیست',
      3 => 'سی',
      4 => 'چهل',
      5 => 'پنجاه',
      6 => 'شصت',
      7 => 'هفتاد',
      8 => 'هشتاد',
      9 => 'نود'
  );
  protected $digit3 = array(
      1 => 'صد',
      2 => 'دویست',
      3 => 'سیصد',
      4 => 'چهارصد',
      5 => 'پانصد',
      6 => 'ششصد',
      7 => 'هفتصد',
      8 => 'هشتصد',
      9 => 'نهصد',
  );
  protected $steps = array(
      1 => 'هزار',
      2 => 'میلیون',
      3 => 'میلیارد',
      4 => 'تریلیون',
      5 => 'کادریلیون',
      6 => 'کوینتریلیون',
      7 => 'سکستریلیون',
      8 => 'سپتریلیون',
      9 => 'اکتریلیون',
      10 => 'نونیلیون',
      11 => 'دسیلیون',
  );
  protected $t = array(
      'and' => 'و',
  );

}