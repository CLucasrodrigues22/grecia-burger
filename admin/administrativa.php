<?php include './layout/header.php'; ?>
<?php include './layout/menu.php'; ?>
<?php 

include 'classes/RelatorioDAO.php';
$relatorioDAO = new RelatorioDAO();
$total_clientes = $relatorioDAO->contar('clientes');
$total_produtos = $relatorioDAO->contar('produtos');
$total_vendas_finalidas = $relatorioDAO->contar('vendas', "status = 'Finalizada'");
$total_vendas_pendentes = $relatorioDAO->contar('vendas', "status = 'Pendente'");
$produtos_por_categoria = json_encode($relatorioDAO->contarProdutosCategoria('produtos'));
$vendas_por_status = json_encode($relatorioDAO->contarVendasStatus());
?>
<div class="row col">
	<h1>Dashboard</h1>
</div>
<div class="row">
	<div class="col-lg-3 col-md-3 col-sm-6">
		<div class="card">
			<div class="card-header">Quantidade Clientes</div>
			<div class="card-body card-dashboard">
				<p class="total"><?= $total_clientes['total'] ?? 0; ?></p>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6">
		<div class="card">
			<div class="card-header">Quantidade Produtos</div>
			<div class="card-body card-dashboard">
				<p class="total produtos"><?= $total_produtos['total'] ?? 0; ?></p>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6">
		<div class="card">
			<div class="card-header">Vendas Finalizadas</div>
			<div class="card-body card-dashboard">
				<p class="total finalizadas">
					<?= $total_vendas_finalidas['total'] ?? 0; ?>
				</p>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6">
		<div class="card">
			<div class="card-header">Vendas Pendentes</div>
			<div class="card-body card-dashboard">
				<p class="total pendente">
					<?= $total_vendas_pendentes['total'] ?? 0; ?>
				</p>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-12">
		<div class="card">
			<div class="card-body">
				<div id="produtos_categoria"></div>
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-12">
		<div class="card">
			<div class="card-body">
				<div id="vendas_status"></div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<div class="card-header">
			<select name="" class="form-control">
				<option value="Diaria">Relatório por dia</option>
				<option value="Semanal">Relatório por semana</option>
				<option value="Mensal">Relatório por mês</option>
				
			</select>
		</div>
		<div class="card">
			<div class="card-body">
				<div id="evolucao_vendas"></div>
			</div>
		</div>
	</div>
</div>
<?php include './layout/footer.php'; ?>
<script>
	var dadosProdCategoria = JSON.parse( '<?php echo $produtos_por_categoria; ?>' );
	var dadosVendasStatus = JSON.parse( '<?php echo $vendas_por_status; ?>' );

	dataProdCategoria = [];
	for (var x in dadosProdCategoria) {
		dataProdCategoria[x] = {
		  name: dadosProdCategoria[x].categoria,
		  y: parseInt(dadosProdCategoria[x].total)
		}
	}
	dataVendasStatus = [];
	for (var x in dadosVendasStatus) {
		dataVendasStatus[x] = {
			name: dadosVendasStatus[x].status,
			y: parseInt(dadosVendasStatus[x].total)
		}
	}
	// dataEvolucaoVendas = [];
	// for (var x in dadosEvolucaoVendas) {
	// 	dataVendasStatus[x] = {
	// 		name: dadosVendasStatus[x].status,
	// 		y: parseInt(dadosVendasStatus[x].total)
	// 	}
	// }


	Highcharts.chart('produtos_categoria', {
    chart: {
         type: 'pie',
        options3d: {
            enabled: true,
            alpha: 45,
            beta: 0
        }
    },
    title: {
        text: 'Qtd Categorias por produtos'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            depth: 35,
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            }
        }
    },
    series: [{
        name: 'Percentual',
        colorByPoint: true,
        data: dataProdCategoria,
    }]
});

Highcharts.chart('vendas_status', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: 0,
        plotShadow: false
    },
    title: {
        text: 'Status de vendas',
        align: 'center',
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            dataLabels: {
                enabled: true,
                distance: 0,
                style: {
                    /*fontWeight: 'bold',*/
                    color: 'black'
                }
            },
            startAngle: -90,
            endAngle: 90,
            center: ['50%', '75%'],
            size: '110%'
        }
    },
    series: [{
        type: 'pie',
        name: 'Browser share',
        innerSize: '50%',
        data: dataVendasStatus
    }]
});

Highcharts.chart('evolucao_vendas', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Quantidade de vendas'
    },
    subtitle: {
        text: 'Evolução diária'
    },
    yAxis: {
        title: {
            text: 'Valor (R$)'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: true
        }
    }
});
</script>