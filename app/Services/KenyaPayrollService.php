<?php

namespace App\Services;

class KenyaPayrollService
{

    /* =========================
       NSSF (Tier I + II simplified)
       ========================= */
    public static function nssf($gross)
    {
        // 6% capped at 1080 employee contribution
        return min($gross * 0.06, 1080);
    }

    /* =========================
       NHIF (2024 rates)
       ========================= */
    public static function nhif($gross)
    {
        $bands = [
            5999=>150,7999=>300,11999=>400,14999=>500,19999=>600,
            24999=>750,29999=>850,34999=>900,39999=>950,44999=>1000,
            49999=>1100,59999=>1200,69999=>1300,79999=>1400,89999=>1500,
            99999=>1600
        ];

        foreach($bands as $limit=>$amount){
            if($gross <= $limit) return $amount;
        }

        return 1700;
    }

    /* =========================
       PAYE (2024 tax bands simplified)
       ========================= */
    public static function paye($taxable)
    {
        $tax = 0;

        if($taxable <= 24000){
            $tax = $taxable * 0.10;
        }
        elseif($taxable <= 32333){
            $tax = 24000 * 0.10
                + ($taxable-24000)*0.25;
        }
        else{
            $tax = 24000*0.10
                + 8333*0.25
                + ($taxable-32333)*0.30;
        }

        // personal relief
        $tax -= 2400;

        return max($tax,0);
    }
	<?php

namespace App\Services;

class KenyaPayrollService
{

    /* =========================
       NSSF (Tier I + II simplified)
       ========================= */
    public static function nssf($gross)
    {
        // 6% capped at 1080 employee contribution
        return min($gross * 0.06, 1080);
    }

    /* =========================
       NHIF (2024 rates)
       ========================= */
    public static function nhif($gross)
    {
        $bands = [
            5999=>150,7999=>300,11999=>400,14999=>500,19999=>600,
            24999=>750,29999=>850,34999=>900,39999=>950,44999=>1000,
            49999=>1100,59999=>1200,69999=>1300,79999=>1400,89999=>1500,
            99999=>1600
        ];

        foreach($bands as $limit=>$amount){
            if($gross <= $limit) return $amount;
        }

        return 1700;
    }

    /* =========================
       PAYE (2024 tax bands simplified)
       ========================= */
    public static function paye($taxable)
    {
        $tax = 0;

        if($taxable <= 24000){
            $tax = $taxable * 0.10;
        }
        elseif($taxable <= 32333){
            $tax = 24000 * 0.10
                + ($taxable-24000)*0.25;
        }
        else{
            $tax = 24000*0.10
                + 8333*0.25
                + ($taxable-32333)*0.30;
        }

        // personal relief
        $tax -= 2400;

        return max($tax,0);
    }
}<?php

namespace App\Services;

class KenyaPayrollService
{

    /* =========================
       NSSF (Tier I + II simplified)
       ========================= */
    public static function nssf($gross)
    {
        // 6% capped at 1080 employee contribution
        return min($gross * 0.06, 1080);
    }

    /* =========================
       NHIF (2024 rates)
       ========================= */
    public static function nhif($gross)
    {
        $bands = [
            5999=>150,7999=>300,11999=>400,14999=>500,19999=>600,
            24999=>750,29999=>850,34999=>900,39999=>950,44999=>1000,
            49999=>1100,59999=>1200,69999=>1300,79999=>1400,89999=>1500,
            99999=>1600
        ];

        foreach($bands as $limit=>$amount){
            if($gross <= $limit) return $amount;
        }

        return 1700;
    }

    /* =========================
       PAYE (2024 tax bands simplified)
       ========================= */
    public static function paye($taxable)
    {
        $tax = 0;

        if($taxable <= 24000){
            $tax = $taxable * 0.10;
        }
        elseif($taxable <= 32333){
            $tax = 24000 * 0.10
                + ($taxable-24000)*0.25;
        }
        else{
            $tax = 24000*0.10
                + 8333*0.25
                + ($taxable-32333)*0.30;
        }

        // personal relief
        $tax -= 2400;

        return max($tax,0);
    }
	// GROSS (same as preview)
$daysWorked = Attendance::where('employee_id',$employee->id)
    ->whereYear('date',$month->year)
    ->whereMonth('date',$month->month)
    ->where('is_approved',1)
    ->sum(DB::raw("
        CASE
            WHEN status = 'present' THEN 1
            WHEN status = 'halfday' THEN 0.5
            ELSE 0
        END
    "));

$salary = $employee->monthly_salary ?? $employee->salary ?? 0;
$gross = ($salary / 26) * $daysWorked;


/* ========= STATUTORY ========= */

$nssf = KenyaPayrollService::nssf($gross);
$nhif = KenyaPayrollService::nhif($gross);

$taxable = $gross - $nssf;
$paye = KenyaPayrollService::paye($taxable);

$deductions = $nssf + $nhif + $paye;
$net = $gross - $deductions;
}
Payroll::updateOrCreate(
[
    'employee_id'=>$employee->id,
    'payroll_month'=>$month->format('Y-m-01')
],
[
    'gross_pay'=>$gross,
    'nssf'=>$nssf,
    'nhif'=>$nhif,
    'paye'=>$paye,
    'deductions'=>$deductions,
    'net_pay'=>$net
]);
}