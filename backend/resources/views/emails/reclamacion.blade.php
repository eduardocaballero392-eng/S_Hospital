<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif; background:#f4f8fc; padding:30px;">
<div style="max-width:560px; margin:0 auto; background:white; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08);">

    <div style="background:linear-gradient(135deg,#0f2a47,#0d9e75); padding:32px 40px; text-align:center;">
        <h1 style="color:white; margin:0; font-size:22px;"> Nueva Reclamación</h1>
        <p style="color:rgba(255,255,255,0.8); margin:8px 0 0; font-size:14px;">E&M Laboratorio - Libro de Reclamaciones</p>
    </div>

    <div style="padding:36px 40px;">
        <table style="width:100%; border-collapse:collapse;">
            <tr style="background:#f4f8fc;">
                <td style="padding:12px 16px; border:1px solid #e2ecf5; font-weight:600; font-size:13px; color:#0d9e75; width:40%;">Nombre</td>
                <td style="padding:12px 16px; border:1px solid #e2ecf5; font-size:14px; color:#0f2a47;">{{ $data['nombre'] }} {{ $data['apellido'] }}</td>
            </tr>
            <tr>
                <td style="padding:12px 16px; border:1px solid #e2ecf5; font-weight:600; font-size:13px; color:#0d9e75;">Documento</td>
                <td style="padding:12px 16px; border:1px solid #e2ecf5; font-size:14px; color:#0f2a47;">{{ $data['tipo_documento'] }}: {{ $data['nro_documento'] }}</td>
            </tr>
            <tr style="background:#f4f8fc;">
                <td style="padding:12px 16px; border:1px solid #e2ecf5; font-weight:600; font-size:13px; color:#0d9e75;">Email</td>
                <td style="padding:12px 16px; border:1px solid #e2ecf5; font-size:14px; color:#0f2a47;">{{ $data['email'] }}</td>
            </tr>
            <tr>
                <td style="padding:12px 16px; border:1px solid #e2ecf5; font-weight:600; font-size:13px; color:#0d9e75;">Teléfono</td>
                <td style="padding:12px 16px; border:1px solid #e2ecf5; font-size:14px; color:#0f2a47;">{{ $data['telefono'] ?? 'No indicado' }}</td>
            </tr>
            <tr style="background:#f4f8fc;">
                <td style="padding:12px 16px; border:1px solid #e2ecf5; font-weight:600; font-size:13px; color:#0d9e75;">Tipo</td>
                <td style="padding:12px 16px; border:1px solid #e2ecf5; font-size:14px; color:#0f2a47;"><strong>{{ $data['tipo_reclamo'] }}</strong></td>
            </tr>
            <tr>
                <td style="padding:12px 16px; border:1px solid #e2ecf5; font-weight:600; font-size:13px; color:#0d9e75;">Detalle</td>
                <td style="padding:12px 16px; border:1px solid #e2ecf5; font-size:14px; color:#0f2a47; line-height:1.6;">{{ $data['detalle'] }}</td>
            </tr>
        </table>
    </div>

    <div style="background:#f4f8fc; padding:20px 40px; text-align:center; border-top:1px solid #e2ecf5;">
        <p style="margin:0; color:#b0bec8; font-size:12px;">© {{ date('Y') }} E&M Laboratorio · Libro de Reclamaciones Virtual</p>
    </div>
</div>
</body>
</html>