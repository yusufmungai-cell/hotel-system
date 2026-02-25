@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-6">Finance — Profit & Loss</h1>

<!-- FILTER -->
<div class="bg-white p-4 rounded shadow mb-6">
    <form method="GET" class="flex items-center gap-4">
        <label class="font-semibold">Select Month:</label>

        <input type="month"
               name="month"
               value="{{ $selectedMonth }}"
               class="border p-2 rounded">

        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Filter
        </button>
    </form>
</div>

<!-- SUMMARY CARDS -->
<div class="grid grid-cols-3 gap-6 mb-6">

    <!-- Income -->
    <div class="bg-green-100 p-5 rounded shadow">
        <h3 class="font-bold mb-2">Total Income</h3>

        <p>Restaurant: <b>KES {{ number_format($restaurantSales,2) }}</b></p>
        <p>Rooms: <b>KES {{ number_format($roomRevenue,2) }}</b></p>

        <hr class="my-3">

        <p class="text-xl font-bold">
            KES {{ number_format($totalIncome,2) }}
        </p>
    </div>

    <!-- Expenses -->
    <div class="bg-red-100 p-5 rounded shadow">
        <h3 class="font-bold mb-2">Expenses</h3>

        <p>Ingredient Usage: <b>KES {{ number_format(abs($ingredientExpense),2) }}</b></p>
        <p>Supplier Payable: <b>KES {{ number_format($supplierPayable,2) }}</b></p>
        <p>Salaries: <b>KES {{ number_format($salaryExpense,2) }}</b></p>

        <hr class="my-3">

        <p class="text-xl font-bold">
            KES {{ number_format($totalExpense,2) }}
        </p>
    </div>

    <!-- Profit -->
    <div class="bg-blue-100 p-5 rounded shadow">
        <h3 class="font-bold mb-2">Net Profit</h3>

        <p class="text-2xl font-bold">
            KES {{ number_format($netProfit,2) }}
        </p>

        <p class="text-sm mt-2">
            Margin: {{ number_format($profitMargin,2) }}%
        </p>
    </div>

</div>

<!-- TODAY CARDS -->
<div class="grid grid-cols-3 gap-6 mb-8">

    <div class="bg-green-50 p-4 rounded shadow">
        <h4 class="font-bold">Today Restaurant</h4>
        <p class="text-xl font-semibold">KES {{ number_format($todayRestaurant,2) }}</p>
    </div>

    <div class="bg-blue-50 p-4 rounded shadow">
        <h4 class="font-bold">Today Rooms</h4>
        <p class="text-xl font-semibold">KES {{ number_format($todayRooms,2) }}</p>
    </div>

    <div class="bg-purple-50 p-4 rounded shadow">
        <h4 class="font-bold">Supplier Payable</h4>
        <p class="text-xl font-semibold">KES {{ number_format($supplierPayable,2) }}</p>
    </div>

</div>

<!-- YEARLY SUMMARY -->
<div class="bg-white p-6 rounded shadow mb-8">
    <h3 class="font-bold mb-3">Yearly Income ({{ substr($selectedMonth,0,4) }})</h3>

    <p class="text-2xl font-bold">
        KES {{ number_format($yearlyIncome,2) }}
    </p>
</div>

<!-- CHART -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="bg-white p-6 rounded shadow">
    <h3 class="font-bold mb-4">Yearly Income vs Expense</h3>
    <canvas id="financeChart"></canvas>
</div>

<script>
const data = @json($monthlyData);

const labels = data.map(d => 'Month ' + d.month);
const income = data.map(d => d.income);
const expense = data.map(d => d.expense);

new Chart(document.getElementById('financeChart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Income',
                data: income,
                backgroundColor: 'rgba(34,197,94,0.7)'
            },
            {
                label: 'Expense',
                data: expense,
                backgroundColor: 'rgba(239,68,68,0.7)'
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' }
        }
    }
});
</script>

@endsection
