<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cjas', function (Blueprint $table) {
            // ----- Mediación -----
            // Fechas/horas de inicio
            $table->date('mediacion_fecha_inicio')->nullable()->after('acepta_inicio_inv');
            $table->time('mediacion_hora_inicio')->nullable()->after('mediacion_fecha_inicio');

            // Fechas/horas de término (corrijo: fecha + hora; evitamos el duplicado)
            $table->date('mediacion_fecha_termino')->nullable()->after('mediacion_hora_inicio');
            $table->time('mediacion_hora_termino')->nullable()->after('mediacion_fecha_termino');

            // Envío de archivo (timestamp; evitamos el duplicado)
            $table->date('fecha_envio_archivo')->nullable()->after('mediacion_hora_termino');

            // Conclusión (ids opcionales; si tienes tablas de referencia, luego puedes añadir FK)
            $table->unsignedBigInteger('conclucion_id')->nullable()->after('fecha_envio_archivo');
            $table->unsignedBigInteger('persona_concluye_id')->nullable()->after('conclucion_id');

            // $table->foreignId('conclucion_id')->nullable()->constrained('conclusiones')->nullOnDelete();
            // $table->foreignId('persona_concluye_id')->nullable()->constrained('personas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('cjas', function (Blueprint $table) {
            $table->dropColumn([
                'persona_concluye_id',
                'conclucion_id',
                'fecha_envio_archivo',
                'mediacion_hora_termino',
                'mediacion_fecha_termino',
                'mediacion_hora_inicio',
                'mediacion_fecha_inicio',
            ]);
        });
    }
};
