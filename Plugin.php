<?php namespace Nuts\Morphos;

use System\Classes\PluginBase;
use morphos\Russian\CardinalNumeralGenerator;
use morphos\Russian\MoneySpeller;
use morphos\Russian\NounPluralization;
use morphos\Russian\OrdinalNumeralGenerator;
use morphos\Russian\Plurality;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function registerMarkupTags() {
        return [
            'filters' => [
                'plural' => [$this, 'pluralFilter'],
                'money' => [$this, 'moneyFilter'],
                'numeral' => [$this, 'numeralFilter'],
                'ordinal' => [$this, 'ordinalFilter'],
                'name' => [$this, 'nameFilter'],
            ]
        ];
    }

    public function pluralFilter($word, $count) {
        return \morphos\Russian\pluralize($count, $word);
    }

    public function moneyFilter($value, $currency) {
        return MoneySpeller::spell($value, $currency, MoneySpeller::SHORT_FORMAT);
    }

    public function numeralFilter($word, $count = null, $gender = Gender::MALE) {
        if ($count === null) {
            return CardinalNumeralGenerator::getCase($word, Cases::NOMINATIVE);
        } else if (in_array($count, array('m', 'f', 'n'))) {
            return CardinalNumeralGenerator::getCase($word, Cases::NOMINATIVE, $count);
        } else {
            return CardinalNumeralGenerator::getCase($count, Cases::NOMINATIVE, $gender).' '. NounPluralization::pluralize($word, $count);
        }
    }

    public function ordinalFilter($number, $gender = Gender::MALE) {
        return OrdinalNumeralGenerator::getCase($number, Cases::NOMINATIVE, $gender);
    }

    public function nameFilter($name, $gender = null, $case = null) {
        if ($case === null)
            return \morphos\Russian\inflectName($name, $gender);
        else
            return \morphos\Russian\inflectName($name, $case, $gender);
    }
}
