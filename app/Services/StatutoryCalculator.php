<?php

namespace App\Services;

class StatutoryCalculator
{

    /* =========================
        NSSF 2024 (TIER I & II)
       ========================= */
    public static function nssf($gross)
    {
        $tier1_limit = 7000;
        $tier2_limit = 36000;

        $tier1 = min($gross, $tier1_limit) * 0.06;

        $tier2 = 0;
        if($gross > $tier1_limit){
            $tier2 = min($gross - $tier1_limit, $tier2_limit - $tier1_limit) * 0.06;
        }

        return round($tier1 + $tier2,2);
    }


    /* =========================
            NHIF BANDS
       ========================= */
    public static function nhif($gross)
    {
        $bands = [
            [0,5999,150],[6000,7999,300],[8000,11999,400],[12000,14999,500],
            [15000,19999,600],[20000,24999,750],[25000,29999,850],[30000,34999,900],
            [35000,39999,950],[40000,44999,1000],[45000,49999,1100],[50000,59999,1200],
            [60000,69999,1300],[70000,79999,1400],[80000,89999,1500],[90000,99999,1600],
            [100000,999999999,1700]
        ];

        foreach($bands as $band){
            if($gross >= $band[0] && $gross <= $band[1]){
                return $band[2];
            }
        }

        return 0;
    }


    /* =========================
            PAYE TAX
       ========================= */
    public static function paye($taxable)
    {
        $tax = 0;

        if($taxable <= 24000){
            $tax = $taxable * 0.10;
        }
        elseif($taxable <= 32333){
            $tax = (24000 * 0.10) +
                   (($taxable - 24000) * 0.25);
        }
        else{
            $tax = (24000 * 0.10) +
                   ((32333 - 24000) * 0.25) +
                   (($taxable - 32333) * 0.30);
        }

        // personal relief
        $tax -= 2400;

        return max(round($tax,2),0);
    }

}