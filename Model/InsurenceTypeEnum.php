<?php

namespace Model;

enum InsurenceTypeEnum: string
{
    case POJISTENI_MAJETKU = 'Pojištění majetku';
    case ZIVOTNI_POJISTENI = 'Životní pojištění';
    case CESTOVNI_POJISTENI = 'Cestovní pojištění';
    case URAZOVE_POJISTENI = 'Úrazové pojištění';
    case POVINNE_RUCENI = 'Povinné ručení';
    case HAVARIJNI_POJISTENI = 'Havarijní pojištění';
}
