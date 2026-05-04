<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0; padding:0; background:#f4f8fc; font-family:'Outfit',Arial,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f8fc; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="560" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:20px; overflow:hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">

                    {{-- HEADER --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #0f2a47, #0d9e75); padding: 36px 40px; text-align:center;">
                            <h1 style="margin:0; color:#ffffff; font-size:28px; font-weight:700; letter-spacing:1px;">
                                🔬 E&M Laboratorio
                            </h1>
                            <p style="margin:8px 0 0; color:rgba(255,255,255,0.8); font-size:14px;">
                                Análisis Clínicos de Excelencia
                            </p>
                        </td>
                    </tr>

                    {{-- BODY --}}
                    <tr>
                        <td style="padding: 40px;">

                            <h2 style="color:#0f2a47; font-size:22px; margin:0 0 12px;">
                                ¡Bienvenido, {{ $usuario->nombre }}! 
                            </h2>
                            <p style="color:#6b7a8d; font-size:15px; line-height:1.7; margin:0 0 28px;">
                                Tu cita ha sido registrada exitosamente y hemos creado una cuenta para que puedas
                                ver tus <strong>resultados en línea</strong> cuando estén listos.
                            </p>

                            {{-- CREDENCIALES --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f8fc; border-radius:12px; overflow:hidden; margin-bottom:28px;">
                                <tr>
                                    <td style="padding:16px 20px; border-bottom:1px solid #e2ecf5;">
                                        <span style="font-size:12px; color:#0d9e75; font-weight:600; text-transform:uppercase; letter-spacing:1px;">Usuario</span><br>
                                        <span style="font-size:16px; color:#0f2a47; font-weight:600;">{{ $usuario->email }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:16px 20px;">
                                        <span style="font-size:12px; color:#0d9e75; font-weight:600; text-transform:uppercase; letter-spacing:1px;">Contraseña temporal</span><br>
                                        <span style="font-size:16px; color:#0f2a47; font-weight:600;">{{ $contrasena }}</span>
                                        <span style="font-size:12px; color:#6b7a8d;"> (tu DNI)</span>
                                    </td>
                                </tr>
                            </table>

                            {{-- BOTON --}}
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/login') }}"
                                           style="display:inline-block; background:linear-gradient(135deg,#0d9e75,#2db87a); color:#ffffff; text-decoration:none; padding:14px 36px; border-radius:10px; font-size:15px; font-weight:600;">
                                            Iniciar sesión →
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="color:#6b7a8d; font-size:13px; margin:28px 0 0; text-align:center;">
                                Te recomendamos cambiar tu contraseña al ingresar por primera vez.
                            </p>
                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td style="background:#f4f8fc; padding:20px 40px; text-align:center; border-top:1px solid #e2ecf5;">
                            <p style="margin:0; color:#b0bec8; font-size:12px;">
                                © {{ date('Y') }} E&M Laboratorio · Todos los derechos reservados
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>