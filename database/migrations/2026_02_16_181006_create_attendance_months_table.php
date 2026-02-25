use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceMonthsTable extends Migration
{
    public function up(): void
    {
         Schema::create('attendance_months', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('month');
            $table->enum('status',['open','approved'])->default('open');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->unique(['year','month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_months');
    }
}
};
