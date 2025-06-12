<?php
require_once __DIR__ . '/../vendor/autoload.php';

class PDFGenerator {
    public function generate($pedido_id, $cliente, $itens, $total) {
        $mpdf = new \Mpdf\Mpdf();
        
        $html = $this->getPDFTemplate($pedido_id, $cliente, $itens, $total);
        
        $mpdf->WriteHTML($html);
        return $mpdf->Output('', 'S'); // Retorna o PDF como string
    }
    
    private function getPDFTemplate($pedido_id, $cliente, $itens, $total) {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Pedido #<?= $pedido_id ?></title>
            <style>
                body { font-family: Arial, sans-serif; }
                .header { text-align: center; margin-bottom: 20px; }
                .header h1 { margin: 0; }
                .info { margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .total { font-weight: bold; text-align: right; }
                .footer { margin-top: 30px; font-size: 0.8em; text-align: center; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Pedido #<?= $pedido_id ?></h1>
                <p>Sua Loja - CNPJ: 00.000.000/0001-00</p>
            </div>
            
            <div class="info">
                <p><strong>Cliente:</strong> <?= htmlspecialchars($cliente['nome']) ?></p>
                <p><strong>E-mail:</strong> <?= htmlspecialchars($cliente['email']) ?></p>
                <p><strong>Endereço:</strong> <?= htmlspecialchars($cliente['endereco']) ?></p>
                <p><strong>Telefone:</strong> <?= htmlspecialchars($cliente['telefone']) ?></p>
                <p><strong>Data:</strong> <?= date('d/m/Y H:i') ?></p>
                <p><strong>Método de Pagamento:</strong> <?= htmlspecialchars($cliente['metodo_pagamento']) ?></p>
            </div>
            
            <h3>Itens do Pedido</h3>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itens as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nome']) ?></td>
                        <td><?= $item['quantidade'] ?></td>
                        <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                        <td>R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="total">Total:</td>
                        <td>R$ <?= number_format($total, 2, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
            
            <div class="footer">
                <p>Obrigado por comprar conosco!</p>
                <p>Sua Loja - <?= date('Y') ?></p>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
}
?>