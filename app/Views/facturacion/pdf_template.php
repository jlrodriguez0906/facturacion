<?php
// Helper para obtener valores tanto si $factura/$cliente son ARREGLOS o son OBJETOS
$getVal = function ($data, $keys, $default = '') {
    if (empty($data)) return $default;
    foreach ((array)$keys as $key) {
        if (is_array($data) && array_key_exists($key, $data) && !is_null($data[$key])) {
            return $data[$key];
        }
        if (is_object($data) && isset($data->$key) && !is_null($data->$key)) {
            return $data->$key;
        }
    }
    return $default;
};

$idFactura   = $getVal($factura ?? null, ['id_venta'], 0);
$fechaFact   = $getVal($factura ?? null, ['fecha'], null);
$totalFact   = $getVal($factura ?? null, ['total'], 0);
$clienteNom  = $getVal($factura ?? null, ['cliente_nombre'], 'Sin cliente');
$clienteCed  = $getVal($factura ?? null, ['cliente_identificacion'], 'No registrado');
$clienteMail = $getVal($factura ?? null, ['cliente_correo'], 'No registrado');
$clienteTel  = $getVal($factura ?? null, ['cliente_telefono'], 'No registrado');
$usuarioNom  = $getVal($factura ?? null, ['usuario_nombre'], 'No registrado');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Factura #<?= str_pad($idFactura, 6, '0', STR_PAD_LEFT) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #fff;
        }

        .invoice-card {
            max-width: 800px;
            margin: auto;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .invoice-header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            padding: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .invoice-header h1 {
            margin: 0;
            font-size: 28px;
            letter-spacing: 1px;
        }

        .invoice-header p {
            margin: 5px 0 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .invoice-body {
            padding: 30px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-box h3 {
            margin-top: 0;
            font-size: 14px;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 5px;
        }

        .info-box p {
            margin: 4px 0;
            font-size: 14px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .table th {
            background-color: #f8fafc;
            color: #475569;
            text-align: left;
            padding: 12px;
            font-size: 13px;
            text-transform: uppercase;
            border-bottom: 2px solid #e2e8f0;
        }

        .table td {
            padding: 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        .table tr:nth-child(even) {
            background-color: #fafafa;
        }

        .text-right {
            text-align: right;
        }

        .totals {
            width: 40%;
            margin-left: auto;
            margin-top: 20px;
        }

        .totals table {
            width: 100%;
        }

        .totals td {
            padding: 6px 12px;
        }

        .grand-total {
            font-weight: bold;
            font-size: 18px;
            color: #4f46e5;
            border-top: 2px solid #4f46e5;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
        }

        /* Ocultar elementos de UI durante la impresión */
        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }

            .invoice-card {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>

<body>

    <div class="no-print" style="max-width: 800px; margin: 0 auto 15px; text-align: right;">
        <button onclick="window.print()" style="background: #4f46e5; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold;">
            🖨️ Imprimir / Guardar en PDF
        </button>
    </div>

    <div class="invoice-card">
        <!-- Encabezado con gradiente -->
        <div class="invoice-header">
            <div>
                <h1>FACTURA</h1>
                <p>Nº Factura: #<?= str_pad($idFactura, 6, '0', STR_PAD_LEFT) ?></p>
            </div>
            <div style="text-align: right;">
                <h2 style="margin:0; font-size: 20px;">Sistema de Facturación</h2>
                <p>Fecha: <?= $fechaFact ? date('d/m/Y H:i', strtotime($fechaFact)) : 'No registrada' ?></p>
            </div>
        </div>

        <div class="invoice-body">
            <!-- Datos del Cliente / Empresa -->
            <div class="info-grid">
                <div class="info-box">
                    <h3>Cliente</h3>
                    <p><strong>Nombre:</strong> <?= esc($clienteNom) ?></p>
                    <p><strong>Cédula/RUC:</strong> <?= esc($clienteCed) ?></p>
                    <p><strong>Teléfono:</strong> <?= esc($clienteTel) ?></p>
                    <p><strong>Email:</strong> <?= esc($clienteMail) ?></p>
                </div>
                <div class="info-box">
                    <h3>Detalles de la Venta</h3>
                    <p><strong>Atendido por:</strong> <?= esc($usuarioNom) ?></p>
                    <p><strong>ID de cliente:</strong> <?= esc($getVal($factura ?? null, ['id_cliente'], 'No registrado')) ?></p>
                </div>
            </div>

            <!-- Detalle de Productos -->
            <!-- Detalle de Productos Dinámico -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th class="text-right">Cant.</th>
                        <th class="text-right">P. Unitario</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($detalles) && is_array($detalles)): ?>
                        <?php foreach ($detalles as $row): ?>
                            <?php
                            // Extraer los campos soportando tanto arrays como objetos
                            $nombre   = $getVal($row, ['producto_nombre'], 'Producto sin nombre');
                            $cantidad = (float) $getVal($row, ['cantidad'], 0);
                            $precio   = (float) $getVal($row, ['precio_unitario'], 0);
                            $subtotal = (float) $getVal($row, ['subtotal'], $cantidad * $precio);
                            ?>
                            <tr>
                                <td><?= esc($nombre) ?></td>
                                <td class="text-right"><?= $cantidad ?></td>
                                <td class="text-right">$<?= number_format($precio, 2) ?></td>
                                <td class="text-right">$<?= number_format($subtotal, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: #888;">
                                No se encontraron productos registrados para esta factura.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <!-- Totales -->
            <div class="totals">
                <table>
                    <tr class="grand-total">
                        <td>Total:</td>
                        <td class="text-right">$<?= number_format((float)$totalFact, 2) ?></td>
                    </tr>
                </table>
            </div>

            <div class="footer">
                <p>¡Gracias por su compra!</p>
            </div>
        </div>
    </div>

    <script>
        // Disparar ventana de impresión automáticamente
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>