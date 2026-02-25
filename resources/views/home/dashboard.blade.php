@extends('layouts.app')

@section('content')

{{-- =======================
    TOP SUMMARY CARDS
======================= --}}
<div class="grid grid-cols-4 gap-6 mb-6">

    <div class="bg-green-100 p-5 rounded shadow">
        <h4 class="font-bold">Today Restaurant</h4>
        <p class="text-2xl font-bold">KES {{ number_format($todayRestaurant,2) }}</p>
    </div>

    <div class="bg-blue-100 p-5 rounded shadow">
        <h4 class="font-bold">Today Rooms</h4>
        <p class="text-2xl font-bold">KES {{ number_format($todayRooms,2) }}</p>
    </div>

    <div class="bg-purple-100 p-5 rounded shadow">
        <h4 class="font-bold">Today Income</h4>
        <p class="text-2xl font-bold">KES {{ number_format($todayIncome,2) }}</p>
    </div>

    <div class="bg-red-100 p-5 rounded shadow">
        <h4 class="font-bold">Today Expense</h4>
        <p class="text-2xl font-bold">KES {{ number_format($todayExpense,2) }}</p>
    </div>

</div>

{{-- =======================
    SECOND ROW
======================= --}}
<div class="grid grid-cols-3 gap-6 mb-10">

    <div class="bg-white p-5 rounded shadow">
        <h4 class="font-bold mb-2">Today's Profit</h4>
        <p class="text-3xl font-bold text-green-600">
            KES {{ number_format($todayProfit,2) }}
        </p>
    </div>

    <div class="bg-white p-5 rounded shadow">
        <h4 class="font-bold mb-2">Room Occupancy</h4>
        <p>{{ $occupiedRooms }} / {{ $totalRooms }} Rooms</p>
        <p class="font-bold text-lg">
            {{ number_format($occupancyRate,1) }}%
        </p>
    </div>

    <div class="bg-white p-5 rounded shadow">
        <h4 class="font-bold mb-2">Total Employees</h4>
        <p class="text-3xl font-bold">
            {{ $employeeCount }}
        </p>
    </div>

</div>

{{-- =======================
    YEAR FILTER
======================= --}}
<form method="GET" class="mb-6">
    <label class="font-semibold">Select Year:</label>
    <input type="number" name="year"
           value="{{ $currentYear }}"
           class="border p-2 rounded w-32">
    <button class="bg-blue-600 text-white px-3 py-2 rounded">
        Filter
    </button>
</form>

{{-- =======================
    CHARTS
======================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="grid grid-cols-2 gap-6">

    <div class="bg-white p-6 rounded shadow">
        <h3 class="font-bold mb-4">Yearly Income vs Expense</h3>
        <canvas id="incomeExpenseChart"></canvas>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <h3 class="font-bold mb-4">Executive Financial Trend</h3>
        <canvas id="profitTrendChart"></canvas>
    </div>

</div>

{{-- =======================
    TOP SELLING ITEMS
======================= --}}
<div class="mt-10 bg-white p-6 rounded shadow">
    <h3 class="font-bold mb-4">Top Selling Items</h3>
    <canvas id="topItemsChart"></canvas>
</div>

<script>
const chartData = @json($monthlyData);
const topItems = @json($topItems);

if(chartData.length > 0){

    const labels = chartData.map(item => item.month);
    const incomeData = chartData.map(item => Number(item.income));
    const expenseData = chartData.map(item => Number(item.expense));
    const profitData = chartData.map(item => Number(item.profit));

    // Income vs Expense
    new Chart(document.getElementById('incomeExpenseChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Income',
                    data: incomeData,
                    backgroundColor: 'rgba(34,197,94,0.7)'
                },
                {
                    label: 'Expense',
                    data: expenseData,
                    backgroundColor: 'rgba(239,68,68,0.7)'
                }
            ]
        },
        options: { responsive: true }
    });

    // Profit Trend
    new Chart(document.getElementById('profitTrendChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Profit',
                    data: profitData,
                    borderColor: 'rgba(59,130,246,1)',
                    backgroundColor: 'rgba(59,130,246,0.2)',
                    fill: true,
                    tension: 0.3
                }
            ]
        },
        options: { responsive: true }
    });
}

// Top Items Chart
if(topItems.length > 0){
    new Chart(document.getElementById('topItemsChart'), {
        type: 'bar',
        data: {
            labels: topItems.map(i => i.name),
            datasets: [{
                label: 'Quantity Sold',
                data: topItems.map(i => i.total_qty),
                backgroundColor: 'rgba(99,102,241,0.7)'
            }]
        },
        options: { responsive: true }
    });
}
</script>

@endsection
