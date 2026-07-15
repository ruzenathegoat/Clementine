<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FinancialReportExport implements FromView, ShouldAutoSize
{
    protected $dailyData;
    protected $period;

    public function __construct(array $dailyData, string $period)
    {
        $this->dailyData = $dailyData;
        $this->period = $period;
    }

    public function view(): View
    {
        return view('admin.financials.export-template', [
            'dailyData' => $this->dailyData,
            'period' => $this->period
        ]);
    }
}
