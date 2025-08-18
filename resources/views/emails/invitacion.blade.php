@component('mail::message')
# Hola {{ $nombre }}

Comparto la liga de acceso a la Reunión de Google Meet programada para atenderle en el Procedimiento de Mediación a distancia.

**Fecha de la reunión:** {{ $fecha }}

**Horario de la reunión:**  
{{ $horario }}

Adjunto encontrará los documentos de **Aviso de Privacidad** y **Reglas del Procedimiento** para su conocimiento y observación.

Le solicito atentamente que, a la brevedad posible, me envíe a este correo electrónico los siguientes documentos escaneados de su original en formato PDF:

- Documento que acredite su identidad  
- Documento que, en su caso, acredite su personalidad jurídica  
- Documentos que acrediten su relación jurídica en conflicto  

Estos documentos deberán ser exhibidos en original al inicio de la reunión para verificarlos a través de la cámara de su equipo.

@component('mail::button', ['url' => $enlace])
Unirse a la reunión
@endcomponent

Quedo a su disposición para cualquier duda o comentario.

Gracias, Medidas Alternativas TSJCDMX
@endcomponent
