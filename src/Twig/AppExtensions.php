<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtensions extends AbstractExtension
{
  public function getFilters()
  {
    return [
      new TwigFilter('price', [$this,'formatPrice'])
    ];
  }

  public function formatPrice($number)
  {
    return number_format($number, '2', ',').'€';
  }
}
